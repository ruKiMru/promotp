<?php
include 'database.php'; // Подключение к базе данных

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Получаем данные из POST-запроса
        $id_issue = $_POST["id_issue"];

        // SQL-запрос для выбора комментариев по id_issue
        $sql = "SELECT comment, DATE_FORMAT(creation_date, '%e %b') AS formatted_date FROM comments WHERE id_issue = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_issue);
        $stmt->execute();
        $result = $stmt->get_result();

        // Формирование HTML для комментариев
        $html = "";
        while ($row = $result->fetch_assoc()) {
            $comment = htmlspecialchars($row['comment']); // Предотвращение XSS-атак
            $formattedDate = htmlspecialchars($row['formatted_date']); // Предотвращение XSS-атак

            // Добавление комментария с форматированной датой в виде "День Месяц - Комментарий"
            $html .= "<div>$formattedDate - \"$comment\"</div>";
        }

        echo $html;

        // Закрытие соединения с базой данных
        $stmt->close();
    } catch (Exception $e) {
        // Выводим детали ошибки, если что-то пошло не так
        echo json_encode(array("status" => "error", "message" => $e->getMessage()));
    }
} else {
    // Возвращаем ошибку, если запрос не является POST-запросом
    echo json_encode(array("status" => "error", "message" => "Неверный метод запроса"));
}

$conn->close();
?>
