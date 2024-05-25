<?php
global $conn;
include '../../../database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $configuration = $conn->real_escape_string($_POST['configuration']);
    $equipment = isset($_POST['equipment']) ? $_POST['equipment'] : [];

    if (!empty($configuration) && !empty($equipment)) {
        // Вставка конфигурации
        $sql = "INSERT INTO `configurations` (`id`, `name`) VALUES (NULL,'$configuration')";
        if ($conn->query($sql) === TRUE) {
            $configuration_id = $conn->insert_id;
            $errors = [];
            // Вставка оборудования для конфигурации
            foreach ($equipment as $equip_id) {
                $equip_id = $conn->real_escape_string($equip_id);
                $sql = "INSERT INTO `config&equip` (`id`, `configuration_id`, `equipments_id`) VALUES (NULL, '$configuration_id', '$equip_id')";
                if (!$conn->query($sql)) {
                    $errors[] = "Ошибка при вводе идентификатора оборудования $equip_id: " . $conn->error;
                }
            }
            if (empty($errors)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Конфигурация успешно добавлена'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Ошибки при добавлении оборудования: ' . implode(', ', $errors)
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $conn->error
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
        'message' => 'Invalid request method'
    ]);
}

// Закрываем соединение
$conn->close();