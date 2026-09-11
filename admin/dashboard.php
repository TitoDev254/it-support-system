<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION["role"] != "admin") {
    die("Access denied. Admins only.");
}

require_once "../config/database.php";
$sql_users = "SELECT COUNT(*) AS total_users FROM users";

$result_users = $conn->query($sql_users);

$row_users = $result_users->fetch_assoc();

$total_users = $row_users["total_users"];
$sql_tickets = "SELECT COUNT(*) AS total_tickets FROM tickets";
$result_tickets = $conn->query($sql_tickets);
$row_tickets = $result_tickets->fetch_assoc();
$total_tickets = $row_tickets["total_tickets"];


$sql_pending = "SELECT COUNT(*) AS pending_tickets FROM tickets WHERE status = 'Pending'";
$result_pending = $conn->query($sql_pending);
$row_pending = $result_pending->fetch_assoc();
$pending_tickets = $row_pending["pending_tickets"];


$sql_progress = "SELECT COUNT(*) AS progress_tickets FROM tickets WHERE status = 'In Progress'";
$result_progress = $conn->query($sql_progress);
$row_progress = $result_progress->fetch_assoc();
$progress_tickets = $row_progress["progress_tickets"];


$sql_resolved = "SELECT COUNT(*) AS resolved_tickets FROM tickets WHERE status = 'Resolved'";
$result_resolved = $conn->query($sql_resolved);
$row_resolved = $result_resolved->fetch_assoc();
$resolved_tickets = $row_resolved["resolved_tickets"];

$sql_assets = "SELECT COUNT(*) AS total_assets FROM assets";

$result_assets = $conn->query($sql_assets);

$row_assets = $result_assets->fetch_assoc();

$total_assets = $row_assets["total_assets"];


$sql_available = "SELECT COUNT(*) AS available_assets 
                  FROM assets 
                  WHERE status = 'Available'";

$result_available = $conn->query($sql_available);

$row_available = $result_available->fetch_assoc();

$available_assets = $row_available["available_assets"];


$sql_maintenance = "SELECT COUNT(*) AS maintenance_assets 
                    FROM assets 
                    WHERE status = 'Maintenance'";

$result_maintenance = $conn->query($sql_maintenance);

$row_maintenance = $result_maintenance->fetch_assoc();

$maintenance_assets = $row_maintenance["maintenance_assets"];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | IT Support System</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>


<!-- NAVIGATION -->

<div class="navbar">

    <a href="dashboard.php">
        🛠️ IT Support System
    </a>

    <a href="manage_users.php">
        👥 Users
    </a>

    <a href="manage_assets.php">
        💻 Assets
    </a>

    <a href="tickets.php">
        🎫 Tickets
    </a>

    <a href="../auth/logout.php">
        🚪 Logout
    </a>

</div>


<!-- MAIN CONTENT -->

<div class="container">

    <div class="header">

        <h1>Admin Dashboard 🛠️</h1>

        <p>
            Welcome back, <?php echo htmlspecialchars($_SESSION["name"]); ?>.
        </p>

    </div>


    <!-- STATISTICS -->

    <div class="cards">


        <!-- USERS -->

        <div class="card">

            <h3>👥 Total Users</h3>

            <p>
                <?php echo $total_users; ?>
            </p>

        </div>


        <!-- TICKETS -->

        <div class="card">

            <h3>🎫 Total Tickets</h3>

            <p>
                <?php echo $total_tickets; ?>
            </p>

        </div>


<!-- PENDING -->

<a href="tickets.php?status=Pending" style="text-decoration: none; color: inherit;">

    <div class="card">

        <h3>🟡 Pending</h3>

        <p>
            <?php echo $pending_tickets; ?>
        </p>

    </div>

</a>


        <!-- IN PROGRESS -->

<a href="tickets.php?status=In%20Progress" style="text-decoration: none; color: inherit;">

    <div class="card">

        <h3>🔵 In Progress</h3>

        <p>
            <?php echo $progress_tickets; ?>
        </p>

    </div>

</a>


        <!-- RESOLVED -->

<a href="tickets.php?status=Resolved" style="text-decoration: none; color: inherit;">

    <div class="card">

        <h3>🟢 Resolved</h3>

        <p>
            <?php echo $resolved_tickets; ?>
        </p>

    </div>

</a>


        <!-- TOTAL ASSETS -->

        <div class="card">

            <h3>💻 Total Assets</h3>

            <p>
                <?php echo $total_assets; ?>
            </p>

        </div>


        <!-- AVAILABLE -->

        <div class="card">

            <h3>✅ Available Assets</h3>

            <p>
                <?php echo $available_assets; ?>
            </p>

        </div>


        <!-- MAINTENANCE -->

        <div class="card">

            <h3>🔧 Maintenance</h3>

            <p>
                <?php echo $maintenance_assets; ?>
            </p>

        </div>


    </div>


    <!-- QUICK ACTIONS -->

    <div class="actions">

        <h2>Quick Actions</h2>
        <br>
        <a class="btn" href="manage_users.php">
    👥 Manage Users
</a> </br>

        <br>

        <a class="btn" href="manage_assets.php">
            💻 Manage Assets
        </a>
        

        <a class="btn" href="tickets.php">
            🎫 Manage Tickets
        </a>


    </div>


</div>

</body>

</html>