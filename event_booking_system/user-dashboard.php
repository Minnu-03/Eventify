<?php
session_start();

// Check if the user is logged in and is a 'user'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

include 'connect.php';

// Fetch available events
$sql = "SELECT * FROM events WHERE available_seats > 0";
$result = $conn->query($sql);

$message = "";

// Handle event registration
if (isset($_POST['register_event'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    // Check if user is already registered for the event
    $check_sql = "SELECT * FROM bookings WHERE user_id = ? AND event_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "You are already registered for this event!";
    } else {
        // Register the user for the event
        $register_sql = "INSERT INTO bookings (user_id, event_id, booking_date) VALUES (?, ?, NOW())";
        $register_stmt = $conn->prepare($register_sql);
        $register_stmt->bind_param("ii", $user_id, $event_id);
        $register_stmt->execute();

        // Decrease available seats
        $update_seats_sql = "UPDATE events SET available_seats = available_seats - 1 WHERE id = ?";
        $update_seats_stmt = $conn->prepare($update_seats_sql);
        $update_seats_stmt->bind_param("i", $event_id);
        $update_seats_stmt->execute();

        $message = "Registration successful!";
    }
}

if (isset($_POST['cancel_registration'])) {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    $cancel_sql = "DELETE FROM bookings WHERE user_id = ? AND event_id = ?";
    $cancel_stmt = $conn->prepare($cancel_sql);
    $cancel_stmt->bind_param("ii", $user_id, $event_id);
    $cancel_stmt->execute();

    $update_seats_sql = "UPDATE events SET available_seats = available_seats + 1 WHERE id = ?";
    $update_seats_stmt = $conn->prepare($update_seats_sql);
    $update_seats_stmt->bind_param("i", $event_id);
    $update_seats_stmt->execute();

    $message = "Your registration has been canceled.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Eventify</title>
    <link rel="stylesheet" href="user-dashboard.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var message = "<?php echo $message; ?>";
            if (message !== "") {
                showPopup(message);
            }
        });

        function showPopup(msg) {
            let popup = document.createElement("div");
            popup.style.position = "fixed";
            popup.style.top = "50%";
            popup.style.left = "50%";
            popup.style.transform = "translate(-50%, -50%)";
            popup.style.background = "white";
            popup.style.padding = "30px"; 
            popup.style.padding = "20px";
            popup.style.borderRadius = "10px";
            popup.style.boxShadow = "0px 0px 10px rgba(0,0,0,0.2)";
            popup.style.zIndex = "1000";
            popup.innerHTML = `<p>${msg}</p><button onclick="this.parentElement.remove()">OK</button>`;

            document.body.appendChild(popup);
        }
    </script>
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

                    $user_id = $_SESSION['user_id'];
                    $check_registration_sql = "SELECT * FROM bookings WHERE user_id = ? AND event_id = ?";
                    $check_registration_stmt = $conn->prepare($check_registration_sql);
                    $check_registration_stmt->bind_param("ii", $user_id, $event['id']);
                    $check_registration_stmt->execute();
                    $check_registration_result = $check_registration_stmt->get_result();

                    if ($check_registration_result->num_rows > 0) {
                        echo "<form method='POST'>
                                <input type='hidden' name='event_id' value='{$event['id']}'>
                                <button type='submit' name='cancel_registration'>Cancel Registration</button>
                              </form>";
                    } elseif ($event['available_seats'] > 0) {
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
