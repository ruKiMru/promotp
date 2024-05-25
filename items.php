<?php
include 'database.php';

// Получение параметров из URL
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$count = isset($_GET['count']) ? intval($_GET['count']) : 10; // По умолчанию 10 записей на странице

$offset = $page * 100;

// Проверка наличия параметра поиска
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Проверка параметров фильтрации
$filterField = isset($_GET['field']) ? $_GET['field'] : '';
$filterValue = isset($_GET['filter']) ? $_GET['filter'] : '';

// Список полей, доступных для фильтрации
$allowedFilterFields = ['status', 'name']; // Добавьте другие поля по необходимости

// Проверка наличия параметра фильтрации в списке допустимых полей
if ($filterField && in_array($filterField, $allowedFilterFields)) {
    $filterQuery = " AND $filterField = '$filterValue'";
} else {
    $filterQuery = '';
}

$sql = "(
    SELECT id_client as id, CONCAT(first_name, ' ', last_name) as name, deleted FROM clients WHERE deleted = 0 AND CONCAT(first_name, ' ', last_name) LIKE '%$search%'
)
UNION (
    SELECT id_issue as id, name, deleted FROM issues WHERE deleted = 0 AND name LIKE '%$search%'
)
UNION (
    SELECT contact_id as id, message as name, deleted FROM messages WHERE deleted = 0 AND message LIKE '%$search%'
)
UNION (
    SELECT ID as id, CONCAT(first_name, ' ', last_name) as name, deleted FROM users WHERE deleted = 0 AND CONCAT(first_name, ' ', last_name) LIKE '%$search%'
)
LIMIT $offset, $count";


$result = $conn->query($sql);

// Обработка результата запроса
$items = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

// Вывод результата в формате JSON
header('Content-Type: application/json');
echo json_encode($items, JSON_UNESCAPED_UNICODE);

// Закрытие соединения с БД
$conn->close();
?>
