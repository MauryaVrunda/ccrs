<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST["complaint_id"];

    $sql = "SELECT * FROM complaints WHERE user_id = $complaint_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status = $row["status"];
        $department = $row["department"];
        $problem = $row["problem"];

        echo "<h2>Complaint Status</h2>";
        echo "Complaint ID: " . $row["user_id"] . "<br>";
        echo "Department: " . $department . "<br>";
        echo "Problem: " . $problem . "<br>";
        echo "Status: <strong>" . $status . "</strong><br>";

        if (!empty($row["document"])) {
            echo "Attached Document: <a href='uploads/" . $row["document"] . "' target='_blank'>View Document</a><br>";
        }
    } else {
        echo "<h3>Complaint not found.</h3>";
    }
}
?>

