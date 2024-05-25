<?php
include 'database.php';

// Установка кодировки соединения в UTF-8
mysqli_set_charset($conn, "utf8");

// Получение данных из URL-строки
$contactId = isset($_GET['contactId']) ? intval($_GET['contactId']) : 0;
$senderId = isset($_GET['senderID']) ? intval($_GET['senderID']) : 0;
$message = isset($_GET['message']) ? $conn->real_escape_string($_GET['message']) : '';

// Проверка, что все необходимые данные получены
if ($contactId && $senderId && $message) {
    // SQL-запрос для вставки записи
    $sql = "INSERT INTO `messages` (`contact_id`, `sender_id`, `message`) VALUES ($contactId, $senderId, '$message')";

    // Выполнение запроса
    if ($conn->query($sql) === TRUE) {
        echo "Запись успешно добавлена";
    } else {
        echo "Ошибка при добавлении записи: " . $conn->error;
    }
} else {
    echo "Недостаточно данных для добавления записи";
}

// Закрытие соединения
$conn->close();
?>
