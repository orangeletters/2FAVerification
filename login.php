<?php
    //esta mierda funciona, hasta aquí
    session_start();
 
    require_once "twilio-php-app/vendor/autoload.php";
    use Twilio\Rest\Client;
 
    $sid = "";
    $token = "";
 
    if (isset($_POST["login"]))
    {
        $email = $_POST["email"];
        $password = $_POST["password"];
 
        $conn = new mysqli("localhost", "root", "", "deprueba");
         
        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
 
        if (mysqli_num_rows($result) > 0)
        {
            $row = mysqli_fetch_object($result);
            if (password_verify($password, $row->password))
            {
                if ($row->is_tfa_enabled)
                {
                    $row->is_verified = false;
                    $_SESSION["user"] = $row;
 
                    $pin = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
                     
                    $sql = "UPDATE usuarios SET pin = '$pin'  WHERE id = '" . $row->id . "'";
                    mysqli_query($conn, $sql);
 
                    $client = new Client($sid, $token);
                    $client->messages->create(
                        $row->phone, array(
                            "from" => "",
                            "body" => "Your 2-factor authentication code is: ". $pin
                        )
                    );
 
                    header("Location: enter-pin.php");
                }
                else
                {
                    $row->is_verified = true;
                    $_SESSION["user"] = $row;
 
                    header("Location: index.php");
                }
            }
            else
            {
                echo "Wrong password";
            }
        }
        else
        {
            echo "Not exists";
        }
    }
 
?>

<form method="POST" action="login.php">
     
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Password">
     
    <input type="submit" name="login">
</form>