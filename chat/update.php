<?php
include 'database.php';

// Установка кодировки соединения в UTF-8
mysqli_set_charset($conn, "utf8");

// Обработка HTTP-запроса
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Проверка наличия параметров id_messages, sender_id и new_message в GET-данных
    if (isset($_GET['id_messages']) && isset($_GET['sender_id']) && isset($_GET['new_message'])) {
        $messageID = $_GET['id_messages'];
        $senderID = $_GET['sender_id'];
        $newMessage = $_GET['new_message'];

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
            // Авторство подтверждено, выполняем обновление сообщения
            $updateQuery = "UPDATE messages SET message = ? WHERE id_messages = ?";
            $updateStmt = $conn->prepare($updateQuery);

            // Проверка успешности подготовленного запроса
            if ($updateStmt === false) {
                die("Ошибка подготовки запроса: " . $conn->error);
            }

            $updateStmt->bind_param("si", $newMessage, $messageID);
            $updateStmt->execute();

            // Проверка успешности выполнения запроса
            if ($updateStmt === false) {
                die("Ошибка выполнения запроса: " . $conn->error);
            }

            // Устанавливаем заголовок Content-Type для правильного отображения символов
            header('Content-Type: application/json; charset=utf-8');

            echo json_encode(["success" => true, "message" => "Сообщение успешно обновлено"], JSON_UNESCAPED_UNICODE);

            // Закрытие подготовленных запросов
            $checkStmt->close();
            $updateStmt->close();
        } else {
            // Ошибка: пользователь не является автором сообщения
            // Устанавливаем заголовок Content-Type для правильного отображения символов
            header('Content-Type: application/json; charset=utf-8');

            echo json_encode(["success" => false, "error" => "Ошибка: Вы не являетесь автором сообщения"], JSON_UNESCAPED_UNICODE);
            
            // Закрытие подготовленных запросов
            $checkStmt->close();
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
