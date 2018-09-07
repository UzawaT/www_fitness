<?php
    include "connect_gymstore_db.php";
    $id = $_GET["id"];
    $query = "select image_type, image_data from user_image where user_id = $id";
    $result = $conn->query($query);
    if ($result)
    {
        $image = $result->fetch_object();
        header("Content-type: ".$image->image_type);
        echo $image->image_data;
    }
    else
    {
        echo $conn->error;
        echo "<br>";
        echo "<a href='prod_list.php'>Return</a>";
    }
?>