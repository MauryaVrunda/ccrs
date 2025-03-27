<?php
include "db.php";

$name=$_POST['name'];
$user_id=$_POST['user_id'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$password=password_hash($_POST['password'],PASSWORD_DEFAULT);


$sql = "INSERT INTO users(name ,user_id, email , phone, password )
        VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql))
{
    die("SQL error:" .mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "sssss",
                        $name,
                        $user_id,
                        $email,
                        $phone,
                        $password);

if (mysqli_stmt_execute($stmt)) {
    echo "Successfully registered!";
} else {
    echo "Error: " . mysqli_error($conn);
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>