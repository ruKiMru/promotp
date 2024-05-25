<?php
include 'database.php';

// Получаем данные из POST-запроса
// Получаем данные из POST-запроса
$first_name = $_POST['first_name'];
$middle_name = $_POST['middle_name'];
$last_name = $_POST['last_name'];
$company_name = $_POST['company_name'];
$contact_phone = $_POST['contact_phone'];
$email = $_POST['c_email']; // Изменили имя переменной
$inn = $_POST['c_inn']; // Изменили имя переменной


// Подготовка SQL-запроса для добавления клиента
$sql = "INSERT INTO clients (first_name, middle_name, last_name, company_name, contact_phone, email, inn) 
        VALUES ('$first_name', '$middle_name', '$last_name', '$company_name', '$contact_phone', '$email', '$inn')";

if ($conn->query($sql) === TRUE) {
    // В случае успешного добавления клиента отправляем сообщение об успехе
    echo "Клиент успешно добавлен!";
} else {
    // В случае ошибки отправляем сообщение об ошибке
    echo "Ошибка при добавлении клиента: " . $conn->error;
}

// Закрываем соединение с базой данных
$conn->close();
?>
