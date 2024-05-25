<?php
include 'database.php';

// Получение данных из POST-запроса
$idIssue = $_GET['id_issue'];
$date = $_GET['date'];

// Запрос на изменение статуса в таблице "issues"
$sql = "UPDATE issues SET completion_time = '$date' WHERE id_issue = $idIssue";

if ($conn->query($sql) === TRUE) {
    echo "Дата успешно изменена";
} else {
    echo "Ошибка при изменении даты: " . $conn->error;
}

// Закрытие соединения
$conn->close();
?>
