<?php
session_start();
require_once 'ConnectClass.php';

$conn = new Connection();
$db = $conn->connection;

$message = "";

if (isset($_POST['SubmitButton'])) {
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    
    $addUser = $db->query("INSERT INTO Users (Username, Password, position) VALUES ('$username', '$password', 'user')");
    
    if ($addUser) {
        $message = "Registration successful, you can now log in.";
    } else {
        $message = "Registration failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/styling.css">
</head>
<body class="body">
    <div class="formCenter">
        <div class="formCard">
            <h3>Register</h3>

        <form method="post">
            <label>Username:</label>
            <input type="text" name="Username" required>

            <label>Password:</label>
            <input type="password" name="Password" required>
            <input type="submit" name="SubmitButton" value="Register" class="commentButton">
          
        </form>

        <div class="formButtons">
            <a href="startpage.php" class="buttonLink">Back to News</a>
            <a href="LoginPage.php" class="buttonLink">Log In</a>
        </div>
    </div>
</div>

</body>
</html>