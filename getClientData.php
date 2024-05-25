<?php
include 'database.php';

// Получение clientId из параметров запроса
$clientId = $_GET['clientId'];

// SQL запрос для получения данных о клиенте по ID
$sql = "SELECT * FROM clients WHERE id_client = $clientId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Преобразуем данные о клиенте в ассоциативный массив и выводим его в формате JSON
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo "0 results";
}
$conn->close();
?>
