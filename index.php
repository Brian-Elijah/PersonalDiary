<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>LOGIN</title>
    <link rel="stylesheet" type ="text/css" href="styles.css">

</head>

<body>
    <form  action="login.php" method="post">
    <?php
    // Retrieve the message from the query parameter
    $message = isset($_GET['message']) ? $_GET['message'] : '';

    // Display the message
    if (!empty($message)) {
        echo '<div style="color: green;">' . htmlspecialchars($message) . '</div>';
    }
    ?>
        <h2>LOGIN</h2>
        <label>User Name</label>
        <input type="text" name="username"  placeholder="User Name" required> <br>

        <label>Password</label>
        <input type="password" name="password"   placeholder="password" required>
        <button type="submit">Login</button>
    </form>
    
</body>
</html>