<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'connect.php';

// Check if event_id is set
if (!isset($_GET['event_id'])) {
    echo "No event selected.";
    exit();
}

$event_id = $_GET['event_id'];

// Fetch event details
$event_sql = "SELECT * FROM events WHERE id = ?";
$event_stmt = $conn->prepare($event_sql);
$event_stmt->bind_param("i", $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();

if ($event_result->num_rows === 0) {
    echo "Event not found.";
    exit();
}

$event = $event_result->fetch_assoc();

// Fetch bookings for the event
$booking_sql = "SELECT bookings.booking_date, users.name AS username, users.email FROM bookings 
                 JOIN users ON bookings.user_id = users.id 
                 WHERE bookings.event_id = ?";
$booking_stmt = $conn->prepare($booking_sql);
$booking_stmt->bind_param("i", $event_id);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings - <?php echo htmlspecialchars($event['title']); ?></title>
    <link rel="stylesheet" href="admin-dashboard.css">
</head>
<body>

    <nav>
        <h2>View Bookings</h2>
        <a href="admin-dashboard.php">Back to Dashboard</a>
    </nav>

    <h3>Bookings for "<?php echo htmlspecialchars($event['title']); ?>"</h3>

    <?php if ($booking_result->num_rows > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Booking Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($booking = $booking_result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$booking['username']}</td>
                            <td>{$booking['email']}</td>
                            <td>{$booking['booking_date']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No bookings found for this event.</p>
    <?php } ?>

</body>
</html>
