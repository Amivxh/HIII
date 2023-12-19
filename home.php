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
                    <textarea name= "post_text" placeholder="What's happening?"></textarea>
                    <button class="button_tweet" type="submit" name= "btn_add_post"> Post</button>
                    <?php 
                    session_start(); // Start or resume the session

                    require_once('conn.php');
                    if (isset($_POST['btn_add_post'])) {
                        $Post_Text = $_POST['post_text'];
                        $user_id = $_SESSION['user_id'];
                    
                        if ($Post_Text != "") {
                            $sql = "INSERT INTO posts (post_text, post_date, user_id) VALUES (?, NOW(), ?)";
                            $stmt = mysqli_prepare($conn, $sql);
                            
                            // Bind parameters to the placeholders
                            mysqli_stmt_bind_param($stmt, "si", $Post_Text, $user_id);
                            mysqli_stmt_execute($stmt);
                    
                            // Redirect to the same page after processing the form
                            header('Location: ' . $_SERVER['PHP_SELF']);
                            exit; // Terminate the script to ensure the redirect occurs
                        }
                    } ?>
                    </div>
                </form>
                <div class="posts">
                    <ul>
                        <?php
                        $user_id = $_SESSION['user_id'];

                        // SQL query to retrieve and display posts of the user and their friends
                        $sql = "SELECT DISTINCT p.post_text, p.post_date
                                FROM posts p
                                LEFT JOIN friends f ON (p.user_id = f.user_id1 OR p.user_id = f.user_id2)
                                WHERE p.user_id = ? OR (f.user_id1 IS NOT NULL AND (f.user_id1 = ? OR f.user_id2 = ?))
                                ORDER BY p.post_id DESC";
                        
                        $stmt = mysqli_prepare($conn, $sql);
                        
                        // Bind parameters to the placeholders
                        mysqli_stmt_bind_param($stmt, "iii", $user_id, $user_id, $user_id);
                        mysqli_stmt_execute($stmt);
                        
                        $result = mysqli_stmt_get_result($stmt);
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                            $postText = $row['post_text'];
                            $postDate = $row['post_date'];
                        
                            echo "<li>$postText <small>Posted on $postDate</small></li>";
                        }
                        
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
