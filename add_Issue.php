<?php
include 'database.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получаем ID пользователя из куки
if(isset($_COOKIE['ID'])) {
    $id_user = mysqli_real_escape_string($conn, $_COOKIE['ID']);
} else {
    // Если ID пользователя не найден в куки, выполнение скрипта прерывается
    exit("ID пользователя не найден");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_status = 1;
    $id_mark = 3;
   
    $id_client = mysqli_real_escape_string($conn, $_POST['id_client']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description_issue']);
    
    $sql = "INSERT INTO issues (id_status, id_user, id_client, id_mark, name, Description) 
            VALUES ('$id_status', '$id_user', '$id_client','$id_mark', '$name', '$description')";

    if ($conn->query($sql) === TRUE) {
        header("Location: Tasks.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
