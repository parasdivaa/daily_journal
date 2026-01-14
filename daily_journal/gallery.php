<?php
require_once 'config.php';
checkLogin();
checkAdmin();

// ADD GALLERY
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO gallery (title, description, image_url, created_date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['title'], $_POST['description'], $_POST['image_url'], $_POST['date']]);
    $msg = "Gallery added!";
}

// DELETE GALLERY
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $msg = "Gallery deleted!";
}

// PAGINATION
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;

// COUNT TOTAL
$total = $pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
$pages = ceil($total / $limit);

// GET DATA
$stmt = $pdo->prepare("SELECT * FROM gallery LIMIT ? OFFSET ?");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$gallery = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gallery Management</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .container { max-width: 1000px; margin: auto; }
        .form-box { background: #f9f9f9; padding: 20px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background: #4a90e2; color: white; }
        .pagination a { padding: 5px 10px; margin: 0 2px; background: #ddd; text-decoration: none; }
        .pagination a.active { background: #4a90e2; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gallery Management</h1>
        <a href="index.php">← Back to Home</a>
        
        <?php if (isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
        
        <!-- ADD FORM -->
        <div class="form-box">
            <h3>Add New Gallery</h3>
            <form method="POST">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="description" placeholder="Description" rows="2"></textarea>
                <input type="text" name="image_url" placeholder="Image URL" required>
                <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                <button type="submit" name="add">Add Gallery</button>
            </form>
        </div>
        
        <!-- GALLERY TABLE -->
        <h3>Gallery List (Total: <?php echo $total; ?>)</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php if (empty($gallery)): ?>
                <tr><td colspan="6">No gallery data</td></tr>
            <?php else: ?>
                <?php $no = $offset + 1; ?>
                <?php foreach ($gallery as $item): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($item['title']); ?></td>
                    <td><?php echo htmlspecialchars($item['description']); ?></td>
                    <td><img src="<?php echo $item['image_url']; ?>" width="80"></td>
                    <td><?php echo $item['created_date']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $item['id']; ?>" 
                           onclick="return confirm('Delete this?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        
        <!-- PAGINATION -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" 
                   class="<?php echo $i == $page ? 'active' : ''; ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>