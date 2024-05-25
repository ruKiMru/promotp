<?php
include 'database.php';

// Получение данных из POST-запроса
$idIssue = $_POST['id_issue'];
$idStatus = $_POST['id_status'];

// Запрос на изменение статуса в таблице "issues"
$sql = "UPDATE issues SET id_status = $idStatus WHERE id_issue = $idIssue";

if ($conn->query($sql) === TRUE) {
    echo "Статус успешно изменен";
} else {
    echo "Ошибка при изменении статуса: " . $conn->error;
}

// Закрытие соединения
$conn->close();
?>
