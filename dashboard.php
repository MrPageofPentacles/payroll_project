<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$db = new mysqli("localhost", "root", "", "payroll_admin");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$username = $_SESSION['username'];

if (isset($_POST['update_save'])) {
    $id = intval($_POST['id']);
    $first_name = $db->real_escape_string($_POST['first_name']);
    $middle_name = $db->real_escape_string($_POST['middle_name']);
    $last_name = $db->real_escape_string($_POST['last_name']);
    $email = $db->real_escape_string($_POST['email']);
    $phone_number = $db->real_escape_string($_POST['phone_number']);
    $address = $db->real_escape_string($_POST['address']);
    $birthdate = $db->real_escape_string($_POST['birthdate']);
    $username_update = $db->real_escape_string($_POST['username']);

    $query = "UPDATE admin SET 
                first_name='$first_name',
                middle_name='$middle_name',
                last_name='$last_name',
                email='$email',
                phone_number='$phone_number',
                address='$address',
                birthdate='$birthdate',
                username='$username_update'
              WHERE id=$id";

    if ($db->query($query)) {
        $_SESSION['username'] = $username_update;
        echo "<script>alert('Profile updated successfully!'); window.location='dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating record: " . $db->error . "');</script>";
    }
}

$result = $db->query("SELECT * FROM admin WHERE username='$username' LIMIT 1");
$admin = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .profile-table {
            border-collapse: collapse;
            width: 60%;
            margin-top: 20px;
        }
        .profile-table th, .profile-table td {
            border: 1px solid #ccc;
            padding: 8px 12px;
        }
        .profile-table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .btn {
            display: inline-block;
            padding: 8px 14px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .popup {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 350px;
        }
        input {
            width: 100%;
            padding: 6px;
            margin: 6px 0 10px;
            box-sizing: border-box;
        }
        .popup-buttons {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <div>
            Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> |
            <a href="login.php">Logout</a>
        </div>
    </div>
    <hr>

    <h2>Your Account Details</h2>

    <table class="profile-table">
        <tr><th>ID</th><td><?php echo $admin['id']; ?></td></tr>
        <tr><th>First Name</th><td><?php echo htmlspecialchars($admin['first_name']); ?></td></tr>
        <tr><th>Middle Name</th><td><?php echo htmlspecialchars($admin['middle_name']); ?></td></tr>
        <tr><th>Last Name</th><td><?php echo htmlspecialchars($admin['last_name']); ?></td></tr>
        <tr><th>Email</th><td><?php echo htmlspecialchars($admin['email']); ?></td></tr>
        <tr><th>Phone</th><td><?php echo htmlspecialchars($admin['phone_number']); ?></td></tr>
        <tr><th>Address</th><td><?php echo htmlspecialchars($admin['address']); ?></td></tr>
        <tr><th>Birthdate</th><td><?php echo htmlspecialchars($admin['birthdate']); ?></td></tr>
        <tr><th>Username</th><td><?php echo htmlspecialchars($admin['username']); ?></td></tr>
    </table>

    <button class="btn" onclick="showPopup()">Update</button>

    <div class="popup" id="popup">
        <div class="popup-content">
            <h3>Update Profile</h3>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">

                <label>First Name:</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($admin['first_name']); ?>" required>

                <label>Middle Name:</label>
                <input type="text" name="middle_name" value="<?php echo htmlspecialchars($admin['middle_name']); ?>">

                <label>Last Name:</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($admin['last_name']); ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>

                <label>Phone Number:</label>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($admin['phone_number']); ?>">

                <label>Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($admin['address']); ?>">

                <label>Birthdate:</label>
                <input type="date" name="birthdate" value="<?php echo htmlspecialchars($admin['birthdate']); ?>">

                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>

                <div class="popup-buttons">
                    <button type="button" onclick="closePopup()">Cancel</button>
                    <button type="submit" name="update_save">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showPopup() {
            document.getElementById("popup").style.display = "flex";
        }
        function closePopup() {
            document.getElementById("popup").style.display = "none";
        }
    </script>
</body>
</html>

<?php $db->close(); ?>
