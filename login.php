<?php
    session_start();

    include "database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link href="./src/logSignStyle.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="log-in">
            <p class="log-in__text">Log In</p>
            <form action="login.php" class="log-in__form" method="POST">
                <label for="name">Account name:  <input type="text" id="username" name="username"  required></label><br>
                <label for="password">Password: <input type="password" id="password" name="password"  required></label><br>
                <input type="submit" id="submit" value="Log In"><br>
            </form>
            <?php
                if(isset($_SESSION["login-correct"]) && !($_SESSION["login-correct"]))
                {
                    echo'
                    <div class="log-in_wrong-password">
                        Incorrect username or password
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
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        //HTML required can be overriden by the user.
        if(!(empty($username) && empty($passowrd)))
        {
            $query = "SELECT * FROM users WHERE username LIKE \"$username\"";
            $result = mysqli_query($conn, $query);
            $row = $result->fetch_assoc();
            if((!empty($row) && count($row) > 0))
            {
                if(password_verify($password, $row["password"]))
                {
                    $_SESSION["username"] = $username;
                    $_SESSION["logStatus"] = true;
                    $_SESSION["login-correct"] = true;
                    mysqli_close($conn);
                    header("Location: index.php");
                }
                else
                {
                    $_SESSION["login-correct"] = false;
                }
            }
            else
            {
                $_SESSION["login-correct"] = false;
            }
            mysqli_close($conn);
            header("Location: login.php");
        }
    }
?>
