<?php
session_start();

// Check if user is logged in and if they are an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'connect.php'; // Include database connection

// Check if event_id is passed in the URL
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Fetch event details (optional: to display event info)
    $event_sql = "SELECT * FROM events WHERE id = ?";
    $event_stmt = $conn->prepare($event_sql);
    $event_stmt->bind_param("i", $event_id);
    $event_stmt->execute();
    $event_result = $event_stmt->get_result();
    $event = $event_result->fetch_assoc();
    $event_stmt->close();

    // Fetch bookings for the specific event
    $bookings_sql = "SELECT b.*, u.username FROM bookings b JOIN users u ON b.user_id = u.id WHERE b.event_id = ?";
    $bookings_stmt = $conn->prepare($bookings_sql);
    $bookings_stmt->bind_param("i", $event_id);
    $bookings_stmt->execute();
    $bookings_result = $bookings_stmt->get_result();
} else {
    // Redirect if no event ID is passed
    header("Location: admin-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings - Eventify</title>
    <link rel="stylesheet" href="admin-dashboard.css">
</head>
<body>

    <nav>
        <h2>Admin Dashboard</h2>
        <a href="admin-dashboard.php?logout=true">Logout</a>
    </nav>

    <h3>Bookings for Event: <?php echo $event['title']; ?></h3>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Booking Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($bookings_result->num_rows > 0) {
                while ($booking = $bookings_result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$booking['username']}</td>
                            <td>{$booking['booking_date']}</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No bookings for this event.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>

<?php
// Close the database connection
$bookings_stmt->close();
$conn->close();
?>
