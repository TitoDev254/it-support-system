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


// Update ticket status

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ticket_id = (int) $_POST["ticket_id"];
    $status = $_POST["status"];

    // Only allow valid statuses
    $allowed_statuses = ["Pending", "In Progress", "Resolved"];

    if (in_array($status, $allowed_statuses)) {

        $sql_update = "UPDATE tickets
                       SET status = ?, updated_at = CURRENT_TIMESTAMP
                       WHERE id = ?";

        $stmt_update = $conn->prepare($sql_update);

        $stmt_update->bind_param(
            "si",
            $status,
            $ticket_id
        );

        $stmt_update->execute();

        $stmt_update->close();
    }
}



// Get tickets

$status_filter = "";

if (isset($_GET["status"])) {
    $status_filter = $_GET["status"];
}


// Get search value

$search = "";

if (isset($_GET["search"])) {
    $search = trim($_GET["search"]);
}


// Base SQL query

$sql = "SELECT tickets.id,
               tickets.title,
               tickets.category,
               tickets.priority,
               tickets.status,
               tickets.created_at,
               users.name AS user_name

        FROM tickets

        INNER JOIN users
        ON tickets.user_id = users.id";


$conditions = [];


// Filter by status

if ($status_filter != "") {

    $safe_status = $conn->real_escape_string($status_filter);

    $conditions[] = "tickets.status = '$safe_status'";
}


// Search tickets

if ($search != "") {

    $safe_search = $conn->real_escape_string($search);

    $conditions[] = "(
        users.name LIKE '%$safe_search%'
        OR tickets.title LIKE '%$safe_search%'
        OR tickets.category LIKE '%$safe_search%'
        OR tickets.priority LIKE '%$safe_search%'
    )";
}


// Add conditions

if (count($conditions) > 0) {

    $sql .= " WHERE " . implode(" AND ", $conditions);

}


// Order newest first

$sql .= " ORDER BY tickets.created_at DESC";


$result = $conn->query($sql);
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../assets/css/style.css">

    <title>Manage Tickets</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        h1 {
            margin-bottom: 20px;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #1f2937;
            color: white;
            white-space: nowrap;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-progress {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-resolved {
            background: #d1fae5;
            color: #065f46;
        }

        .btn {
            display: inline-block;
            padding: 8px 14px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        td form {
            display: flex;
            gap: 8px;
            align-items: center;
            margin: 0;
        }

        select {
            padding: 7px;
        }

        button {
            padding: 7px 12px;
            cursor: pointer;
        }

        .back {
            display: inline-block;
            margin-bottom: 20px;
        }

    </style>

</head>


<body>

<div class="navbar">

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

</div>


<div class="container">

 <h1>Manage Support Tickets 🎫</h1>

<a class="back" href="dashboard.php">
    ← Back to Dashboard
</a>

<a class="btn" href="tickets.php">
    📋 Show All Tickets
</a>
<form method="GET" style="margin: 15px 0;">

    <input
        type="text"
        name="search"
        placeholder="Search by staff, issue, category or priority..."
        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
        style="padding: 10px; width: 300px;"
    >

    <button type="submit">
        🔍 Search
    </button>

    <a class="btn" href="tickets.php">
        Clear
    </a>

</form>


    <?php if ($result->num_rows > 0): ?>

        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Staff</th>
                        <th>Issue</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Update</th>

                    </tr>

                </thead>


                <tbody>

                    <?php while ($ticket = $result->fetch_assoc()): ?>

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

                        <tr>

                            <!-- 1. ID -->
                            <td>
                                <?php echo $ticket["id"]; ?>
                            </td>


                            <!-- 2. STAFF -->
                            <td>
                                <?php echo htmlspecialchars($ticket["user_name"]); ?>
                            </td>


                            <!-- 3. ISSUE -->
                            <td>
                                <?php echo htmlspecialchars($ticket["title"]); ?>
                            </td>


                            <!-- 4. CATEGORY -->
                            <td>
                                <?php echo htmlspecialchars($ticket["category"]); ?>
                            </td>


                            <!-- 5. PRIORITY -->
                            <td>
                                <?php echo htmlspecialchars($ticket["priority"]); ?>
                            </td>


                            <!-- 6. STATUS -->
                            <td>

                                <span class="status <?php echo $status_class; ?>">

                                    <?php echo htmlspecialchars($status); ?>

                                </span>

                            </td>


                            <!-- 7. DATE -->
                            <td>
                                <?php echo htmlspecialchars($ticket["created_at"]); ?>
                            </td>


                            <!-- 8. ACTION -->
                            <td>

                                <a class="btn"
                                   href="view_ticket.php?id=<?php echo $ticket["id"]; ?>">

                                    View

                                </a>

                            </td>


                            <!-- 9. UPDATE -->
                            <td>

                                <form method="POST">

                                    <input
                                        type="hidden"
                                        name="ticket_id"
                                        value="<?php echo $ticket["id"]; ?>"
                                    >

                                    <select name="status">

                                        <option value="Pending"
                                            <?php if ($status == "Pending") echo "selected"; ?>>

                                            Pending

                                        </option>


                                        <option value="In Progress"
                                            <?php if ($status == "In Progress") echo "selected"; ?>>

                                            In Progress

                                        </option>


                                        <option value="Resolved"
                                            <?php if ($status == "Resolved") echo "selected"; ?>>

                                            Resolved

                                        </option>

                                    </select>


                                    <button type="submit">

                                        Update

                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>


    <?php else: ?>

        <p>No support tickets found.</p>

    <?php endif; ?>


</div>


</body>

</html>

<?php

$conn->close();

?>