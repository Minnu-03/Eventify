<?php

error_reporting(E_ALL);  // Show all errors
ini_set('display_errors', 1);  // Display errors in the browser

session_start();

// Check if user is logged in and if they are an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.html");
    exit();
}

// Create New Event
if (isset($_POST['create_event'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $venue = $_POST['venue'];
    $available_seats = $_POST['available_seats'];

    include 'connect.php'; // Database connection

    $sql = "INSERT INTO events (title, date, description, venue, available_seats) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $date, $description, $venue, $available_seats);

    if ($stmt->execute()) {
        echo "Event created successfully!";
    } else {
        echo "Error creating event: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

// Fetch Events
include 'connect.php'; // Include database connection

$sql = "SELECT * FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Eventify</title>
    <link rel="stylesheet" href="admin-dashboard.css">
</head>
<body>

    <nav>
        <h2>Admin Dashboard</h2> <!-- Admin Dashboard text inside the navbar -->
        <a href="admin-dashboard.php?logout=true">Logout</a> <!-- Logout link aligned to the right -->
    </nav>

    <!-- Form to create a new event -->
    <h3>Create New Event</h3>
    <form method="POST" action="admin-dashboard.php">
        <label for="title">Event Title:</label>
        <input type="text" name="title" id="title" required><br>

        <label for="date">Event Date:</label>
        <input type="datetime-local" name="date" id="date" required><br>

        <label for="description">Event Description:</label>
        <textarea name="description" id="description" required></textarea><br>

        <label for="venue">Event Venue:</label>
        <input type="text" name="venue" id="venue" required><br>

        <label for="available_seats">Available Seats:</label>
        <input type="number" name="available_seats" id="available_seats" required><br>

        <button type="submit" name="create_event">Create Event</button>
    </form>

    <h3>Manage Events</h3>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Available Seats</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($event = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$event['title']}</td>
                        <td>{$event['date']}</td>
                        <td>{$event['venue']}</td>
                        <td>{$event['available_seats']}</td>
                        <td>{$event['created_at']}</td>
                        <td>
                            <a href='edit-event.php?edit_event_id={$event['id']}'>Edit</a> |
                            <a href='admin-dashboard.php?delete_event_id={$event['id']}'>Delete</a> |
                            <a href='view-bookings.php?event_id={$event['id']}'>View Bookings</a> <!-- Updated link -->
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Event deletion
    if (isset($_GET['delete_event_id'])) {
        $event_id = $_GET['delete_event_id'];
        $delete_sql = "DELETE FROM events WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $event_id);

        if ($delete_stmt->execute()) {
            echo "Event deleted successfully!";
        } else {
            echo "Error deleting event: " . $delete_stmt->error;
        }

        $delete_stmt->close();
        $conn->close();
    }
    ?>

</body>
</html>
