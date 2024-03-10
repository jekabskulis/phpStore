<?php
    session_start();
    include "database.php";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $id = (int) $_POST["delete-id"];

        $sql = "DELETE FROM games WHERE id = ".$id;
        $query = "SELECT * FROM games WHERE id = ".$id;
        $result = mysqli_query($conn, $query);
        $row = $result->fetch_assoc();
        $image_path = $row["image_path"];
        $game_path = $row["game_path"];

        if (mysqli_query($conn, $sql) === TRUE) 
        {
            mysqli_close($conn);
            if(is_file($image_path) && is_file($game_path))
            {
                if(unlink($image_path) && unlink($game_path))
                {
                    //File is deleted, reload the page.
                    header("Location: userGames.php");
                }
            }
            else
            {
                echo"Unable to found files";
            }
         } 
         else 
         {
             echo"Unable to delete files: ".mysqli_error($conn);
         }
         
         mysqli_close($conn);
    }
?>