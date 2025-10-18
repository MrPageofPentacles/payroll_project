<?php
$db = new mysqli("localhost", "root", "", "payroll_admin");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (isset($_POST['register'])) {
    $first_name   = trim($_POST['first_name']);
    $middle_name  = trim($_POST['middle_name']);
    $last_name    = trim($_POST['last_name']);
    $email        = trim($_POST['email']);
    $phone        = trim($_POST['phone_number']);
    $address      = trim($_POST['address']);
    $birthdate    = $_POST['birthdate'];
    $username     = trim($_POST['username']);
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $db->prepare("SELECT id FROM admin WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists!'); window.history.back();</script>";
    } else {
        $stmt = $db->prepare("INSERT INTO admin 
            (first_name, middle_name, last_name, email, phone_number, address, birthdate, username, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $first_name, $middle_name, $last_name, $email, $phone, $address, $birthdate, $username, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Error during registration: " . addslashes($stmt->error) . "'); window.history.back();</script>";
        }

        $stmt->close();
    }

    $check->close();
}

$db->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Registration Page</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <h1>Registration</h1>
        <form action="register.php" method="post">
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="first_name" required><br><br>

            <label for="middlename">Middle Name:</label>
            <input type="text" id="middlename" name="middle_name" required><br><br>

            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="last_name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="phone_number">Phone:</label>
            <input type="text" id="phone_number" name="phone_number" required><br><br>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required><br><br>

            <label for="birthdate">Birthdate:</label>
            <input type="date" id="birthdate" name="birthdate" required><br><br>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit" name="register">Register</button><br>
            <a href="login.php">Login</a>
        </form>
    </body>
</html>
