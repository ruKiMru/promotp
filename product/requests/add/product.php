<?php
global $conn;
include '../../../database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client = isset($_POST['client']) ? $_POST['client'] : [];
    $model = isset($_POST['model']) ? $_POST['model'] : [];
    $configuration = isset($_POST['configuration']) ? $_POST['configuration'] : [];
    $series = $_POST['series'];
    $ipAddress = $_POST['ipAddress'];
    $releaseDate = $_POST['releaseDate'];

    if (!empty($client) && !empty($model) && !empty($configuration) && !empty($series) && !empty($ipAddress) && !empty($releaseDate)) {
        $sql = "INSERT INTO `products`
        (id,`client_id`, `model_id`, `configuration_id`, `series`, `ip`, `release_date`)
        VALUES (NULL, '$client', '$model', '$configuration', '$series', '$ipAddress', '$releaseDate')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode([
                'success' => true,
                'message' => 'Продукт успешно добавлен'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при добавлении продукта: ' . $conn->error
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Пожалуйста, заполните все поля'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Неверный метод запроса'
    ]);
}

// Закрываем соединение
$conn->close();
