<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'db.php'; // Include Database Connection
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST["complaint_id"];
    $status = $_POST["status"];

    // Fetch complaint details
    $sql = "SELECT email, name FROM complaints WHERE id = $complaint_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row["email"];
        $name = $row["name"];

        // Update status in the database
        $update_sql = "UPDATE complaints SET status = '$status' WHERE id = $complaint_id";
        if ($conn->query($update_sql) === TRUE) {
            // Send Email Notification
            $mail = new PHPMailer(true);
            try {
                // SMTP Configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'your-email@gmail.com'; // Replace with your email
                $mail->Password = 'your-email-password'; // Replace with your email password or app password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Email Details
                $mail->setFrom('your-email@gmail.com', 'CCRS Complaint System');
                $mail->addAddress($email, $name);
                $mail->Subject = "Complaint Status Updated - ID: $complaint_id";
                $mail->Body = "Hello $name,\n\nYour complaint status has been updated.\n\nComplaint ID: $complaint_id\nNew Status: $status\n\nYou can track the status on our website.\n\n- CCRS Team";

                $mail->send();
                echo "<script>alert('Status Updated & Email Sent!'); window.location='update_status.php';</script>";
            } catch (Exception $e) {
                echo "<script>alert('Status Updated but Email could not be sent.'); window.location='update_status.php';</script>";
            }
        } else {
            echo "Error updating status: " . $conn->error;
        }
    } else {
        echo "Complaint not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Complaint Status</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>

    <form action="update_status.php" method="POST">
        <h2>Update Complaint Status</h2>

        <label for="complaint_id">Complaint ID:</label>
        <input type="text" id="complaint_id" name="complaint_id" required>

        <label for="status">Select New Status:</label>
        <select id="status" name="status" required>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Resolved">Resolved</option>
            <option value="Rejected">Rejected</option>
        </select>

        <button type="submit">Update Status</button>
    </form>

</body>
</html>