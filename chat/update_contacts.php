<?php
include '../database.php';

// Установка кодировки соединения в UTF-8
mysqli_set_charset($conn, "utf8");

// Обработка HTTP-запроса
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search_parametrs = isset($_GET['search_parametrs']) ? $_GET['search_parametrs'] : '';

    $updateQuery = "SELECT DISTINCT contact_id, clients.* 
                    FROM messages 
                    JOIN clients ON messages.contact_id = clients.id_client 
                    WHERE clients.last_name LIKE CONCAT('%', ?, '%') 
                       OR clients.first_name LIKE CONCAT('%', ?, '%') 
                       OR clients.middle_name LIKE CONCAT('%', ?, '%') 
                    ORDER BY contact_id DESC";

    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('sss', $search_parametrs, $search_parametrs, $search_parametrs);

    if ($updateStmt->execute()) {
        $result = $updateStmt->get_result();
        $contacts = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode(["success" => true, "result" => $contacts], JSON_UNESCAPED_UNICODE);
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
