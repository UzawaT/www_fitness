<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Change Password</title>
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

        //connect to the database
        include "includes/connect_gymstore_db.php";

        //include external file
        include "includes/functions.php";

        if ($_SERVER["REQUEST_METHOD"] === "POST")
        {
            $current_pass = formatInput($_POST["current_pass"]);
            $new_pass = formatInput($_POST["new_pass"]);
            $confirm = formatInput($_POST["confirm"]);

            //check for empty fields
            if (empty($current_pass)||empty($new_pass)||empty($confirm))
            {
                echo "There is(are) field(s) with missing information.";
                return;
            }

            //check if the password match
            if ($new_pass != $confirm)
            {
                echo "Password doesn't match.";
                return;
            }

            //retrieve user information from the database
            $query = "select user_pass from gym_user where user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result || $result->num_rows != 1)
            {
                echo "user not found";
                return;
            }

            $userInfo = $result->fetch_object();

            //check if the current password matches
            if (password_verify($current_pass, $userInfo->user_pass))
            {
                //encrypt the new password
                $hash = password_hash($new_pass, PASSWORD_DEFAULT);
                $hash = $conn->real_escape_string($hash);

                //update password
                $query = "update gym_user
                set user_pass = ?
                where user_id = ?";

                $stmt = $conn->prepare($query);
                $stmt->bind_param("si", $hash, $id);

                if ($stmt->execute())
                {
                    header("Location: prod_list.php");
                }
                else
                {
                    echo "Error updating password"; 
                }
            }
            else
            {
                echo "Password doesn't match.";
                echo "<a href='prod_list.php'>Return</a>";
                return;   
            }
        }
    ?>
    <?php include "includes/header.php"; ?>
    <div id="main">
    <h2>Change Password</h2>
        <form method="post">
            <label for="password">Current Password:</label>
            <input type="password" name="current_pass" id="current_pass" required>
            <br>
            <label for="password">New Password:</label>
            <input type="password" name="new_pass" id="new_pass" required>
            <br>
            <label for="confirm">Confirm New Password:</label>
            <input type="password" name="confirm" id="confirm" required>
            <br>
            <button type="submit">Save</button>
        </form>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>