<?php
// Include database connection
include 'db.php'; // Adjust the path to your db_connect.php file

// Start the session
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $original_complaint_id = $_POST['original_complaint_id'];
    $citizen_name = $_POST['citizen_name'];
    $complaint_details = $_POST['complaint_details'];

    // Validate if `original_complaint_id` exists in the `complaints` table
    $check_query = "SELECT id FROM complaints WHERE id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("i", $original_complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If the original complaint exists, generate a unique `recomplaint_id`
        $recomplaint_id = "RE-" . $original_complaint_id . "-" . rand(1000, 9999);

        // Insert the recomplaint data into the `recomplaint` table
        $insert_query = "INSERT INTO recomplaint (recomplaint_id, original_complaint_id, citizen_name, complaint_details, status)
                         VALUES (?, ?, ?, ?, 'New')";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("siss", $recomplaint_id, $original_complaint_id, $citizen_name, $complaint_details);

        if ($insert_stmt->execute()) {
            echo "Recomplaint registered successfully! Your Recomplaint ID is: $recomplaint_id";
        } else {
            echo "Error: Could not register recomplaint. " . $conn->error;
        }

        // Close the insert statement
        $insert_stmt->close();
    } else {
        // If the original complaint does not exist, show an error
        echo "Error: The Original Complaint ID does not exist. Please check and try again.";
    }

    // Close the check query statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
