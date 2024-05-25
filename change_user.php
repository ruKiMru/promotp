<?php
include 'database.php';

// Получение данных из POST-запроса
$idIssue = $_GET['id_issue'];
$idUser = $_GET['id_user'];

// Запрос на изменение статуса в таблице "issues"
$sql = "UPDATE issues SET id_user = $idUser WHERE id_issue = $idIssue";

if ($conn->query($sql) === TRUE) {
    echo "Исполнитель успешно изменен";
} else {
    echo "Ошибка при изменении статуса: " . $conn->error;
}

// Закрытие соединения
$conn->close();
?>
