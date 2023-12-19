<?php
require_once('conn.php');

$query = "SELECT * FROM posts ORDER BY post_date ASC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    echo "Post from the database";
    $post_text = $row['post_text'];
    $post_date = $row['post_date'];
?>
    <!DOCTYPE html>
    <html>
    <head>
        <link rel="stylesheet" type="text/css" href="assets/css/tweet_box.css">
    </head>
    <body>
        <div class="tweet_box">
            <div class="tweet_left">
                <img src="images/avatar.png" alt="">
            </div>
            <div class="tweet_body">
                <div class="tweet_header">
                    <p class="tweet_name">Connect</p>
                    <p class="tweet_username">@connect_test</p>
                    <p class="tweet_date"><?php echo date('M d', strtotime($post_date)); ?></p>
                </div>

                <p class="tweet_text"><?php echo htmlspecialchars($post_text); ?></p>

                <div class="tweet__icons">
                    <a href="#"><i class="far fa-comment"></i></a>
                    <a href="#"><i class="far fa-heart"></i></a>
                </div>
            </div>
        </div>
    </body>
    </html>
<?php
}
?>
