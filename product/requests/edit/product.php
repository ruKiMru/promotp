<?php
global $conn;
include '../../../database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idProduct = $_POST['idProduct'];
    $client = isset($_POST['client']) ? $_POST['client'] : [];
    $model = isset($_POST['model']) ? $_POST['model'] : [];
    $configuration = isset($_POST['configuration']) ? $_POST['configuration'] : [];
    $series = $_POST['series'];
    $ipAddress = $_POST['ipAddress'];
    $releaseDate = $_POST['releaseDate'];

    if (!empty($client) && !empty($model) && !empty($configuration) && !empty($series) && !empty($ipAddress) && !empty($releaseDate)) {
        $sql = "UPDATE products 
                SET client_id='$client', model_id='$model', configuration_id='$configuration', 
                series='$series', ip='$ipAddress', release_date='$releaseDate' 
                WHERE id='$idProduct'";

        if ($conn->query($sql) === TRUE) {
            echo json_encode([
                'success' => true,
                'message' => 'Успешно обновились данные'
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
