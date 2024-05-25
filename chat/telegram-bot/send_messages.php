<?php
include '../../database.php';

// Установка кодировки соединения в UTF-8
mysqli_set_charset($conn, "utf8mb4");

// Ваш токен API
$apiToken = "6837379910:AAGqmKcRpfzffzPttBbSWOE-elsxtQDDs-w";

// Обработка HTTP-запроса
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Получение данных из URL-строки
    $contactId = isset($_POST['contactId']) ? intval($_POST['contactId']) : 0;
    $senderId = isset($_POST['senderId']) ? intval($_POST['senderId']) : 0;
    $message = isset($_POST['message']) ? $conn->real_escape_string($_POST['message']) : '';

    // Проверка, что все необходимые данные получены
    if ($contactId && $senderId && $message) {
        // SQL-запрос для вставки записи
        $sql = "INSERT INTO `messages` (`contact_id`, `sender_id`, `message`) VALUES ($contactId, $senderId, '$message')";

        // Получаем информацию о клиенте
        $query = "SELECT * FROM clients WHERE id_client = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            error_log("Ошибка подготовки запроса: " . $conn->error);
            return false;
        }

        $chatId = 0;
        $stmt->bind_param('i', $contactId);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $client = $result->fetch_assoc();
                $chatId = $client['chat_id'];
            } else {
                echo "Клиент не найден.";
                return false;
            }
        } else {
            error_log("Ошибка выполнения запроса: " . $stmt->error);
            return false;
        }

        // Выполнение запроса
        if ($conn->query($sql) === TRUE) {
            sendMessage($chatId, $message, $apiToken);
            echo "Запись успешно добавлена";
            return true;
        } else {
            echo "Ошибка при добавлении записи: " . $conn->error;
            return false;
        }
    } else {
        echo "Недостаточно данных для добавления записи";
        return false;
    }
} else {
    // Ошибка: отсутствуют необходимые параметры в GET-данных
    // Устанавливаем заголовок Content-Type для правильного отображения символов
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode(["success" => false, "error" => "Ошибка: Необходимые параметры отсутствуют в запросе"], JSON_UNESCAPED_UNICODE);
}

function sendMessageWeb($username, $message, $chatId) {
    global $conn;
    global $apiToken;

    // Сначала проверим наличие пользователя и обновим chat_id, если необходимо
    if (!hasUsername($username, $chatId)) {
        sendMessage($chatId, "Обратитесь к администратору, чтобы он добавил ваш тег.", $apiToken);
        return false;
    }

    // Получаем информацию о клиенте
    $query = "SELECT * FROM clients WHERE username = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Ошибка подготовки запроса: " . $conn->error);
        return false;
    }

    $contactId = 0;
    $stmt->bind_param('s', $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $client = $result->fetch_assoc();
            $contactId = $client['id_client'];
        } 
    } else {
        error_log("Ошибка выполнения запроса: " . $stmt->error);
        return false;
    }

    // Записываем сообщение в базу данных
    $insertQuery = "INSERT INTO messages (contact_id, message) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);

    if (!$insertStmt) {
        error_log("Ошибка подготовки запроса для записи сообщения: " . $conn->error);
        return false;
    }

    $insertStmt->bind_param('is', $contactId, $message);

    if (!$insertStmt->execute()) {
        error_log("Ошибка выполнения запроса для записи сообщения: " . $insertStmt->error);
        $insertStmt->close();
        return false;
    }

    $insertStmt->close();
    return true;
}

function hasUsername($username, $chatId) {
    global $conn;
    $query = "SELECT * FROM clients WHERE username = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        error_log("Ошибка подготовки запроса: " . $conn->error);
        return false;
    }

    $stmt->bind_param('s', $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $client = $result->fetch_assoc();

            // Проверяем, установлен ли chat_id
            if (!isset($client['chat_id']) || empty($client['chat_id'])) {
                // Обновляем chat_id
                $updateQuery = "UPDATE clients SET chat_id = ? WHERE username = ?";
                $updateStmt = $conn->prepare($updateQuery);

                if (!$updateStmt) {
                    error_log("Ошибка подготовки запроса на обновление: " . $conn->error);
                    $stmt->close(); // Закрываем предыдущий statement перед возвратом
                    return false;
                }

                $updateStmt->bind_param('is', $chatId, $username);

                if (!$updateStmt->execute()) {
                    error_log("Ошибка выполнения запроса на обновление: " . $updateStmt->error);
                    $updateStmt->close();
                    $stmt->close(); // Закрываем предыдущий statement перед возвратом
                    return false;
                }

                $updateStmt->close();
            }

            $stmt->close();
            return true;
        } else {
            $stmt->close(); // Закрываем statement если пользователь не найден
            return false;
        }
    } else {
        error_log("Ошибка выполнения запроса: " . $stmt->error);
        $stmt->close(); // Закрываем statement перед возвратом
        return false;
    }
}

function sendMessage($chatId, $message, $apiToken) {
    $url = "https://api.telegram.org/bot$apiToken/sendMessage";
    $data = array(
        'chat_id' => $chatId,
        'text' => $message
    );
    $options = array(
        'http' => array(
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data),
        ),
    );
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}
?>
