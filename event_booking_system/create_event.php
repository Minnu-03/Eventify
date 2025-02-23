<?php
include 'includes/db.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $venue = $_POST['venue'];
    $available_seats = $_POST['available_seats'];

    $sql = "INSERT INTO events (title, description, date, venue, available_seats) 
            VALUES ('$title', '$description', '$date', '$venue', '$available_seats')";
    if ($conn->query($sql) === TRUE) {
        echo "Event created successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST">
    <input type="text" name="title" placeholder="Event Title" required><br>
    <textarea name="description" placeholder="Event Description" required></textarea><br>
    <input type="datetime-local" name="date" required><br>
    <input type="text" name="venue" placeholder="Venue" required><br>
    <input type="number" name="available_seats" placeholder="Available Seats" required><br>
    <button type="submit">Create Event</button>
</form>
