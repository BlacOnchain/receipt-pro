<?php
session_start();
// Check if logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

include 'db_config.php';

// Get the ID from the URL
if (!isset($_GET['id'])) {
    die("No receipt ID provided.");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM sales WHERE id = $id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Receipt not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt #<?php echo $data['id']; ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #f4f4f4; padding: 40px 20px; margin: 0; }
        .receipt-card { background: white; width: 350px; margin: auto; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-top: 5px solid #007bff; border-radius: 4px; }
        .header { text-align: center; border-bottom: 1px dashed #ccc; padding-bottom: 15px; margin-bottom: 20px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .total { border-top: 2px solid #333; padding-top: 15px; font-weight: bold; font-size: 18px; margin-top: 10px; }
        .footer { text-align: center; margin-top: 25px; font-size: 11px; color: #777; line-height: 1.5; }
        
        /* Navigation Buttons */
        .actions { width: 350px; margin: 20px auto; display: flex; gap: 10px; }
        .btn { flex: 1; text-align: center; padding: 12px; text-decoration: none; border-radius: 6px; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: bold; cursor: pointer; border: none; }
        .btn-print { background: #007bff; color: white; }
        .btn-back { background: #6c757d; color: white; }
        .btn:hover { opacity: 0.9; }

        /* Hide buttons when printing */
        @media print {
            .actions, .btn { display: none; }
            body { background: white; padding: 0; }
            .receipt-card { box-shadow: none; border: none; margin: 0; width: 100%; }
        }
    </style>
</head>
<body>

    <div class="receipt-card">
        <div class="header">
            <h2 style="margin: 0 0 5px 0;">BLAC'S TECH HUB</h2>
            <p style="margin: 0; font-size: 12px; color: #555;">Digital Sales Receipt</p>
        </div>

        <div class="row">
            <span>Date:</span>
            <span><?php echo date('d M, Y', strtotime($data['sale_date'])); ?></span>
        </div>
        <div class="row">
            <span>Customer:</span>
            <span><?php echo strtoupper($data['customer_name']); ?></span>
        </div>
        
        <div style="border-bottom: 1px solid #eee; margin: 15px 0;"></div>
        
        <div class="row">
            <span style="font-weight: bold;"><?php echo $data['item_name']; ?></span>
            <span>x<?php echo $data['quantity']; ?></span>
        </div>
        <div class="row" style="color: #666; font-size: 13px;">
            <span>Unit Price:</span>
            <span>₦<?php echo number_format($data['price'], 2); ?></span>
        </div>
        
        <div class="row total">
            <span>TOTAL:</span>
            <span>₦<?php echo number_format($data['total_amount'], 2); ?></span>
        </div>

        <div class="footer">
            <p>Thank you for choosing us!<br>
            Please keep this receipt for your records.<br>
            <strong>ID: #<?php echo $data['id']; ?></strong></p>
        </div>
    </div>

    <div class="actions">
        <a href="dashboard.php" class="btn btn-back">← Dashboard</a>
        <button onclick="window.print();" class="btn btn-print">Print / PDF</button>
    </div>

</body>
</html>