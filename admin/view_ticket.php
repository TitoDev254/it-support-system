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

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Invalid ticket ID.");
}

$ticket_id = (int) $_GET["id"];

$sql = "SELECT tickets.id,
               tickets.title,
               tickets.description,
               tickets.category,
               tickets.priority,
               tickets.status,
               tickets.created_at,
               tickets.updated_at,
               users.name AS user_name,
               users.email AS user_email

        FROM tickets

        INNER JOIN users
        ON tickets.user_id = users.id

        WHERE tickets.id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $ticket_id);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Ticket not found.");
}

$ticket = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Ticket #<?php echo $ticket["id"]; ?> | Admin</title>

<link rel="stylesheet" href="../assets/css/style.css">
```

</head>

<body>

<div class="navbar">

```
<a href="dashboard.php">
    🛠️ Dashboard
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
```

</div>

<div class="container">

```
<div class="header">

    <h1>
        🎫 Ticket #<?php echo $ticket["id"]; ?>
    </h1>

    <p>
        Complete support ticket information.
    </p>

</div>


<div class="form-container">

    <h2>
        <?php echo htmlspecialchars($ticket["title"]); ?>
    </h2>

    <br>


    <div class="form-group">

        <label>Submitted By</label>

        <p>
            <?php echo htmlspecialchars($ticket["user_name"]); ?>
        </p>

        <p>
            <?php echo htmlspecialchars($ticket["user_email"]); ?>
        </p>

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


    <a class="btn" href="tickets.php">
        ← Back to Tickets
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
