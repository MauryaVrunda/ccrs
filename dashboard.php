<?php
session_start();
include 'db.php'; // Include database connection

// Check if the citizen is logged in
if (!isset($_SESSION['user_id']) ) {
    header("Location: login.php");
    exit();
}

// Get citizen information from the session
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Initialize variables
$complaints = [];
$total_complaints = 0;
$notifications = [];

// Fetch complaints registered by the citizen
$sql = "SELECT id, description, status, created_at, updated_at, problem, ward, remarks, document_title, document_path 
        FROM complaints 
        WHERE user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Debugging: Check if the query executed correctly
    if (!$result) {
        echo "Error executing query: " . $conn->error;
        exit();
    }

    // Debugging: Check if any rows are returned
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $complaints[] = $row;
        }
        $total_complaints = count($complaints);
    } else {
        // No complaints found
        echo "No complaints found for user ID: " . htmlspecialchars($user_id);
    }
} else {
    // Debugging: Check if the statement prepared correctly
    echo "Error preparing statement: " . $conn->error;
    exit();
}

$total_complaints = count($complaints);

// Fetch notifications for changes made by employees
$notification_sql = "SELECT id, description, status, updated_at, remarks 
                     FROM complaints 
                     WHERE user_id = ? AND updated_at > DATE_SUB(NOW(), INTERVAL 1 DAY)"; // Changes in the last 24 hours
$notification_stmt = $conn->prepare($notification_sql);
$notification_stmt->bind_param("i", $user_id);
$notification_stmt->execute();
$notification_result = $notification_stmt->get_result();

while ($notification = $notification_result->fetch_assoc()) {
    $notifications[] = $notification;
}

// Close the database connections
$stmt->close();
$notification_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citizen Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .header {
            background-color: #34495e;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .welcome {
            font-size: 18px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #34495e;
            color: white;
        }
        .notification {
            background-color: #dff9fb;
            color: #34495e;
            border: 1px solid #c7ecee;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Citizen Dashboard</h1>
</div>

<div class="container">
    <p class="welcome">Welcome, <strong><?php echo htmlspecialchars($user_name); ?></strong>!</p>
    <p>You have registered a total of <strong><?php echo $total_complaints; ?></strong> complaints.</p>

    <!-- Notifications -->
    <?php if (!empty($notifications)): ?>
        <h3>Notifications:</h3>
        <?php foreach ($notifications as $notification): ?>
            <div class="notification">
                <p><strong>Complaint ID:</strong> <?php echo htmlspecialchars($notification['id']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($notification['description']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($notification['status']); ?></p>
                <p><strong>Remarks:</strong> <?php echo htmlspecialchars($notification['remarks']); ?></p>
                <p><strong>Updated At:</strong> <?php echo htmlspecialchars($notification['updated_at']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No recent updates on your complaints.</p>
    <?php endif; ?>

    <!-- Complaint Table -->
    <h3>Your Complaints:</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Status</th>
                <th>Problem</th>
                <th>Ward</th>
                <th>Remarks</th>
                <th>Document</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($complaints)): ?>
                <?php foreach ($complaints as $complaint): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($complaint['id']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['description']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['status']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['problem']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['ward']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['remarks']); ?></td>
                        <td>
                            <?php if (!empty($complaint['document_path'])): ?>
                                <a href="<?php echo htmlspecialchars($complaint['document_path']); ?>" target="_blank">View</a>
                            <?php else: ?>
                                No document
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($complaint['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['updated_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No complaints found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>