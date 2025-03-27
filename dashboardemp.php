<?php
include 'db.php';
session_start();

// Check session variables
if (!isset($_SESSION['department'])) {
    header("Location: login.php");
    exit();
}

$department_name = $_SESSION['department'];

// Fetch all complaints for the department
$sql = "SELECT id, user_id, description, status, created_at, updated_at, problem, ward, remarks, document_title, document_path 
        FROM complaints 
        WHERE department = ?";
$stmt = $conn->prepare($sql);



$stmt->bind_param("s", $department_name);
$stmt->execute();
$result = $stmt->get_result();




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <h2>Department: <?php echo htmlspecialchars($department_name); ?></h2>
        </div>
        <div class="main-content">
            <h1>Welcome to the <?php echo htmlspecialchars($department_name); ?> Dashboard</h1>
            <!-- Complaints Table -->
            <h2>Complaint Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Problem</th>
                        <th>Ward</th>
                        <th>Remarks</th>
                        <th>Document Title</th>
                        <th>Document Path</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                                <td><?php echo htmlspecialchars($row['problem']); ?></td>
                                <td><?php echo htmlspecialchars($row['ward']); ?></td>
                                <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                                <td><?php echo htmlspecialchars($row['document_title']); ?></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($row['document_path']); ?>" target="_blank">View</a>
                                </td>
                                <td>
                                    <a href="edit_complaint.php?id=<?php echo $row['id']; ?>">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12">No complaints found for the <?php echo htmlspecialchars($department_name); ?> department.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Recomplaints Table -->
            <h2>Recomplaint Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Recomplaint ID</th>
                        <th>Original Complaint ID</th>
                        <th>Citizen Name</th>
                        <th>Recomplaint Details</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_recomplaints->num_rows > 0): ?>
                        <?php while ($row = $result_recomplaints->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['recomplaint_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['original_complaint_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['citizen_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['complaint_details']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">No recomplaints found for the <?php echo htmlspecialchars($department_name); ?> department.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
// Close statements and connections
$stmt->close();
$stmt_recomplaints->close();
$conn->close();
?>
