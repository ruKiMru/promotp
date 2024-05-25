<?php
global $conn;
include '../../../database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $model = $conn->real_escape_string($_POST['model']);
    $equipment = isset($_POST['equipment']) ? $_POST['equipment'] : [];
    $configuration = isset($_POST['configuration']) ? $_POST['configuration'] : [];

    if (!empty($model) && !empty($equipment) && !empty($configuration)) {
        // Вставка модели
        $sql = "INSERT INTO `models` (`id`, `name`) VALUES (NULL, '$model')";
        if ($conn->query($sql) === TRUE) {
            $model_id = $conn->insert_id;
            $errors = [];

            // Вставка оборудования для модели
            foreach ($equipment as $equip_id) {
                $equip_id = $conn->real_escape_string($equip_id);
                $sql = "INSERT INTO `model&equip` (`id`, `model_id`, `equipment_id`) VALUES (NULL, '$model_id', '$equip_id')";
                if (!$conn->query($sql)) {
                    $errors[] = "Ошибка при добавлении оборудования ID $equip_id: " . $conn->error;
                }
            }

            // Вставка конфигураций для модели
            foreach ($configuration as $config_id) {
                $config_id = $conn->real_escape_string($config_id);
                $sql = "INSERT INTO `model&config` (`id`, model_id, configuration_id) VALUES (NULL, '$model_id', '$config_id')";
                if (!$conn->query($sql)) {
                    $errors[] = "Ошибка при добавлении конфигурации ID $config_id: " . $conn->error;
                }
            }

            if (empty($errors)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Модель успешно добавлена'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Ошибки при добавлении: ' . implode(', ', $errors)
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка: ' . $conn->error
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Пустые поля при отправке запроса'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Неверный метод запроса'
    ]);
}

$conn->close();