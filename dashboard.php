<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }
include 'db_config.php';

// 1. Stats Calculation
$today = date('Y-m-d');
$this_month = date('Y-m');

$today_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM sales WHERE DATE(sale_date) = '$today'"));
$month_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM sales WHERE sale_date LIKE '$this_month%'"));
$count_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as count FROM sales"));

// 2. Chart Logic (Last 7 Days)
$chart_labels = []; $chart_data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $chart_labels[] = date('D', strtotime($date));
    $day_val = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_amount) as total FROM sales WHERE DATE(sale_date) = '$date'"));
    $chart_data[] = $day_val['total'] ?? 0;
}

// 3. Search Logic
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$query = "SELECT * FROM sales WHERE customer_name LIKE '%$search%' OR item_name LIKE '%$search%' ORDER BY sale_date DESC";
$all_sales = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Receipt Pro</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; }
        .container { max-width: 1100px; margin: auto; padding: 20px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 25px; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card h3 { margin: 0; font-size: 12px; color: #888; text-transform: uppercase; }
        .card p { margin: 10px 0 0; font-size: 26px; font-weight: bold; }
        .btn-new { background: #007bff; color: white; text-decoration: none; padding: 12px 20px; border-radius: 8px; font-weight: bold; }
        .btn-pdf { background: #17a2b8; color: white; text-decoration: none; padding: 12px 20px; border-radius: 8px; font-weight: bold; display: inline-block; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; }
        th { background: #f8f9fa; padding: 15px; text-align: left; font-size: 12px; color: #666; }
        td { padding: 15px; border-top: 1px solid #eee; font-size: 14px; }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2>Business Overview</h2>
            <div>
                <a href="logout.php" style="color: #dc3545; text-decoration: none; margin-right: 20px; font-size: 14px;">Logout</a>
                <a href="index.php" class="btn-new">+ New Sale</a>
            </div>
        </div>

        <div class="grid">
            <div class="card" style="border-bottom: 4px solid #007bff;">
                <h3>Today's Revenue</h3>
                <p>₦<?php echo number_format($today_res['total'] ?? 0, 2); ?></p>
            </div>
            <div class="card" style="border-bottom: 4px solid #28a745;">
                <h3>Monthly Total</h3>
                <p>₦<?php echo number_format($month_res['total'] ?? 0, 2); ?></p>
            </div>
            <div class="card">
                <h3>Total Receipts</h3>
                <p><?php echo $count_res['count']; ?></p>
            </div>
        </div>

        <div class="card" style="margin-bottom: 25px; height: 350px;">
            <h3 style="margin-bottom: 20px;">7-Day Sales Trend (₦)</h3>
            <canvas id="salesChart"></canvas>
        </div>

        <a href="generate_report.php" target="_blank" class="btn-pdf">📄 Generate Monthly PDF Report</a>

        <div style="background: white; padding: 15px; border-radius: 12px; margin-bottom: 20px; display: flex; gap: 10px;">
            <form style="display: flex; width: 100%; gap: 10px;">
                <input type="text" name="search" style="flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Search customer..." value="<?php echo $search; ?>">
                <button type="submit" style="padding: 10px 20px; background: #333; color: white; border: none; border-radius: 6px; cursor: pointer;">Search</button>
            </form>
        </div>

        <table>
            <thead>
                <tr><th>Customer</th><th>Item</th><th>Total</th><th>Date</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($all_sales)): ?>
                <tr>
                    <td><strong><?php echo strtoupper($row['customer_name']); ?></strong></td>
                    <td><?php echo $row['item_name']; ?></td>
                    <td style="color: #28a745; font-weight: bold;">₦<?php echo number_format($row['total_amount'], 2); ?></td>
                    <td><?php echo date('M d', strtotime($row['sale_date'])); ?></td>
                    <td><a href="view_receipt.php?id=<?php echo $row['id']; ?>" style="color: #007bff; text-decoration: none;">View</a></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: [{
                    label: 'Revenue',
                    data: <?php echo json_encode($chart_data); ?>,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });
    </script>
</body>
</html>