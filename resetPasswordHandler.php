<?php
include 'database.php';

// Получение данных из AJAX-запроса
$userId = $_POST['userId'];
$newPassword = $_POST['newPassword'];

$hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

// Обновление пароля пользователя в базе данных
$update_password_sql = "UPDATE users SET password = '$hashed_password' WHERE ID = '$userId'";

if ($conn->query($update_password_sql) === TRUE) {
    echo "success";
} else {
    echo "Ошибка при изменении пароля пользователя: " . $conn->error;
}

$conn->close();
?>
