<?php
include 'database.php'; // Подключение к базе данных

// Проверка, были ли получены данные через POST запрос
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из POST запроса
    $company = $_POST['company'];
    $contact = $_POST['contact'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $inn = $_POST['inn'];

    // Подготовленный запрос для обновления данных клиента
    $sql = "UPDATE clients SET 
                company_name = '$company', 
                contact_phone = '$phone', 
                email = '$email', 
                inn = '$inn' 
            WHERE CONCAT(first_name, ' ', middle_name, ' ', last_name) = '$contact'";

    // Выполнение запроса к базе данных
    if ($conn->query($sql) === TRUE) {
        echo "Данные клиента успешно обновлены";
    } else {
        echo "Ошибка при обновлении данных клиента: " . $conn->error;
    }

    // Закрытие соединения с базой данных
    $conn->close();
}
?>
