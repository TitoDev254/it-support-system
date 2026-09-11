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

if (!isset($_GET["id"])) {
    die("User ID is missing.");
}

$user_id = intval($_GET["id"]);

/* Prevent admin from deleting their own account */
if ($user_id == $_SESSION["user_id"]) {
    die("You cannot delete your own admin account.");
}

/* Check if user exists */
$sql = "SELECT id, name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("User not found.");
}

$user = $result->fetch_assoc();

/* Delete user */
$delete_sql = "DELETE FROM users WHERE id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("i", $user_id);

if ($delete_stmt->execute()) {

    header("Location: manage_users.php");
    exit();

} else {

    echo "Error deleting user.";

}

?>