<?php
include 'db_config.php';
// Fetch Business Name from Settings
$set_res = mysqli_query($conn, "SELECT business_name FROM settings WHERE id=1");
$set_data = mysqli_fetch_assoc($set_res);
$b_name = $set_data['business_name'] ?? "Receipt Pro";
?>
<div style="background: white; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; font-family: 'Segoe UI', sans-serif;">
    <div style="display: flex; align-items: center;">
        <img src="logo.png.jfif" alt="Logo" style="height: 50px; width: auto; margin-right: 15px;">
        <div>
            <h1 style="margin: 0; font-size: 18px; color: #333;"><?php echo $b_name; ?></h1>
            <p style="margin: 0; font-size: 9px; color: #007bff; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">Admin Terminal</p>
        </div>
    </div>
    <div style="display: flex; gap: 20px; align-items: center;">
        <a href="dashboard.php" style="text-decoration: none; color: #666; font-size: 13px; font-weight: 600;">🏠 Dashboard</a>
        <a href="settings.php" style="text-decoration: none; color: #666; font-size: 13px; font-weight: 600;">⚙️ Settings</a>
        <span style="font-size: 11px; color: #aaa; border-left: 1px solid #ddd; padding-left: 15px;"><?php echo date('d M, Y'); ?></span>
    </div>
</div>