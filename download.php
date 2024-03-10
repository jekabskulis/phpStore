<?php
    include "database.php";
?>

<?php
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $file_id = $_POST["download-id"];
        $file_dir = $_POST["download-path"];
        $file_name = $_POST["download-name"];
        if(!file_exists($file_dir))
        {
            die('File not found');
        } 
        else 
        {
            //Adds +1 to download count, user doesn't have to see it automatically
            //increasing because this information is more important to
            //other users.
            $query = "SELECT * FROM games WHERE id = ".$file_id;
            $result = mysqli_query($conn, $query);
            $row = $result->fetch_assoc();
            $downloads = $row["download_count"] + 1;
            $sql = "UPDATE games SET download_count = $downloads
                    WHERE id = $file_id";
            mysqli_query($conn, $sql);
            mysqli_close($conn);

            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename = $file_name");
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: binary");
        
            readfile($file_dir);
        }
    }
?>