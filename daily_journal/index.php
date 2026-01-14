<?php
require_once 'config.php';

// LOGIN
if (isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->execute([$user, $pass]);
    $user_data = $stmt->fetch();
    
    if ($user_data) {
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['name'] = $user_data['name'];
        $_SESSION['role'] = $user_data['role'];
        $login_success = true;
    } else {
        $error = "Username/password salah!";
    }
}

// LOGOUT
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daily Journal</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f0f0f0; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        .login-box { width: 300px; margin: 100px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        button { background: #4a90e2; color: white; border: none; padding: 10px 15px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #4a90e2; color: white; }
        .nav { background: #333; padding: 10px; margin-bottom: 20px; }
        .nav a { color: white; margin-right: 15px; text-decoration: none; }
        .alert { padding: 10px; background: #ff4757; color: white; margin: 10px 0; }
        .success { background: #2ed573; }
    </style>
</head>
<body>
    
    <?php if (!isset($_SESSION['user_id'])): ?>
    <!-- LOGIN FORM -->
    <div class="login-box">
        <h2>Login Daily Journal</h2>
        <?php if (isset($error)) echo "<div class='alert'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p><b></b> danny / admin (admin) | parasdiva / 123456 (user)</p>
    </div>
    
    <?php else: ?>
    <!-- DASHBOARD -->
    <div class="container">
        <div class="nav">
            <a href="index.php">Home</a>
            <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="gallery.php">Gallery Management</a>
            <a href="users.php">User Management</a>
            <?php endif; ?>
            <a href="?logout" style="float:right">Logout</a>
        </div>
        
        <h1>Welcome, <?php echo $_SESSION['name']; ?>!</h1>
        <p>Role: <b><?php echo $_SESSION['role']; ?></b></p>
        
        <h2>Your Profile</h2>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $profile = $stmt->fetch();
        ?>
        <table>
            <tr><td>Username</td><td><?php echo $profile['username']; ?></td></tr>
            <tr><td>Name</td><td><?php echo $profile['name']; ?></td></tr>
            <tr><td>Email</td><td><?php echo $profile['email']; ?></td></tr>
            <tr><td>Phone</td><td><?php echo $profile['phone']; ?></td></tr>
            <tr><td>Role</td><td><?php echo $profile['role']; ?></td></tr>
        </table>
    </div>
    <?php endif; ?>
    
</body>
</html>