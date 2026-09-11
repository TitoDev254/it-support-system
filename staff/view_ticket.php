<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SESSION["role"] != "staff") {
    die("Access denied. Staff only.");
}

require_once "../config/database.php";

$user_id = $_SESSION["user_id"];

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Invalid ticket ID.");
}

$ticket_id = (int) $_GET["id"];

$sql = "SELECT id, title, description, category, priority, status, created_at, updated_at
        FROM tickets
        WHERE id = ? AND user_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ii", $ticket_id, $user_id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Ticket not found or access denied.");
}

$ticket = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Ticket #<?php echo $ticket["id"]; ?> | IT Support System</title>

<link rel="stylesheet" href="../assets/css/style.css">
```

</head>

<body>

<!-- NAVIGATION -->

<div class="navbar">

```
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
```

</div>

<!-- MAIN CONTENT -->

<div class="container">

```
<div class="header">

    <h1>
        🎫 Ticket #<?php echo $ticket["id"]; ?>
    </h1>

    <p>
        View the details and current status of your support request.
    </p>

</div>


<div class="form-container">

    <h2>
        <?php echo htmlspecialchars($ticket["title"]); ?>
    </h2>

    <br>


    <div class="form-group">

        <label>Status</label>

        <?php

        $status = $ticket["status"];

        if ($status == "Pending") {
            $status_class = "status-pending";
        } elseif ($status == "In Progress") {
            $status_class = "status-progress";
        } elseif ($status == "Resolved") {
            $status_class = "status-resolved";
        } else {
            $status_class = "";
        }

        ?>

        <span class="status <?php echo $status_class; ?>">
            <?php echo htmlspecialchars($status); ?>
        </span>

    </div>


    <div class="form-group">

        <label>Category</label>

        <p>
            <?php echo htmlspecialchars($ticket["category"]); ?>
        </p>

    </div>


    <div class="form-group">

        <label>Priority</label>

        <p>
            <?php echo htmlspecialchars($ticket["priority"]); ?>
        </p>

    </div>


    <div class="form-group">

        <label>Description</label>

        <p>
            <?php echo nl2br(htmlspecialchars($ticket["description"])); ?>
        </p>

    </div>


    <div class="form-group">

        <label>Date Submitted</label>

        <p>
            <?php echo htmlspecialchars($ticket["created_at"]); ?>
        </p>

    </div>


    <div class="form-group">

        <label>Last Updated</label>

        <p>
            <?php echo htmlspecialchars($ticket["updated_at"]); ?>
        </p>

    </div>


    <a class="btn" href="my_tickets.php">
        ← Back to My Tickets
    </a>

</div>
```

</div>

</body>

</html>

<?php

$stmt->close();

$conn->close();

?>
