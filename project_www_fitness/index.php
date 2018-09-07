<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>
<body>
    <?php
        //connect to the database
        include "includes/connect_gymstore_db.php";

        //include external file
        include "includes/functions.php";
        
        //start the session
        session_start();

        if ($_SERVER["REQUEST_METHOD"] === "POST")
        {
            $user = formatInput($_POST["user"]);
            $pass = formatInput($_POST["password"]);

            //check for empty fields
            if (empty($user)||empty($pass))
            {
                echo "There is(are) field(s) with missing information.";
                echo "<br>";
                echo "<a href='index.php'>Return</a>";
                return;
            }

            //retrieve user information from the database
            $query = "select * from gym_user where user_name = '$user'";
            $result = $conn->query($query);

            if (!$result || $result->num_rows != 1)
            {
                echo "user not found";
                echo "<br>";
                echo "<a href='index.php'>Return</a>";
                return;
            }

            $userInfo = $result->fetch_object();

            //check if the user information match with the entry
            if (password_verify($pass, $userInfo->user_pass))
            {
                $_SESSION["user_id"] = $userInfo->user_id;
                $_SESSION["first_name"] = $userInfo->first_name;
                $_SESSION["role_id"] = $userInfo->role_id;

                header("Location: prod_list.php");
            }
            else
            {
                echo "Password doesn't match.";
                echo "<br>";
                echo "<a href='index.php'>Return</a>";
                return;   
            }
        }
    ?>
    <div id="header">
        <h1>World Wide Workout Fitness Online Store</h1>
    </div>
    <div id="main">
        <h1>Log in</h1>
        <p><h3><a href="register.php">Register</a></h3></p>
        <form method="post">
            <label for="user">User Name:</label>
            <input type="text" name="user" id="user" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <button type="submit">Log in</button>
        </form>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>