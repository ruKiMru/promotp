<?php
include 'database.php';

// Получаем ID клиента из POST-запроса
$clientId = $_POST['clientId'];

// Подготавливаем SQL-запрос для удаления клиента
$sql = "UPDATE clients SET deleted=1 WHERE id_client = $clientId";

if ($conn->query($sql) === TRUE) {
    echo "Клиент успешно удален!";
} else {
    echo "Ошибка при удалении клиента: " . $conn->error;
}

// Закрываем соединение с базой данных
$conn->close();
?>
