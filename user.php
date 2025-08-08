<?php
session_start();
require_once 'ConnectClass.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$conn = new Connection();
$db = $conn->connection;

if (isset($_POST['add_comment'])) {
    $content = $_POST['content'];
    $news_id = $_POST['news_id'];
    $db->query("INSERT INTO comments (content, news_id, user_id) VALUES ('$content', $news_id, $user_id)");
    header("Location: user.php");
    exit();
}

if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $db->query("DELETE FROM comments WHERE id = $comment_id AND user_id = $user_id");
    header("Location: user.php");
    exit();
}

$news = $db->query("SELECT * FROM news");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/styling.css">
</head>
<body class="body">

    <div class="startHead">
        <h2>GLOBAL NEWS</h2>
    </div>

    <?php if ($news && $news->num_rows > 0): ?>
        <?php while($article = $news->fetch_assoc()): ?>
            <div class="newsArtic">
                <div class="showNews">
                    <h4><?php echo htmlspecialchars($article['title']); ?></h4>
                    <p><?php echo htmlspecialchars($article['content']); ?></p>

                    <div class="comments">
                        <h5>Comments:</h5>
                        <?php
                        $comments = $db->query("
                        SELECT comments.id, comments.content, comments.user_id, users.username
                        FROM comments
                        JOIN users ON comments.user_id = users.id
                        WHERE comments.news_id = " . $article['id']);
                        if ($comments && $comments->num_rows > 0):
                            while ($comment = $comments->fetch_assoc()):
                        ?>
                            <div class="ShowComments">
                            <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                                <p><?php echo htmlspecialchars($comment['content']); ?></p>
                                <?php if ($comment['user_id'] == $user_id): ?>
                                    <form method="post" class="commentForm">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                        <input type="submit" name="delete_comment" value="Delete" class="commentButton">
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; else: ?>
                            <p>No comments.</p>
                        <?php endif; ?>
                    </div>

                    <form method="post" class="commentForm">
                        <input type="text" name="content" placeholder="Write your comment..." required class="commentInput">
                        <input type="hidden" name="news_id" value="<?php echo $article['id']; ?>">
                        <input type="submit" name="add_comment" value="Post Comment" class="commentButton">
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="catSelect">No news yet.</p>
    <?php endif; ?>

    <div class="bottomLinks">
        <a href="startpage.php" class="buttonLink">Log out</a>
    </div>
</body>
</html>