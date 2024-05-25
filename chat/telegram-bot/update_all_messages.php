<?php
include '../../database.php';

// Установка кодировки соединения в UTF-8
mysqli_set_charset($conn, "utf8mb4");

// Ваш токен API
$apiToken = "6837379910:AAGqmKcRpfzffzPttBbSWOE-elsxtQDDs-w";

// URL API для получения обновлений
$apiUrl = "https://api.telegram.org/bot$apiToken/getUpdates";

function getUpdates($apiUrl) {
    $response = file_get_contents($apiUrl);
    return json_decode($response, true);
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

// Читаем lastUpdateId из файла
$lastUpdateIdFile = 'last_update_id.txt';
if (file_exists($lastUpdateIdFile)) {
    $lastUpdateId = (int)file_get_contents($lastUpdateIdFile);
} else {
    $lastUpdateId = 0;
}

$updates = getUpdates($apiUrl . "?offset=" . ($lastUpdateId + 1));
if (isset($updates['result']) && count($updates['result']) > 0) {
    foreach ($updates['result'] as $update) {
        $lastUpdateId = $update['update_id'];
        file_put_contents($lastUpdateIdFile, $lastUpdateId);
        $chatId = $update['message']['chat']['id'];
        $username = $update['message']['chat']['username'];
        $message = $update['message']['text'];
        
        // Обработка команды /start
        if ($message == "/start") {
            $response = "Добро пожаловать! Здесь вы можете задать воспросы или проконсультироваться.";
            sendMessage($chatId, $response, $apiToken);
        } else {
            sendMessageWeb($username, $message, $chatId);
            sendMessage($chatId, 'Сообщение отправлено.', $apiToken);
        }
    }
}
sleep(1); // Задержка в 1 секунду перед следующим запросом

?>
