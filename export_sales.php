<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    exit("Access Denied");
}
include 'db_config.php';

// Force the browser to download a CSV file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=ReceiptPro_Report_'.date('Y-m-d').'.csv');

$output = fopen('php://output', 'w');

// Set headers for the Excel columns
fputcsv($output, array('ID', 'Customer', 'Item', 'Price', 'Qty', 'Total Amount', 'Sale Date'));

// Grab all sales
$data = mysqli_query($conn, "SELECT id, customer_name, item_name, price, quantity, total_amount, sale_date FROM sales ORDER BY id DESC");

while ($row = mysqli_fetch_assoc($data)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>