<?php
include 'db.php'; // Include database connection

if (isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id']; // ID of the complaint to update
    $new_status = $_POST['status']; // The new status (e.g., "Resolved")

    // Update the status of the complaint
    $sql_update_status = "UPDATE complaints SET status = ? WHERE id = ?";
    $stmt_update_status = $conn->prepare($sql_update_status);
    if (!$stmt_update_status) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt_update_status->bind_param("si", $new_status, $complaint_id);
    $stmt_update_status->execute();

    if ($stmt_update_status->affected_rows > 0) {
        echo "Complaint status updated successfully.";

        // Optional: Log or notify for monitoring purposes
        if ($new_status === "Resolved") {
            echo "The complaint was marked as 'Resolved'. The database trigger will handle OTP generation.";
        }
    } else {
        echo "Error: Complaint status could not be updated.";
    }
}
?>
