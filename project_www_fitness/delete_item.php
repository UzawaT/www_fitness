<?php
    include "includes/connect_gymstore_db.php";
    $id = $_GET["id"];

    $qry_delete = "delete from cart_item where item_id = $id";
    $result = $conn->query($qry_delete);

    if($result)
    {
        header("Location: cart.php");
    }
?>