<?php
    session_start();
    include "database.php";

    $_SESSION["username_is_taken"] = false;
    $_SESSION["login-correct"] = true;
    $_SESSION["game-name_is_taken"] = false;
    $_SESSION["image_is_taken"] = false;
    $_SESSION["game-file_is_taken"] = false;
?>
<?php
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./src/style.css">
    <title>Main page</title>
</head>
<body>
    <div class="container">
        <div class="log-in">
            <?php
                if(!$_SESSION["logStatus"])
                {
                    echo"
                    <a href=\"login.php\"><button>Log in</button></a>
                    <a href=\"signup.php\"><button>Sign up</button></a>
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
            <div class="games-list__game-container__flex-filler"></div>
            <div class="info-bar__search">
                <form action="index.php" method="POST" id="search-form" class="info-bar__search__form">
                    <input type="text" id="search-text" class="search-form__input" name="search-form__input" placeholder="Search">
                    <input type="image" class="search-form__button" name="search-form__button" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCI+CjxwYXRoIGQ9Ik0gNyA0IEMgNS4zNTQ1NDU1IDQgNCA1LjM1NDU0NTUgNCA3IEwgNCA0MyBDIDQgNDQuNjQ1NDU1IDUuMzU0NTQ1NSA0NiA3IDQ2IEwgNDMgNDYgQyA0NC42NDU0NTUgNDYgNDYgNDQuNjQ1NDU1IDQ2IDQzIEwgNDYgNyBDIDQ2IDUuMzU0NTQ1NSA0NC42NDU0NTUgNCA0MyA0IEwgNyA0IHogTSA3IDYgTCA0MyA2IEMgNDMuNTU0NTQ1IDYgNDQgNi40NDU0NTQ1IDQ0IDcgTCA0NCA0MyBDIDQ0IDQzLjU1NDU0NSA0My41NTQ1NDUgNDQgNDMgNDQgTCA3IDQ0IEMgNi40NDU0NTQ1IDQ0IDYgNDMuNTU0NTQ1IDYgNDMgTCA2IDcgQyA2IDYuNDQ1NDU0NSA2LjQ0NTQ1NDUgNiA3IDYgeiBNIDIyLjUgMTMgQyAxNy4yNjUxNCAxMyAxMyAxNy4yNjUxNCAxMyAyMi41IEMgMTMgMjcuNzM0ODYgMTcuMjY1MTQgMzIgMjIuNSAzMiBDIDI0Ljc1ODIxOSAzMiAyNi44MzIwNzYgMzEuMjAxNzYxIDI4LjQ2NDg0NCAyOS44Nzg5MDYgTCAzNi4yOTI5NjkgMzcuNzA3MDMxIEwgMzcuNzA3MDMxIDM2LjI5Mjk2OSBMIDI5Ljg3ODkwNiAyOC40NjQ4NDQgQyAzMS4yMDE3NjEgMjYuODMyMDc2IDMyIDI0Ljc1ODIxOSAzMiAyMi41IEMgMzIgMTcuMjY1MTQgMjcuNzM0ODYgMTMgMjIuNSAxMyB6IE0gMjIuNSAxNSBDIDI2LjY1Mzk4IDE1IDMwIDE4LjM0NjAyIDMwIDIyLjUgQyAzMCAyNi42NTM5OCAyNi42NTM5OCAzMCAyMi41IDMwIEMgMTguMzQ2MDIgMzAgMTUgMjYuNjUzOTggMTUgMjIuNSBDIDE1IDE4LjM0NjAyIDE4LjM0NjAyIDE1IDIyLjUgMTUgeiI+PC9wYXRoPgo8L3N2Zz4=">
                </form>
            </div>
        </div>
        <div class="info-container">
            <div class="games-selection">
                <form method="POST" action="index.php">
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
                    <input type="submit" value="Search" name="search-games" id="games-selection__search" class="games-selection__button"><br>
                </form>
            </div>
            <div class="games-list">
                <?php
                    //All uploaded games.
                    //$query = "SELECT * FROM games";
                    $action_checkbox = isset($_POST["action-checkbox"]);
                    $puzzle_checkbox = isset($_POST["puzzle-checkbox"]);
                    $adventure_checkbox = isset($_POST["adventure-checkbox"]);
                    $shooting_checkbox = isset($_POST["shooting-checkbox"]);
                    $role_playing_checkbox = isset($_POST["role-playingn-checkbox"]);
                    $strategy_checkbox = isset($_POST["strategy-checkbox"]);
                    $racing_checkbox = isset($_POST["racing-checkbox"]);
                    $sports_checkbox = isset($_POST["sports-checkbox"]);
                    $fighting_checkbox = isset($_POST["fighting-checkbox"]);
                    $simulation_checkbox = isset($_POST["simulation-checkbox"]);
                    $rhythm_checkbox = isset($_POST["rhythm-checkbox"]);
                    $party_checkbox = isset($_POST["party-checkbox"]);
                    $other_checkbox = isset($_POST["other-checkbox"]);
                    $empty_checkboxes = true;

                    $game_name = "";

                    //Genres array
                    $genres = array("Action", "Puzzle", "Adventure", "Shooting", "Role-playing", "Strategy", 
                    "Racing", "Sports", "Fighting", "Simulation", "Rhythm", "Party", "Other");
                    $genres_names_db = array("action", "puzzle", "adventure", "shooting", "role_playing", "strategy", 
                            "racing", "sports", "fighting", "simulation", "rhythm", "party", "other");
                    $selected_genres = array();

                    if($action_checkbox){
                        array_push($selected_genres, "action");
                        $empty_checkboxes = false;
                    }if($puzzle_checkbox){
                        array_push($selected_genres, "puzzle");
                        $empty_checkboxes = false;
                    }if($adventure_checkbox){
                        array_push($selected_genres, "adventure");
                        $empty_checkboxes = false;
                    }if($shooting_checkbox){
                        array_push($selected_genres, "shooting");
                        $empty_checkboxes = false;
                    }if($role_playing_checkbox){
                        array_push($selected_genres, "role_playing");
                        $empty_checkboxes = false;
                    }if($strategy_checkbox){
                        array_push($selected_genres, "strategy");
                        $empty_checkboxes = false;
                    }if($racing_checkbox){
                        array_push($selected_genres, "racing");
                        $empty_checkboxes = false;
                    }if($sports_checkbox){
                        array_push($selected_genres, "sports");
                        $empty_checkboxes = false;
                    }if($fighting_checkbox){
                        array_push($selected_genres, "fighting");
                        $empty_checkboxes = false;
                    }if($simulation_checkbox){
                        array_push($selected_genres, "simulation");
                        $empty_checkboxes = false;
                    }if($rhythm_checkbox){
                        array_push($selected_genres, "rhythm");
                        $empty_checkboxes = false;
                    }if($party_checkbox){
                        array_push($selected_genres, "party");
                        $empty_checkboxes = false;
                    }if($other_checkbox){
                        array_push($selected_genres, "other");
                        $empty_checkboxes = false;
                    }
                    
                    //Creates sql query condition for game search.
                    $query_condition = "(";
                    for($i = 0; $i < count($selected_genres); $i++)
                    {
                        if($i !== count($selected_genres) - 1)
                        {
                            $query_condition = $query_condition." $selected_genres[$i] LIKE 1 OR";
                        }
                        else
                        {
                            $query_condition = $query_condition." $selected_genres[$i] LIKE 1)";
                        }
                    }
                    $query = "SELECT * FROM games WHERE".$query_condition;
                    
                    if($empty_checkboxes)
                    {
                        $query = "SELECT * FROM games";
                    }
                    
                    if(isset($_POST["search-form__input"]))
                    {
                        $game_name = filter_input(INPUT_POST, "search-form__input", FILTER_SANITIZE_SPECIAL_CHARS);
                        $query = $query." WHERE name LIKE \"$game_name\"";
                    }
                    //Checks which one of main, popular, newest pages is selected.
                    if(isset($_POST["main_page"]))
                    {
                        $query = $query." ORDER BY release_date ASC";
                    }
                    else if(isset($_POST["popular_page"]))
                    {
                        $query = $query." ORDER BY download_count DESC";
                    }
                    else if(isset($_POST["newest_page"]))
                    {
                        $query = $query." ORDER BY release_date DESC";
                    }
                    
                    $result = mysqli_query($conn, $query);
                    $rows = array();
                    $i = 0;
                    while($row = $result->fetch_assoc())
                    {
                        $rows[$i] = $row;
                        $i++;
                    }
                    $i=0;
                    if(count($rows) == 0)
                    {
                        echo'
                            <div class="games-list__empty">
                                <h1 class="games-list__empty__header">No games found, change selected genres.</h1>
                            </div>  
                        ';
                    }
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
                                    <p class="games-list__game-container__text__release-date">Release date: '.$rows[$i]["release_date"].'</p>
                                </div>
                                <div class="games-list__game-container__flex-filler"></div>
                                <div class="games-list__game-container__download">
                                    <p class="games-list__game-container__download__count">Downloads:'.$rows[$i]["download_count"].'</p>
                                    <form action="download.php" method="POST">
                                        <input type="hidden" name="download-id" value="'.$rows[$i]["id"].'">
                                        <input type="hidden" name="download-name" value="'.$rows[$i]["name"].'">
                                        <input type="hidden" name="download-path" value="'.$rows[$i]["game_path"].'">
                                        <input type="submit" class="games-list__game-container__download__button" value="Download" name="download-game'.$i.'">
                                    </form>
                                </div>
                            </div>
                        </div>';
                    }
                    mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>  

    <script src="./src/script.js"></script>
</body>
</html>