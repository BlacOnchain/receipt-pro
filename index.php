<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Sale | Receipt Pro</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; padding: 0; }
        .form-container { max-width: 450px; background: white; padding: 30px; border-radius: 12px; margin: 50px auto; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        h2 { text-align: center; color: #333; margin-bottom: 25px; }
        label { font-size: 13px; color: #666; font-weight: bold; display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 15px; }
        button { width: 100%; padding: 14px; background: #007bff; color: white; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.2s; }
        button:hover { background: #0056b3; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="form-container">
        <h2>Generate New Receipt</h2>
        <form action="process.php" method="POST">
            <label>Customer Name</label>
            <input type="text" name="customer_name" placeholder="Enter name" required>
            
            <label>Item Description</label>
            <input type="text" name="item_name" placeholder="What did they buy?" required>
            
            <label>Unit Price (₦)</label>
            <input type="number" name="price" placeholder="0.00" step="0.01" required>
            
            <label>Quantity</label>
            <input type="number" name="quantity" value="1" min="1" required>
            
            <button type="submit" name="submit">Save & View Receipt</button>
        </form>
        <a href="dashboard.php" class="back-link">← Cancel and go to Dashboard</a>
    </div>

</body>
</html>