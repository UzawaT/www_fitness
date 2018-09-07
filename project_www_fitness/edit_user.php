<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit User Info</title>
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

        //include external function file
        include "includes/functions.php";

        //retrieve user information
        $query = "select first_name, middle_initial, last_name, email, phone, user_name
            from gym_user where user_id = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1)
        {
            $userInfo = $result->fetch_object();
        }
        else
        {
            echo "user not found.";
            exit();
        }
    
        //edit user information
        if ($_SERVER["REQUEST_METHOD"] === "POST")
        {
            $fname = formatInput($_POST["fname"]);
            $mid = formatInput($_POST["mid"]);
            $lname = formatInput($_POST["lname"]);
            $email = formatInput($_POST["email"]);
            $phone = formatInput($_POST["phone"]);
            $user = formatInput($_POST["user"]);

            //check to see if the required fields are filled out
            if (empty($fname)||empty($lname)||empty($email)||empty($user))
            {
                echo "There is required field not entered.";
                return;
            }

            //update information in database
            $query_user = "update gym_user
                set first_name = ?, middle_initial = ?, last_name = ?, 
                email = ?, phone = ?, user_name = ?
                where user_id = ?";
            $stmt = $conn->prepare($query_user);
            $stmt->bind_param("ssssssi", $fname, $mid, $lname, $email, $phone, $user, $id);
            $result_user = $stmt->execute();
            
            if($result_user)
            {
                $_SESSION["first_name"] = $fname;
                header("Location: prod_list.php");
            }
            else
            {
                echo "Error with updating user information."; 
            }
            
            $stmt->close();
            $conn->close();
        }
    ?>
    <?php include "includes/header.php"; ?>
    <div id="main">
    <h2>Edit User Information</h2>
        <form method="post">
            <label for="fname">First Name:</label>
            <input type="text" name="fname" id="fname" value=<?php echo $userInfo->first_name;?> required>
            <br>
            <label for="mid">Middle Initial:</label>
            <input type="text" name="mid" id="mid" maxlength="1" value=<?php echo $userInfo->middle_initial;?>>
            <br>
            <label for="lname">Last Name:</label>
            <input type="text" name="lname" id="lname" value=<?php echo $userInfo->last_name;?> required>
            <br>
            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" value=<?php echo $userInfo->email;?> required>
            <br>
            <label for="phone">Phone No:</label>
            <input type="tel" name="phone" id="phone" placeholder="123-456-7890" 
                pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value=<?php echo $userInfo->phone;?>>
            <br>
            <label for="user">User Name:</label>
            <input type="text" name="user" id="user" value=<?php echo $userInfo->user_name;?> required>
            <br>
            <button type="submit">Save</button>
        </form>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>