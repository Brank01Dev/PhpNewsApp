<?php
session_start();
require_once 'ConnectClass.php';

if (isset($_POST['SubmitButton'])) {
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    
    $conn = new Connection();
    $db = $conn->connection;

    $stmt = $db->prepare("SELECT Id, Username, Password, position FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if ($password == $user['Password']) {
            $_SESSION['user_id'] = $user['Id'];
            $_SESSION['username'] = $user['Username'];
            $_SESSION['position'] = $user['position'];
            
            if ($user['position'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: user.php");
            }
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/styling.css">
</head>


<body class="body">

<title>Login</title>
<div class="formCenter">
    <div class="formCard">
        <h3>Login</h3>
        <form method="post">
            <label>Username:</label>
            <input type="text" name="Username" required>

            <label>Password:</label>
            <input type="password" name="Password" required>

            <input type="submit" name="SubmitButton" value="Login" class="commentButton">
        </form>

        <div class="formButtons">
            <a href="RegisterPage.php" class="buttonLink">Register</a>
            <a href="startpage.php" class="buttonLink">Back to News</a>
        </div>
    </div>
</div>

</body>
</html>