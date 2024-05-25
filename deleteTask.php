<?php
include 'database.php';

// Проверяем, был ли получен POST-запрос
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем id задачи из тела запроса
    $taskId = json_decode(file_get_contents('php://input'), true)['taskId'];

    
    $deleteSql = "DELETE FROM issues WHERE id = $taskId";
    $result = $conn->query($deleteSql);

    // Возвращаем ответ клиенту (пример)
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ошибка при удалении задачи']);
    }
} else {
    // Возвращаем ошибку, если запрос не был POST
    echo json_encode(['success' => false, 'message' => 'Некорректный запрос']);
}
?>
