<?php
include 'db.php';
session_start();

if (!isset($_SESSION['department']) || !isset($_GET['id'])) {
    header("Location: dashboardemp.php");
    exit();
}

$complaint_id = $_GET['id'];
$department_name = $_SESSION['department'];

// Fetch the complaint details
$sql = "SELECT * FROM complaints WHERE id = ? AND department = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $complaint_id, $department_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Complaint not found.";
    exit();
}

$complaint = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];
    $document_path = $complaint['document_path']; // Keep the old document path by default

    // Handle document upload
    if (!empty($_FILES['document']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['document']['name']);
        move_uploaded_file($_FILES['document']['tmp_name'], $target_file);
        $document_path = $target_file;
    }

    // Update the complaint details
    $update_sql = "UPDATE complaints SET status = ?, remarks = ?, document_path = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $status, $remarks, $document_path, $complaint_id);
    $update_stmt->execute();

    header("Location: dashboardemp.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Complaint</title>
</head>
<body>
    <h1>Edit Complaint ID: <?php echo $complaint['id']; ?></h1>
    <form method="post" enctype="multipart/form-data">
        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Pending" <?php echo $complaint['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Ongoing" <?php echo $complaint['status'] == 'Ongoing' ? 'selected' : ''; ?>>Ongoing</option>
            <option value="Resolved" <?php echo $complaint['status'] == 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
        </select>
        <br><br>
        <label for="remarks">Remarks:</label>
        <textarea id="remarks" name="remarks"><?php echo htmlspecialchars($complaint['remarks']); ?></textarea>
        <br><br>
        <label for="document">Upload New Document:</label>
        <input type="file" id="document" name="document">
        <br><br>
        <button type="submit">Update Complaint</button>
    </form>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>