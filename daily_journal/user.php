<?php
require_once 'config.php';
checkLogin();
checkAdmin();

// ADD USER
if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO users (username, password, name, email, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['username'],
        $_POST['password'],
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['role']
    ]);
    $msg = "User added!";
}

// DELETE USER
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Cegah hapus diri sendiri
    if ($id != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $msg = "User deleted!";
    } else {
        $error = "Cannot delete yourself!";
    }
}

// PAGINATION
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;

// COUNT
$total = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$pages = ceil($total / $limit);

// GET USERS
$stmt = $pdo->prepare("SELECT * FROM users LIMIT ? OFFSET ?");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        /* Same style as gallery.php */
    </style>
</head>
<body>
    <div class="container">
        <h1>User Management</h1>
        <a href="index.php">← Back to Home</a>
        
        <?php if (isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        
        <!-- ADD FORM -->
        <div class="form-box">
            <h3>Add New User</h3>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone">
                <select name="role">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="add">Add User</button>
            </form>
        </div>
        
        <!-- USERS TABLE -->
        <h3>User List (Total: <?php echo $total; ?>)</h3>
        <table>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php if (empty($users)): ?>
                <tr><td colspan="7">No users</td></tr>
            <?php else: ?>
                <?php $no = $offset + 1; ?>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo $user['phone']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <a href="?delete=<?php echo $user['id']; ?>" 
                               onclick="return confirm('Delete user <?php echo $user['username']; ?>?')">Delete</a>
                        <?php else: ?>
                            <span style="color:gray">Current User</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        
        <!-- PAGINATION -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>