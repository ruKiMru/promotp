<?php
include 'database.php';

// Проверка, является ли запрос POST-запросом
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Получаем данные из POST-запроса
        $idIssue = $_POST["id_issue"];
        $comment = $_POST["comment"];

        // Проверяем, существует ли задача с указанным id_issue в таблице issues
        $checkIssueQuery = "SELECT id_issue FROM issues WHERE id_issue = ?";
        $stmtCheckIssue = $conn->prepare($checkIssueQuery);
        $stmtCheckIssue->bind_param("i", $idIssue);
        $stmtCheckIssue->execute();
        $stmtCheckIssue->store_result();
        $issueCount = $stmtCheckIssue->num_rows;
        $stmtCheckIssue->close();

        if ($issueCount > 0) {
            // Задача существует, можно вставлять комментарий
            $sqlInsertComment = "INSERT INTO comments (id_issue, comment, creation_date) VALUES (?, ?, NOW())";
            $stmt = $conn->prepare($sqlInsertComment);

            if ($stmt) {
                $stmt->bind_param("is", $idIssue, $comment);

                if ($stmt->execute()) {
                    echo json_encode(array("status" => "success"));
                } else {
                    throw new Exception("Ошибка при выполнении запроса: " . $stmt->error);
                }

                $stmt->close();
            } else {
                throw new Exception("Ошибка при подготовке запроса: " . $conn->error);
            }
        } else {
            // Задача не существует, возвращаем ошибку с информацией об отсутствующей задаче
            echo json_encode(array("status" => "error", "message" => "Задача с id_issue $idIssue не существует."));
        }
    } catch (Exception $e) {
        echo json_encode(array("status" => "error", "message" => $e->getMessage()));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Неверный метод запроса"));
}

$conn->close();
?>
