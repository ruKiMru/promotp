document.addEventListener('DOMContentLoaded', function() {
    var statusFilter = document.getElementById('statusFilter');
    var taskRows = document.querySelectorAll('.task-table tbody tr');

    statusFilter.addEventListener('change', function() {
        var selectedStatus = statusFilter.value;

        // Показываем или скрываем строки таблицы в зависимости от выбранного статуса
        taskRows.forEach(function(row) {
            var statusCell = row.querySelector('td:nth-child(2)');
            var statusText = statusCell.textContent.trim();

            if (selectedStatus === 'all' || statusText === selectedStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
