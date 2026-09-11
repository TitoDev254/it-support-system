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

$sql = "SELECT id, title, category, priority, status, created_at
        FROM tickets
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Tickets | IT Support System</title>

<link rel="stylesheet" href="../assets/css/style.css">
```

</head>

<body>

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

<div class="container">

```
<div class="header">

    <h1>🎫 My Support Tickets</h1>

    <p>
        View and track the support requests you have submitted.
    </p>

</div>


<br>


<a class="btn" href="create_ticket.php">
    ➕ Submit New Ticket
</a>


<div class="table-container">

    <?php if ($result->num_rows > 0): ?>

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Title</th>

                    <th>Category</th>

                    <th>Priority</th>

                    <th>Status</th>

                    <th>Date Submitted</th>

                    <th>Action</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($ticket = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            #<?php echo $ticket["id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($ticket["title"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($ticket["category"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($ticket["priority"]); ?>
                        </td>

                        <td>

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

                        </td>

                        <td>
                            <?php echo htmlspecialchars($ticket["created_at"]); ?>
                        </td>

                        <td>

                            <a class="btn"
                               href="view_ticket.php?id=<?php echo $ticket['id']; ?>">

                                View

                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    <?php else: ?>

        <div class="actions">

            <h3>📭 No Tickets Yet</h3>

            <p>
                You have not submitted any support tickets yet.
            </p>

            <br>

            <a class="btn" href="create_ticket.php">
                📝 Report an Issue
            </a>

        </div>

    <?php endif; ?>

</div>


<br>

<a href="dashboard.php">
    ← Back to Dashboard
</a>
```

</div>

</body>

</html>

<?php

$stmt->close();

$conn->close();

?>
