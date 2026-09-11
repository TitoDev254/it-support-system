<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION["user_id"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $priority = $_POST["priority"];

    $status = "Pending";

    $sql = "INSERT INTO tickets 
            (user_id, title, description, category, priority, status)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "isssss",
        $user_id,
        $title,
        $description,
        $category,
        $priority,
        $status
    );

    if ($stmt->execute()) {
        $message = "Support ticket submitted successfully!";
    } else {
        $message = "Failed to submit ticket.";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

```
<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Submit Support Ticket | IT Support System</title>

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
<div class="form-container">

    <h1>🎫 Submit IT Support Ticket</h1>

    <p>
        Describe your technical problem below and the IT support team
        will review your request.
    </p>

    <br>


    <!-- SUCCESS / ERROR MESSAGE -->

    <?php if ($message != ""): ?>

        <div class="success">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php endif; ?>


    <!-- TICKET FORM -->

    <form method="POST">

        <div class="form-group">

            <label for="title">
                Issue Title
            </label>

            <input
                type="text"
                id="title"
                name="title"
                placeholder="e.g. Computer cannot connect to Wi-Fi"
                required
            >

        </div>


        <div class="form-group">

            <label for="description">
                Description
            </label>

            <textarea
                id="description"
                name="description"
                placeholder="Describe the problem in detail..."
                required
            ></textarea>

        </div>


        <div class="form-group">

            <label for="category">
                Category
            </label>

            <select id="category" name="category">

                <option value="Hardware">
                    Hardware
                </option>

                <option value="Software">
                    Software
                </option>

                <option value="Network">
                    Network
                </option>

                <option value="Printer">
                    Printer
                </option>

                <option value="Other">
                    Other
                </option>

            </select>

        </div>


        <div class="form-group">

            <label for="priority">
                Priority
            </label>

            <select id="priority" name="priority">

                <option value="Low">
                    Low
                </option>

                <option value="Medium" selected>
                    Medium
                </option>

                <option value="High">
                    High
                </option>

            </select>

        </div>


        <button type="submit">
            🚀 Submit Support Ticket
        </button>

    </form>


    <br>

    <a href="dashboard.php">
        ← Back to Dashboard
    </a>

</div>
```

</div>

</body>

</html>
