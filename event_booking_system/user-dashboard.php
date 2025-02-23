<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

include 'connect.php';

$sql = "SELECT * FROM events WHERE available_seats > 0";
$result = $conn->query($sql);

if (isset($_POST['register_event'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    $check_sql = "SELECT * FROM bookings WHERE user_id = ? AND event_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "You are already registered for this event!";
    } else {
        $register_sql = "INSERT INTO bookings (user_id, event_id, booking_date) VALUES (?, ?, NOW())";
        $register_stmt = $conn->prepare($register_sql);
        $register_stmt->bind_param("ii", $user_id, $event_id);
        
        $update_seats_sql = "UPDATE events SET available_seats = available_seats - 1 WHERE id = ?";
        $update_seats_stmt = $conn->prepare($update_seats_sql);
        $update_seats_stmt->bind_param("i", $event_id);

        if ($register_stmt->execute() && $update_seats_stmt->execute()) {
            echo "Successfully registered for the event!";
        } else {
            echo "Error registering for the event.";
        }

        $register_stmt->close();
        $update_seats_stmt->close();
    }

    $check_stmt->close();
}

if (isset($_POST['cancel_registration'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    $cancel_sql = "DELETE FROM bookings WHERE user_id = ? AND event_id = ?";
    $cancel_stmt = $conn->prepare($cancel_sql);
    $cancel_stmt->bind_param("ii", $user_id, $event_id);

    $update_seats_sql = "UPDATE events SET available_seats = available_seats + 1 WHERE id = ?";
    $update_seats_stmt = $conn->prepare($update_seats_sql);
    $update_seats_stmt->bind_param("i", $event_id);

    if ($cancel_stmt->execute() && $update_seats_stmt->execute()) {
        echo "Registration cancelled successfully!";
    } else {
        echo "Error cancelling registration.";
    }

    $cancel_stmt->close();
    $update_seats_stmt->close();
}

$conn->close();
?>

<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

include 'connect.php';

$sql = "SELECT * FROM events WHERE available_seats > 0";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Eventify</title>
    <link rel="stylesheet" href="user-dashboard.css">
</head>
<body>

    <nav>
        <h2>Book Events</h2>
        <a href="logout.php">Logout</a>
    </nav>

    <h3>Available Events</h3>
    <table>
        <thead>
            <tr>
                <th>Event Title</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Available Seats</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($event = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$event['title']}</td>
                            <td>{$event['date']}</td>
                            <td>{$event['venue']}</td>
                            <td>{$event['available_seats']}</td>
                            <td>";
                    
                    if ($event['available_seats'] > 0) {
                        echo "<form method='POST'>
                                <input type='hidden' name='event_id' value='{$event['id']}'>
                                <button type='submit' name='register_event'>Register</button>
                            </form>";
                    } else {
                        echo "<p>Sold Out</p>";
                    }
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No events available at the moment.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>
