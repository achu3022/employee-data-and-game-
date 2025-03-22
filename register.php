<?php
session_start();
include "config.php"; // Database connection

$message = ""; // Message for user feedback

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = trim($_POST['emp_id']);
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $department = trim($_POST['department']);
    $email = trim($_POST['email']);

    // Check for empty fields
    if (empty($emp_id) || empty($name) || empty($phone) || empty($department) || empty($email)) {
        $message = "<div class='alert alert-danger'>All fields are required!</div>";
    } else {
        // Check if Employee ID or Email already exists
        $check_query = "SELECT emp_id FROM staffs WHERE emp_id = ? OR email = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("ss", $emp_id, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "<div class='alert alert-warning'>Employee ID or Email already exists!</div>";
        } else {
            // Insert new employee record
            $query = "INSERT INTO staffs (emp_id, name, phone, department, email) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $emp_id, $name, $phone, $department, $email);

            if ($stmt->execute()) {
                $message = "<div class='alert alert-success'>Registration successful! You can now log in.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Registration failed! Please try again.</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4>Employee Registration</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" onsubmit="disableButton()">
                            <div class="mb-3">
                                <label class="form-label">Employee ID:</label>
                                <input type="text" name="emp_id" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Name:</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone:</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Department:</label>
                                <input type="text" name="department" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <button type="submit" id="register-btn" class="btn btn-primary w-100">Register</button>
                        </form>

                        <div class="mt-3">
                            <?php echo $message; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
