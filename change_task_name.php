<?php
include 'database.php';

// Получение данных из POST-запроса
$idIssue = $_GET['id_issue'];
$name = $_GET['name'];

// Запрос на изменение статуса в таблице "issues"
$sql = "UPDATE issues SET name = $name WHERE id_issue = $idIssue";

if ($conn->query($sql) === TRUE) {
    echo "Имя задачи успешно изменено";
} else {
    echo "Ошибка при изменении имени задачи: " . $conn->error;
}

// Закрытие соединения
$conn->close();
?>