<?php
    if(isset($_POST["register"]))
    {
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $password = $_POST["password"];
        $password = password_hash($password, PASSWORD_DEFAULT);

        $conn = mysql_connect("localhost", "", "deprueba");
        $sql = "INSERT INTO users (email, phone, password, is_tfa_enabled, pin) VALUES ('$email', '$phone', '$password', 0, '')";

        mysqli_query($conn, $sql);
 
        header("Location: login.php");
    }
?>

<form method="POST" action="register.php">
     
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
    <input type="text" name="phone" placeholder="Phone">
     
    <input type="submit" name="register">
</form>