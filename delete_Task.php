<?php
include 'database.php';

// Проверка, был ли передан идентификатор задачи в запросе
if (isset($_GET['id'])) {
    $taskId = $_GET['id'];

    // Запрос на удаление задачи и связанных с ней комментариев
    $deleteTaskQuery = "DELETE FROM issues WHERE id_issue = $taskId";
    $deleteCommentsQuery = "DELETE FROM comments WHERE id_issue = $taskId";

    // Выполнение запросов
    $conn->query($deleteCommentsQuery);
    $conn->query($deleteTaskQuery);
    
    // Закрытие соединения с базой данных
    $conn->close();
    
    // Возвращение успешного ответа (может быть использовано в AJAX)
    echo json_encode(['success' => true]);
} else {
    // Возвращение ошибочного ответа (может быть использовано в AJAX)
    echo json_encode(['success' => false, 'message' => 'Не передан идентификатор задачи']);
}
?>
