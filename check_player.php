<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = trim($_POST['emp_id']);
    $ip_address = trim($_POST['ip']);
    $mac_address = trim($_POST['mac']);

    // Check if the user already played and completed the game
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
        // Insert new entry since this device/user is not found
        $stmt = $conn->prepare("INSERT INTO player_tracking (emp_id, ip_address, mac_address, completed) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("sss", $emp_id, $ip_address, $mac_address);
        $stmt->execute();

        echo json_encode(["status" => "quiz"]); // Redirect to quiz.php
    }

    $stmt->close();
    $conn->close();
}
?>
