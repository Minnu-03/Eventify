<?php
error_reporting(E_ALL);  
ini_set('display_errors', 1);  

include 'connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
    } else {
        $error_message = "Name field is missing.";
    }

    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $hashed_password = hash('sha512', $password);

        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $success_message = "Registration successful! You can now <a href='login.php'>login</a>.";
        } else {
            $error_message = "Error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Eventify</title>
    <link rel="stylesheet" href="register.css"> 
</head>
<body>

    <div class="form-container">
        <h2>Register</h2>

        <!-- Show success or error message if available -->
        <?php if (isset($success_message)): ?>
            <div class="success-message">
                <h3><?= $success_message; ?></h3>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="error-message">
                <h3><?= $error_message; ?></h3>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form method="POST" action="register.php">
            <label for="name">Name:</label> 
            <input type="text" name="name" id="name" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required><br>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

</body>
</html>
