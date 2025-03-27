<?php
include "db.php";

    $user_id = $_POST['user_id'];
    $department = $_POST['department'];
    $problem = $_POST['problem'];
    $description = $_POST['description'];
    $remarks = $_POST['remarks'];
    $ward = $_POST['ward'];
    $file_name = "";

$sql = "INSERT INTO complaints(user_id, department , problem , description, remarks , ward )
        VALUES ( ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql))
{
    die("SQL error:" .mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "ssssss",
                        $user_id,
                        $department,
                        $problem,
                        $description,
                        $remarks,
                        $ward );
                       
if (mysqli_stmt_execute($stmt)) {
    echo "Successfully registered!";
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

