<?php
require_once 'ConnectClass.php';
$conn = new Connection();
$db = $conn->connection;

$news = $db->query("SELECT * FROM news");

$selected_category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';


if ($selected_category_id) {
    $news = $db->query("SELECT * FROM news WHERE category_id = $selected_category_id");
} else {
    $news = $db->query("SELECT * FROM news");
}

$categories = $db->query("SELECT * FROM categories");

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


    <div class="catSelect">
    <form method="get">
        <label for="category_id">Select news category:</label>
        <select name="category_id" id="category_id" onchange="this.form.submit()">
        <option value="">All Categories</option>
            <?php while($cat = $categories->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>" <?php if ($selected_category_id == $cat['id']) echo 'selected'; ?>>
                    <?php echo $cat['name']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>
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
                        SELECT comments.content, users.username 
                        FROM comments 
                        JOIN users ON comments.user_id = users.id 
                        WHERE comments.news_id = " . $article['id']
                    );
                    if ($comments && $comments->num_rows > 0):
                        while($UserComment = $comments->fetch_assoc()):
                    ?>
                        <div class="ShowComments">
                            <strong><?php echo htmlspecialchars($UserComment['username']); ?>:</strong>
                            <p><?php echo htmlspecialchars($UserComment['content']); ?></p>
                        </div>
                    <?php 
                        endwhile;
                    else:
                    ?>
                        <p>No comments.</p>
                    <?php endif; ?>
                </div>
            </div> 
        </div> 
    <?php endwhile; ?>
<?php else: ?>
    <p>No news yet.</p>
<?php endif; ?>
    <div class="bottomLinks">
    <div class="bottomLinks">
    <a href="LoginPage.php" class="buttonLink">Log in</a>
    <a href="RegisterPage.php" class="buttonLink">Register</a>
</div>
    </div>
    
</body>
</html> 