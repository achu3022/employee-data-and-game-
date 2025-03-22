<?php
include "config.php";

header("Content-Type: application/json"); // Ensure JSON response

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['emp_id']) || empty(trim($_POST['emp_id']))) {
        echo json_encode(["status" => "error", "message" => "Employee ID is empty"]);
        exit;
    }

    $emp_id = trim($_POST['emp_id']);

    // Check if database connection is valid
    if (!$conn) {
        echo json_encode(["status" => "error", "message" => "Database connection failed"]);
        exit;
    }

    $query = "SELECT name, phone, department, email FROM staffs WHERE emp_id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Query preparation failed"]);
        exit;
    }

    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "status" => "success",
            "name" => $row["name"],
            "phone" => $row["phone"],
            "department" => $row["department"],
            "email" => $row["email"]
        
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "No user found!"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
