<?php
include 'database.php';
// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Запрос для получения данных из таблицы "marks"
$sql = "SELECT * FROM status";
$result = $conn->query($sql);

// Формирование выпадающего списка
$dropdownContent = '<select id="marksDropdown">';
while ($row = $result->fetch_assoc()) {
    $id = $row['id_status'];
    $name = $row['status_name'];
    $dropdownContent .= "<option value='$id'>$name</option>";
}
$dropdownContent .= '</select>';

// Закрытие соединения
$conn->close();

// Вывод HTML-кода с выпадающим списком
echo $dropdownContent;
?>
