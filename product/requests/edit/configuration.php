<?php
global $conn;
include '../../../database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $configurationId = $_POST['idConfig'];
    $configuration = $conn->real_escape_string($_POST['configuration']);
    $equipment = isset($_POST['equipment']) ? $_POST['equipment'] : [];

    if (!empty($configuration) && !empty($equipment)) {
        $deleteSql = "DELETE FROM `config&equip` WHERE configuration_id='$configurationId'";
        if ($conn->query($deleteSql) === TRUE) {
            $updateSql = "UPDATE configurations SET name='$configuration' WHERE id='$configurationId'";
            if ($conn->query($updateSql) === TRUE) {
                $errors = [];
                foreach ($equipment as $equip_id) {
                    $equip_id = $conn->real_escape_string($equip_id);
                    $insertSql = "INSERT INTO `config&equip` (`configuration_id`, `equipments_id`) VALUES ('$configurationId', '$equip_id')";
                    if (!$conn->query($insertSql)) {
                        $errors[] = "Ошибка при вводе идентификатора оборудования $equip_id: " . $conn->error;
                    }
                }
                if (empty($errors)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Конфигурация успешно обновлена'
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
                    'message' => 'Ошибка при обновлении конфигурации: ' . $conn->error
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

$conn->close();

