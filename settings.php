<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }
include 'db_config.php';

if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['b_name']);
    $pass = mysqli_real_escape_string($conn, $_POST['b_pass']);
    mysqli_query($conn, "UPDATE settings SET business_name='$name', admin_password='$pass' WHERE id=1");
    $success = "Settings updated successfully!";
}

$res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM settings WHERE id=1"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | Receipt Pro</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; }
        .card { max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 12px; margin: 10px 0 20px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="card">
        <h2>System Settings</h2>
        <?php if(isset($success)) echo "<p style='color: green;'>$success</p>"; ?>
        <form method="POST">
            <label>Business Name</label>
            <input type="text" name="b_name" value="<?php echo $res['business_name']; ?>" required>
            <label>Admin Password</label>
            <input type="text" name="b_pass" value="<?php echo $res['admin_password']; ?>" required>
            <button type="submit" name="update" class="btn">Save Configuration</button>
        </form>
    </div>
</body>
</html>