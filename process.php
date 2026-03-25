<?php
include 'db_config.php'; // Bring in the connection

if (isset($_POST['submit'])) {
    // 1. Get the data from the form
    $customer = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $item = mysqli_real_escape_string($conn, $_POST['item_name']);
    $price = $_POST['price'];
    $qty = $_POST['quantity'];

    // 2. Calculate Total
    $total = $price * $qty;

    // 3. Insert into Database
    $sql = "INSERT INTO sales (customer_name, item_name, price, quantity, total_amount) 
            VALUES ('$customer', '$item', '$price', '$qty', '$total')";

    if (mysqli_query($conn, $sql)) {
        // Get the ID of the record we just saved to show it on the receipt
        $last_id = mysqli_insert_id($conn);
        header("Location: view_receipt.php?id=" . $last_id);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>