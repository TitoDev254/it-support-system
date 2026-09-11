<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION["role"] != "staff") {
    die("Access denied.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Staff Dashboard | IT Support System</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>


<!-- NAVIGATION -->

<div class="navbar">

    <a href="dashboard.php">
        🛠️ IT Support System
    </a>

    <a href="create_ticket.php">
        📝 Report Issue
    </a>

    <a href="my_tickets.php">
        🎫 My Tickets
    </a>

    <a href="my_assets.php">
        💻 My Assets
    </a>

    <a href="../auth/logout.php">
        🚪 Logout
    </a>

</div>


<!-- MAIN CONTENT -->

<div class="container">


    <!-- WELCOME -->

    <div class="header">

        <h1>
            Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>! 👋
        </h1>

        <p>
            IT Support & Asset Management System
        </p>

    </div>


    <!-- QUICK ACTIONS -->

    <div class="cards">


        <div class="card">

            <h3>📝 Report an Issue</h3>

            <p style="font-size: 16px; font-weight: normal;">
                Having a technical problem?
            </p>

            <br>

            <a class="btn" href="create_ticket.php">
                Submit Ticket
            </a>

        </div>


        <div class="card">

            <h3>🎫 My Tickets</h3>

            <p style="font-size: 16px; font-weight: normal;">
                View your support requests and their status.
            </p>

            <br>

            <a class="btn" href="my_tickets.php">
                View Tickets
            </a>

        </div>


        <div class="card">

            <h3>💻 My Assets</h3>

            <p style="font-size: 16px; font-weight: normal;">
                View equipment assigned to you.
            </p>

            <br>

            <a class="btn" href="my_assets.php">
                View Assets
            </a>

        </div>


    </div>


    <!-- INFORMATION -->

    <div class="actions">

        <h2>How IT Support Works</h2>

        <br>

        <p>
            📝 Submit a support ticket when you experience a technical problem.
        </p>

        <br>

        <p>
            🎫 Track your ticket and see when its status changes.
        </p>

        <br>

        <p>
            💻 View the IT equipment currently assigned to you.
        </p>

    </div>


</div>

</body>

</html>