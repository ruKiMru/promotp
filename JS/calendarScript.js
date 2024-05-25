document.addEventListener("DOMContentLoaded", function () {
    const calendar = document.getElementById("calendar");

    function renderCalendar(year, month) {
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDayOfMonth = new Date(year, month, 1).getDay();

        let html = '<table><thead><tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Вс</th></tr></thead><tbody>';

        let date = 1;
        for (let i = 0; i < 6; i++) {
            html += '<tr>';
            for (let j = 0; j < 7; j++) {
                if (i === 0 && j < firstDayOfMonth) {
                    html += '<td></td>';
                } else if (date > daysInMonth) {
                    break;
                } else {
                    html += `<td>${date}</td>`;
                    date++;
                }
            }
            html += '</tr>';
            if (date > daysInMonth) {
                break;
            }
        }

        html += '</tbody></table>';
        calendar.innerHTML = html;
    }

    const currentDate = new Date();
    let currentYear = currentDate.getFullYear();
    let currentMonth = currentDate.getMonth();

    renderCalendar(currentYear, currentMonth);

    function goToPreviousMonth() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentYear, currentMonth);
    }

    function goToNextMonth() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentYear, currentMonth);
    }

    // Прослушиваем события для кнопок переключения месяца
    document.getElementById("prev-month").addEventListener("click", goToPreviousMonth);
    document.getElementById("next-month").addEventListener("click", goToNextMonth);
});
