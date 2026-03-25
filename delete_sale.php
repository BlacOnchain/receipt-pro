<?php
include 'db_config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // SQL to delete the record
    $sql = "DELETE FROM sales WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Redirect back to dashboard after deleting
        header("Location: dashboard.php?msg=deleted");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
?>