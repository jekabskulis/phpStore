<?php
    session_start();
    include "database.php";

    $_SESSION["username_is_taken"] = false;
    $_SESSION["login-correct"] = true;
    
    if(!(empty($_SESSION["username"])))
    {
        $_SESSION["logStatus"] = true;
    }
    else
    {
        $_SESSION["logStatus"] = false;
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["log-out"]))
        {
            session_destroy();
    
            header("Location: index.php");
        }
    }
?>  

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(isset($_POST["upload"]))
        {
            $gameName = filter_input(INPUT_POST, "gameName", FILTER_SANITIZE_SPECIAL_CHARS);
            $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS);
            $image = filter_input(INPUT_POST, "image", FILTER_SANITIZE_SPECIAL_CHARS);
            $game_file = filter_input(INPUT_POST, "game_file", FILTER_SANITIZE_SPECIAL_CHARS);

            $action_checkbox = !(empty(filter_input(INPUT_POST, "action-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $puzzle_checkbox = !(empty(filter_input(INPUT_POST, "puzzle-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $adventure_checkbox = !(empty(filter_input(INPUT_POST, "adventure-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $shooting_checkbox = !(empty(filter_input(INPUT_POST, "shooting-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $role_playing_checkbox = !(empty(filter_input(INPUT_POST, "role-playing-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $strategy_checkbox = !(empty(filter_input(INPUT_POST, "strategy-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $racing_checkbox = !(empty(filter_input(INPUT_POST, "racing-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $sports_checkbox = !(empty(filter_input(INPUT_POST, "sports-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $fighting_checkbox = !(empty(filter_input(INPUT_POST, "fighting-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $simulation_checkbox = !(empty(filter_input(INPUT_POST, "simulation-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $rhythm_checkbox = !(empty(filter_input(INPUT_POST, "rhythm-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $party_checkbox = !(empty(filter_input(INPUT_POST, "party-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));
            $other_checkbox = !(empty(filter_input(INPUT_POST, "other-checkbox", FILTER_SANITIZE_SPECIAL_CHARS)));

            if(!(empty($name)  && empty($description)  && empty($price)  && empty($game_file)  && empty($image)) && 
            ((!empty($action_checkbox) || !empty($puzzle_checkbox) || !empty($adventure_checkbox) || !empty($racing_checkbox) || !empty($shooting_checkbox) || 
            !empty($role_playing_checkbox) || !empty($strategy_checkbox) || !empty($sports_checkbox) || !empty($fighting_checkbox) || 
            !empty($simulation_checkbox) || !empty($rhythm_checkbox) || !empty($party_checkbox) || !empty($other_checkbox))))
            {
                $target_file_images = './uploads/images/'. basename($_FILES["image"]["name"]);
                $target_file_game = './uploads/games/'. basename($_FILES["game_file"]["name"]);
                
                $allowed_image_extensions = array("image/png", "image/jpeg", "image/x-ms-bmp", "image/x-png", "image/x-jpeg");
                $allowed_game_extensions = array("application/zip", "application/vnd.rar", "application/x-zip", "application/x-rar");

                $query = "SELECT * FROM games WHERE name LIKE '$gameName'";
                $result = mysqli_query($conn, $query);
                $row = $result->fetch_assoc();
                //Checks if the game name, image and game file paths are already taken.
                if((!empty($row) && count($row) > 0))
                {
                    $_SESSION["game-name_is_taken"] = true;
                }
                else
                {
                    $_SESSION["game-name_is_taken"] = false;
                }
                $query = "SELECT * FROM games WHERE image_path LIKE '$target_file_images'";
                $result = mysqli_query($conn, $query);
                $row = $result->fetch_assoc();
                if((!empty($row) && count($row) > 0))
                {
                    $_SESSION["image_is_taken"] = true;
                }
                else
                {
                    $_SESSION["image_is_taken"] = false;
                }
                $query = "SELECT * FROM games WHERE game_path LIKE '$target_file_game'";
                $result = mysqli_query($conn, $query);
                $row = $result->fetch_assoc();
                if((!empty($row) && count($row) > 0))
                {
                    $_SESSION["game-file_is_taken"] = true;
                }
                else
                {
                    $_SESSION["game-file_is_taken"] = false;
                }

                if(!($_SESSION["game-name_is_taken"] || $_SESSION["image_is_taken"] || $_SESSION["game-file_is_taken"]))
                {   
                    

                    if(is_uploaded_file($_FILES["image"]["tmp_name"]) && is_uploaded_file($_FILES["game_file"]["tmp_name"]))
                    {
                        $image_mime_type = mime_content_type($_FILES["image"]["tmp_name"]);
                        $game_mime_type = mime_content_type($_FILES["game_file"]["tmp_name"]);

                        if(in_array($image_mime_type, $allowed_image_extensions) && in_array($game_mime_type, $allowed_game_extensions))
                        {
                            //File upload
                            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file_images);
                            move_uploaded_file($_FILES["game_file"]["tmp_name"], $target_file_game);

                            //Gets uploader_id
                            $username = $_SESSION["username"];
                            $query = "SELECT * FROM users WHERE username LIKE \"$username\"";
                            $result = mysqli_query($conn, $query);
                            $row = $result->fetch_assoc();
                            $uploader_id = $row["id"];

                            $sql = "INSERT INTO games (uploader_id, name, description, image_path, game_path, action, puzzle, adventure, 
                                                    shooting, role_playing, strategy, racing, sports, fighting, simulation, rhythm, party, other)
                                    VALUES('$uploader_id', '$gameName', '$description', '$target_file_images', '$target_file_game',
                                            '$action_checkbox', '$puzzle_checkbox', '$adventure_checkbox', '$shooting_checkbox', '$role_playing_checkbox', 
                                            '$strategy_checkbox', '$racing_checkbox', '$sports_checkbox', '$fighting_checkbox', '$simulation_checkbox', '$rhythm_checkbox', 
                                            '$party_checkbox', '$other_checkbox')";
                            mysqli_query($conn, $sql);
                        }
                    }
                }
                header("Location: userGames.php");
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <link rel="stylesheet" href="./src/style.css">
    <link rel="stylesheet" href="./src/userGames.css">
</head>
<body>
    <div class="container">
        <div class="log-in">
            <?php
                if(!$_SESSION["logStatus"])
                {
                    echo"
                    <a href=\"login.php\"><button>Log in</button></a>
                    <a href=\"signin.php\"><button>Sign in</button></a>
                    ";
                }
                else
                {
                    echo"   
                    <form action=\"index.php\" method=\"POST\">
                        <input type=\"submit\" value=\"Log out\" name=\"log-out\">
                    </form>
                    ";
                }
            ?>
        </div>
        <div class="info-bar">
            <form action="index.php" method="POST">
                <input type="submit" name="main_page" value="Main Page">
            </form>
            <form action="index.php" method="POST">
                <input type="submit" name="popular_page" value="Popular">
            </form>
            <form action="index.php" method="POST">
                <input type="submit" name="newest_page" value="Newest">
            </form>
            <?php
                if($_SESSION["logStatus"])
                {
                    echo'
                        <form action="userGames.php" method="POST">
                            <input type="submit" name="user_game_page" value="Add/remove games">
                        </form>
                        ';
                }
            ?>
        </div>
        <div class="game-add">
            <h1 class="game-add__header">Add a new game</h1>
            <form action="userGames.php" class="game-add__form" method="POST"  enctype="multipart/form-data">
                <div class="game-add__form__text">
                    <label for="gameName">Game name: <input type="gameName" name="gameName" id="gameName" required></label><br>
                    <label for="description">Description: </label><br>
                    <textarea name="description" id="description" cols="30" rows="10" required></textarea><br>
                    <label for="image">Image: <input type="file" name="image" id="image" required></label><br>
                    <label for="game_file">Upload file: <input type="file" name="game_file" id="game_file" required></label><br>
                    <input type="submit" name="upload" value="Upload my game"><br>
                    <?php
                        if(isset($_SESSION["game-name_is_taken"]) && $_SESSION["game-name_is_taken"])
                        {
                            echo'
                            <div class="log-in_wrong-password">
                                Game name is already taken.
                            </div>';
                        }
                        if(isset($_SESSION["image_is_taken"]) && $_SESSION["image_is_taken"])
                        {
                            echo'
                            <div class="log-in_wrong-password">
                                Image path is already used, change file name.
                            </div>';
                        }
                        if(isset($_SESSION["game-file_is_taken"]) && $_SESSION["game-file_is_taken"])
                        {
                            echo'
                            <div class="log-in_wrong-password">
                                Game path is already used, change file name.
                            </div>';
                        }
                    ?>
                </div>
                <div class="games-add__form__checkboxes">
                    <label for="action-checkbox">Action: <input type="checkbox" id="action-checkbox" name="action-checkbox"></label><br>
                    <label for="puzzle-checkbox">Puzzle:<input type="checkbox" id="puzzle-checkbox" name="puzzle-checkbox"></label><br>
                    <label for="adventure-checkbox">Adventure: <input type="checkbox" id="adventure-checkbox" name="adventure-checkbox"></label><br>
                    <label for="shooting-checkbox">Shooting: <input type="checkbox" id="shooting-checkbox" name="shooting-checkbox"></label><br>
                    <label for="role-playing-checkbox">Role-playing: <input type="checkbox" id="role-playing-checkbox" name="role-playing-checkbox"></label><br>
                    <label for="strategy-checkbox">Strategy: <input type="checkbox" id="strategy-checkbox" name="strategy-checkbox"></label><br>
                    <label for="racing-checkbox">Racing: <input type="checkbox" id="racing-checkbox" name="racing-checkbox"></label><br>
                    <label for="sports-checkbox">Sports: <input type="checkbox" id="sports-checkbox" name="sports-checkbox"></label><br>
                    <label for="fighting-checkbox">Fighting: <input type="checkbox" id="fighting-checkbox" name="fighting-checkbox"></label><br>
                    <label for="simulation-checkbox">Simulation: <input type="checkbox" id="simulation-checkbox" name="simulation-checkbox"></label><br>
                    <label for="rhythm-checkbox">Rhythm: <input type="checkbox" id="rhythm-checkbox" name="rhythm-checkbox"></label><br>
                    <label for="party-checkbox">Party: <input type="checkbox" id="party-checkbox" name="party-checkbox"></label><br>
                    <label for="other-checkbox">Other: <input type="checkbox" id="other-checkbox" name="other-checkbox"></label><br>
                </div>
            </form>
        </div>
        <div class="game-remove">
            <h1 class="game-remove__header">Remove a game</h1>
            <?php
                //Gets uploader_id
                $username = $_SESSION["username"];
                $query = "SELECT * FROM `users` WHERE `username` LIKE \"%$username%\"";
                $result = mysqli_query($conn, $query);
                $row = $result->fetch_assoc();
                $uploader_id = $row["id"];
                //Gets all games uploaded by the current user.
                $query = "SELECT * FROM `games` WHERE `uploader_id` LIKE \"%$uploader_id%\"";
                $result = mysqli_query($conn, $query);
                $rows = array();
                $i = 0;
                while($row = $result->fetch_assoc())
                {
                    $rows[$i] = $row;
                    $i++;
                }
                $i=0;
                //Genres array
                $genres = array("Action", "Puzzle", "Adventure", "Shooting", "Role-playing", "Strategy", 
                                "Racing", "Sports", "Fighting", "Simulation", "Rhythm", "Party", "Other");
                $genres_names_db = array("action", "puzzle", "adventure", "shooting", "role_playing", "strategy", 
                                        "racing", "sports", "fighting", "simulation", "rhythm", "party", "other");
                for($i = 0; $i < count($rows); $i++)
                {
                    $game_ids[$i] = $rows[$i]["id"];
                    echo'
                    <div class="games-list">
                        <div class="games-list__game-container">
                            <div class="games-list__game-container__imgage">
                                <img src="'.$rows[$i]["image_path"].'" alt="Picture for example view">
                            </div>
                            <div class="games-list__game-container__text">
                                <p class="games-list__game-container__text__title">'.$rows[$i]["name"].'</p>
                                <p class="games-list__game-container__text__short-description">'.$rows[$i]["description"].'</p>
                                <p class="games-list__game-container__text__game-genre">';
                                for($j = 0; $j < count($genres); $j++)
                                {
                                    if($rows[$i][$genres_names_db[$j]] == 1)
                                    {
                                        echo $genres[$j]." ";
                                    }
                                }
                                ;
                                echo'</p>
                                <p class="games-list__game-container__text__release-date">'.$rows[$i]["release_date"].'</p>
                            </div>
                            <div class="games-list__game-container__flex-filler"></div>
                            <div class="games-list__game-container__download">
                                <p class="games-list__game-container__download__count">Downloads: '.$rows[$i]["download_count"].'</p>
                                <form action="deleteGame.php" method="POST">
                                    <input type="hidden" name="delete-id" value="'.$rows[$i]["id"].'">
                                    <input type="submit" class="games-list__game-container__download__button" value="Delete" name="delete-game'.$i.'">
                                </form>
                            </div>
                        </div>
                    </div>';
                    
                }
                mysqli_close($conn);
            ?>
        </div>
    </div>
</body>
</html>

