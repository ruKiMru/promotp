<?php
include '../database.php';

// Установка кодировки соединения в UTF-8
mysqli_set_charset($conn, "utf8mb4");

// Обработка HTTP-запроса
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['contact_id'])) {
        $contactID = $_GET['contact_id'];

        $updateQuery = "SELECT * FROM messages WHERE contact_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $contactID);

        if ($updateStmt->execute()) {
            $result = $updateStmt->get_result();
            $messages = $result->fetch_all(MYSQLI_ASSOC);

            echo json_encode(["success" => true, "result" => $messages], JSON_UNESCAPED_UNICODE); 
        } else {
            echo json_encode(["success" => false, "error" => 'Ошибка выполнения запроса: ' . $conn->error], JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(["success" => false, "error" => 'Ошибка выполнения запроса: ' . $conn->error], JSON_UNESCAPED_UNICODE);
    }
} else {
    // Ошибка: отсутствуют необходимые параметры в GET-данных
    // Устанавливаем заголовок Content-Type для правильного отображения символов
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode(["success" => false, "error" => "Ошибка: Необходимые параметры отсутствуют в запросе"], JSON_UNESCAPED_UNICODE);
}

// Закрытие соединения с БД
$conn->close();
?>
