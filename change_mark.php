<?php
include 'database.php';

// Получение данных из POST-запроса
$idIssue = $_GET['id_issue'];
$idMark = $_GET['id_mark'];

// Запрос на изменение статуса в таблице "issues"
$sql = "UPDATE issues SET id_mark = $idMark WHERE id_issue = $idIssue";

if ($conn->query($sql) === TRUE) {
    echo "Отметка успешно изменена";
} else {
    echo "Ошибка при изменении отметки: " . $conn->error;
}

// Закрытие соединения
$conn->close();
?>
