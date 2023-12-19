<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/home.css">
    <title>You're connecting</title>
</head>
<body>
    <?php 
        require_once('conn.php')
    ?>

    <div class="container">
        <div class="sidebar">
            <ul class="nav">
                <li><a href="home.php">Home</a></li>
                <li><a href="policy.php">Policy</a></li>
                <li><a href="#">Profile</a></li>
                <li><a href="friends.php">Friends</a></li>
                <li><a href="logout.php">log out</a></li>


            </ul>
        </div>
        <div class="content">
            <div class="tweet-box">
                <form method="POST">
                    <textarea name= "mail" placeholder="add friends by using their mail!"></textarea>
                    <button class="button_tweet" type="submit" name="btn_add_post">Add them</button>

           
                    <?php

                        session_start(); // Start the session (if not already started)

                        if (isset($_SESSION['user_id'])) {
                            $current_user_id = $_SESSION['user_id'];
                        
                            if (isset($_POST['btn_add_post'])) {
                                $mail = $_POST['mail'];
                        
                                // Check if the mail exists in the database
                                $query = "SELECT user_id FROM users WHERE mail = ?";
                                $stmt = mysqli_prepare($conn, $query);
                                mysqli_stmt_bind_param($stmt, "s", $mail);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                        
                                if ($row = mysqli_fetch_assoc($result)) {
                                    $friend_user_id = $row['user_id'];
                        
                                    // Insert a friendship into the "friends" table
                                    $insert_query = "INSERT INTO friends (user_id1, user_id2) VALUES (?, ?)";
                                    $stmt_insert = mysqli_prepare($conn, $insert_query);
                        
                                    // Bind the values to the placeholders
                                    mysqli_stmt_bind_param($stmt_insert, "ii", $current_user_id, $friend_user_id);
                        
                                    // Execute the prepared statement
                                    if (mysqli_stmt_execute($stmt_insert)) {
                                        echo "Friend added!";
                                    } else {
                                        echo "Failed to add friend.";
                                    }
                                } else {
                                    echo "Friend's user not found.";
                                }
                            }
                        } else {
                            echo "User not logged in";
                        }
                    ?>
                    </div>
                </form>
                <div class="posts">
                <ul>
                    <?php
                    $user_id = $_SESSION['user_id'];

                    // SQL query to retrieve and display posts of the friends from the logged user
                    $sql = "SELECT DISTINCT p.post_text, p.post_date, u.firstName
                            FROM posts p
                            JOIN friends f ON (p.user_id = f.user_id1 OR p.user_id = f.user_id2)
                            JOIN users u ON (p.user_id = u.user_id)
                            WHERE (f.user_id1 = $user_id OR f.user_id2 = $user_id) AND p.user_id <> $user_id
                            ORDER BY p.post_id DESC";

                    $result = mysqli_query($conn, $sql);


                    while ($row = mysqli_fetch_assoc($result)) {
                        $postText = $row['post_text'];
                        $postDate = $row['post_date'];
                        $firstName = $row['firstName'];

                        echo "<li>$firstName: $postText <small>Posted on $postDate</small></li>";
                    }
                    ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
