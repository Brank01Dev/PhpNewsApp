<?php
require_once 'ConnectClass.php';
$conn = new Connection();
$db = $conn->connection;

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $db->query("DELETE FROM news WHERE id = $id");
    header("Location: admin.php");
    exit();
}

if (isset($_POST['add_news'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];
    $db->query("INSERT INTO news (title, content, category_id) VALUES ('$title', '$content', '$category_id')");
    header("Location: admin.php");
    exit();
}

if (isset($_POST['delete_comment'])) {
    $comment_id = $_POST['comment_id'];
    $db->query("DELETE FROM comments WHERE id = $comment_id");
    header("Location: admin.php");
    exit();
}

$news = $db->query("SELECT n.*, c.name as category_name FROM news n JOIN categories c ON n.category_id = c.id");
$categories = $db->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="css/styling.css?v=1.0.3">

<body class="body">

    <div class="form1">
        <h2>Admin Panel</h2>

        <h3>Add News</h3>
        <form method="post" class="addForm">
            <input type="text" name="title" placeholder="Title" required><br><br>
            <textarea name="content" placeholder="Content" required rows="5" style="width: 100%;"></textarea><br><br>
            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php while($cat = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                <?php endwhile; ?>
            </select><br><br>
            <button type="submit" name="add_news">Add News</button>
        </form>
    </div>

    <div class="form1">
        <h3>All News</h3>

        <?php while($article = $news->fetch_assoc()): ?>
            <div class="newsArtic">
                <div class="showNews">
                    <h4><?php echo htmlspecialchars($article['title']); ?></h4>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($article['category_name']); ?></p>
                    <p><?php echo htmlspecialchars($article['content']); ?></p>
                    <a href="admin.php?delete=<?php echo $article['id']; ?>">
                        <button>Delete Article</button>
                    </a>

                    <h5>Comments:</h5>
                    <?php
                        $comments = $db->query("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE news_id = " . $article['id']);
                        if ($comments && $comments->num_rows > 0):
                            while ($comment = $comments->fetch_assoc()):
                    ?>
                        <div class="ShowComments">
                            <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong>: <?php echo htmlspecialchars($comment['content']); ?></p>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                                <input type="submit" name="delete_comment" value="Delete Comment">
                            </form>
                        </div>
                    <?php endwhile; else: ?>
                        <p>No comments.</p>
                    <?php endif; ?>
                </div>
            </div>
                    
        <?php endwhile; ?>

        <div class="formButtons">
        <p><a href="startpage.php" class="buttonLink">Back to News</a></p>
        </div>
     </div>
</body>
</html>