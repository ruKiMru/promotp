<?php
include 'database.php';

// Получение данных из AJAX-запроса
$userId = $_POST['userId'];

// Проверка наличия связанных записей в других таблицах
$sql_check_issues = "SELECT * FROM issues WHERE id_user = $userId";
$sql_check_messages = "SELECT * FROM messages WHERE sender_id = $userId";

$result_issues = $conn->query($sql_check_issues);
$result_messages = $conn->query($sql_check_messages);

// Если есть связанные записи в таблицах issues или messages, отменяем удаление
if ($result_issues->num_rows > 0 || $result_messages->num_rows > 0) {
    echo "Ошибка: Невозможно удалить пользователя, так как он связан с другими таблицами.";
} else {
    // Запрос к базе данных для удаления пользователя
    $deleteUserSql = "DELETE FROM users WHERE ID = $userId";

    if ($conn->query($deleteUserSql) === TRUE) {
        echo "success";
    } else {
        echo "Ошибка при удалении пользователя: " . $conn->error;
    }
}

$conn->close();
?>
