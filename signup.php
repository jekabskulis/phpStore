<?php
    session_start();

    include "database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link href="./src/logSignStyle.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="log-in">
            <p class="log-in__text">Sign In</p>
            <form action="signup.php" class="log-in__form" method="POST">
                <label for="email">Email: <input type="email" id="email" name="email" required></label><br>
                <label for="username">Account name:  <input type="text" id="username" name="username"  required></label><br>
                <label for="password">Password: <input type="password" id="password" name="password"  required></label><br>
                <label for="confirm-password">Confirm password: <input type="password" id="confirm-password" name="confirm-password"  required></label><br>
                <input type="submit" id="submit" value="Sign In"><br>
            </form>
        <?php
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
            $confirm_password = filter_input(INPUT_POST, "confirm-password", FILTER_SANITIZE_SPECIAL_CHARS);
            if($password !== $confirm_password)
            {
                echo'
                <div class="log-in_wrong-password">
                    Passwords does not match
                </div>';
            }
            if(isset($_SESSION["username_is_taken"]) && $_SESSION["username_is_taken"])
            {
                echo'
                <div class="log-in_wrong-password">
                    Username is already taken
                </div>';
            }
        ?>
        </div>
    </div>
</body>
</html>

<?php

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
        $confirm_password = filter_input(INPUT_POST, "confirm-password", FILTER_SANITIZE_SPECIAL_CHARS);

        //HTML required can be overriden by the user.
        if(!(empty($username) && empty($email) && empty($password) && empty($confirm_password)))
        {
            if($password == $confirm_password)
            {
                $hash = password_hash($password, PASSWORD_BCRYPT,['cost' => 12]);
                $sql = "INSERT INTO users (username, password, email)
                VALUES('$username', '$hash', '$email')";

                $query = "SELECT * FROM users WHERE username LIKE \"$username\"";
                $result = mysqli_query($conn, $query);
                $row = $result->fetch_assoc();
                //Checks if the username is already taken.
                if((!empty($row) && count($row) > 0))
                {
                    $_SESSION["username_is_taken"] = true;

                    header("Location: signup.php");
                }
                else
                {
                    mysqli_query($conn, $sql);
                    $_SESSION["username"] = $username;
                    $_SESSION["logStatus"] = true;
                    $_SESSION["username_is_taken"] = false;
    
                    mysqli_close($conn);
                    header("Location: index.php");
                }
                mysqli_close($conn);
            }
        }
    }

?>
