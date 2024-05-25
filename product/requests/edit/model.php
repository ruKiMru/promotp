<?php
global $conn;
include '../../../database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $modelId = $_POST['idModel'];
    $modelName = $conn->real_escape_string($_POST['model']);
    $equipment = isset($_POST['equipment']) ? $_POST['equipment'] : [];
    $configuration = isset($_POST['configuration']) ? $_POST['configuration'] : [];

    if (!empty($modelName) && !empty($equipment) && !empty($configuration)) {
        // Удаление всех записей оборудования и конфигураций для данной модели
        $deleteEquipmentsSql = "DELETE FROM `model&equip` WHERE model_id='$modelId'";
        $deleteConfigurationsSql = "DELETE FROM `model&config` WHERE model_id='$modelId'";

        if ($conn->query($deleteEquipmentsSql) === TRUE && $conn->query($deleteConfigurationsSql) === TRUE) {
            // Обновление имени модели
            $updateModelSql = "UPDATE models SET name='$modelName' WHERE id='$modelId'";
            if ($conn->query($updateModelSql) === TRUE) {
                $errors = [];

                // Вставка нового оборудования
                foreach ($equipment as $equip_id) {
                    $equip_id = $conn->real_escape_string($equip_id);
                    $insertEquipSql = "INSERT INTO `model&equip` (`model_id`, `equipment_id`) VALUES ('$modelId', '$equip_id')";
                    if (!$conn->query($insertEquipSql)) {
                        $errors[] = "Ошибка при добавлении оборудования с ID $equip_id: " . $conn->error;
                    }
                }

                // Вставка новых конфигураций
                foreach ($configuration as $config_id) {
                    $config_id = $conn->real_escape_string($config_id);
                    $insertConfigSql = "INSERT INTO `model&config` (`model_id`, `configuration_id`) VALUES ('$modelId', '$config_id')";
                    if (!$conn->query($insertConfigSql)) {
                        $errors[] = "Ошибка при добавлении конфигурации с ID $config_id: " . $conn->error;
                    }
                }

                if (empty($errors)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Модель успешно обновлена'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Ошибки при добавлении оборудования или конфигураций: ' . implode(', ', $errors)
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Ошибка при обновлении модели: ' . $conn->error
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Ошибка при удалении старых записей: ' . $conn->error
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

// Закрываем соединение
$conn->close();