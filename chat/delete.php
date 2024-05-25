<?php
include 'database.php';

// Установка кодировки соединения в UTF-8
mysqli_set_charset($conn, "utf8");

// Обработка HTTP-запроса
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Проверка наличия параметров id_messages и sender_id в GET-данных
    if (isset($_GET['id_messages']) && isset($_GET['sender_id'])) {
        $messageID = $_GET['id_messages'];
        $senderID = $_GET['sender_id'];

        // Подготовка SQL-запроса для проверки авторства сообщения
        $checkQuery = "SELECT * FROM messages WHERE id_messages = ? AND sender_id = ?";
        $checkStmt = $conn->prepare($checkQuery);

        // Проверка успешности подготовленного запроса
        if ($checkStmt === false) {
            die("Ошибка подготовки запроса: " . $conn->error);
        }

        $checkStmt->bind_param("ii", $messageID, $senderID);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        // Проверка успешности выполнения запроса
        if ($result === false) {
            die("Ошибка выполнения запроса: " . $conn->error);
        }

        // Проверка авторства сообщения
        if ($result->num_rows > 0) {
            // Авторство подтверждено, выполняем удаление
            $deleteQuery = "UPDATE messages SET deleted = 1 WHERE id_messages = ?";
            $deleteStmt = $conn->prepare($deleteQuery);

            // Проверка успешности подготовленного запроса
            if ($deleteStmt === false) {
                die("Ошибка подготовки запроса: " . $conn->error);
            }

            $deleteStmt->bind_param("i", $messageID);
            $deleteStmt->execute();

            // Проверка успешности выполнения запроса
            if ($deleteStmt === false) {
                die("Ошибка выполнения запроса: " . $conn->error);
            }

            // Устанавливаем заголовок Content-Type для правильного отображения символов
            header('Content-Type: application/json; charset=utf-8');
            
            echo json_encode(["success" => true, "message" => "Сообщение успешно удалено"], JSON_UNESCAPED_UNICODE);
        } else {
            // Ошибка: пользователь не является автором сообщения
            // Устанавливаем заголовок Content-Type для правильного отображения символов
            header('Content-Type: application/json; charset=utf-8');
            
            echo json_encode(["success" => false, "error" => "Ошибка: Вы не являетесь автором сообщения"], JSON_UNESCAPED_UNICODE);
        }

        // Закрытие подготовленных запросов
        $checkStmt->close();
        
        // Проверка, был ли deleteStmt инициализирован перед закрытием
        if (isset($deleteStmt)) {
            $deleteStmt->close();
        }
    } else {
        // Ошибка: отсутствуют необходимые параметры в GET-данных
        // Устанавливаем заголовок Content-Type для правильного отображения символов
        header('Content-Type: application/json; charset=utf-8');
        
        echo json_encode(["success" => false, "error" => "Ошибка: Необходимые параметры отсутствуют в запросе"], JSON_UNESCAPED_UNICODE);
    }
}

// Закрытие соединения с БД
$conn->close();
?>
