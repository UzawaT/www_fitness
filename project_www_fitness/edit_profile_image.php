<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Change Profile Picture</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>
<body>
    <?php
        //check if the user has logged in
        session_start();

        if (!isset($_GET["id"])||!isset($_SESSION["user_id"]))
        {
            header("Location: index.php");
            exit();
        }

        $id = $_GET["id"];
        
        //connect to database
        include "includes/connect_gymstore_db.php";

        if ($_SERVER["REQUEST_METHOD"] === "POST")
        {   
            if (!empty($_FILES["image"]["tmp_name"]))
            {
                $fileinfo = finfo_open(FILEINFO_MIME_TYPE);

                if (finfo_file($fileinfo, $_FILES["image"]["tmp_name"]) == "image/jpeg")
                {
                    $image_data = $conn->escape_string(file_get_contents($_FILES["image"]["tmp_name"]));
                    $image_prop = getimagesize($_FILES["image"]["tmp_name"]);
                    $image_type = $image_prop["mime"];
                    $image_name = $_FILES["image"]["name"];

                    $check_qry = "select image_id from user_image where user_id = $id";
                    $result_chk = $conn->query($check_qry);

                    if($result_chk->num_rows == 0)
                    {
                        $query_img = "insert into user_image(filename, image_type, image_data, user_id)
                        values('$image_name','$image_type', '$image_data', '$id')";
                    }
                    else
                    {
                        $query_img = "update user_image
                        set filename = '$image_name', image_type = '$image_type', image_data = '$image_data'
                        where user_id = $id";
                    }

                    $result_img = $conn->query($query_img);

                    if($result_img)
                    {
                        header("Location: prod_list.php");
                    }
                    else
                    {
                        echo "Error: $conn->error";
                    }
                }
                else
                {
                    echo "Invalid file type.";
                    echo "<br>";
                    echo "<a href='edit_profile_image.php?id=".$id."'>Return</a>";
                }
            }
            else
            {
                echo "No file selected.";
                echo "<br>";
                echo "<a href='edit_profile_image.php?id=".$id."'>Return</a>";
            }
        }
    ?>
    <?php include "includes/header.php"; ?>
    <div id="main">
        <h2>Change Profile Picture</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="image">Upload an image:</label>
            <input type="file" name="image" id="image">
            <br>
            <button type="submit">Save</button>
        </form>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>