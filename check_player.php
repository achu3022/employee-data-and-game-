<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = trim($_POST['emp_id']);
    $ip_address = trim($_POST['ip']);
    $mac_address = trim($_POST['mac']);

    if (empty($emp_id) || empty($ip_address) || empty($mac_address)) {
        echo json_encode(["status" => "error", "message" => "Invalid request parameters"]);
        exit;
    }

    // Check if Employee ID exists in the database
    $stmt = $conn->prepare("SELECT emp_id FROM staffs WHERE emp_id = ?");
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result->fetch_assoc()) {
        echo json_encode(["status" => "error", "message" => "Employee ID not found!"]);
        exit;
    }

    // Check if the Employee has already played on this device
    $stmt = $conn->prepare("SELECT completed FROM player_tracking WHERE emp_id = ? AND mac_address = ?");
    $stmt->bind_param("ss", $emp_id, $mac_address);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['completed'] == 1) {
            echo json_encode(["status" => "results"]); // Redirect to results.php
        } else {
            echo json_encode(["status" => "warning"]); // Redirect to device_warning.php
        }
    } else {
        // Check if either Employee ID or MAC exists in the table
        $stmt = $conn->prepare("SELECT * FROM player_tracking WHERE emp_id = ? OR mac_address = ?");
        $stmt->bind_param("ss", $emp_id, $mac_address);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->fetch_assoc()) {
            echo json_encode(["status" => "warning"]); // Redirect to device_warning.php
        } else {
            // Insert new record for first-time players
            $stmt = $conn->prepare("INSERT INTO player_tracking (emp_id, ip_address, mac_address, completed) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("sss", $emp_id, $ip_address, $mac_address);
            $stmt->execute();

            echo json_encode(["status" => "quiz"]); // Redirect to quiz.php
        }
    }

    $stmt->close();
    $conn->close();
}
?>
