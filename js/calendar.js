document.addEventListener('DOMContentLoaded', function() {
  const calendarEl = document.getElementById('calendar');
  const calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    initialView: 'dayGridMonth',
    events: './fetch_events.php', // Fetch events from a separate PHP file
    editable: true,
    selectable: true,
    select: function(info) {
      const title = prompt('Enter Event Title:');
      if (title) {
        fetch('./insert_event.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            title: title,
            start: info.startStr,
            end: info.endStr || info.startStr
          })
        })
        .then(response => response.text())
        .then(result => {
          calendar.refetchEvents(); // Refresh events on the calendar
        })
        .catch(error => console.error('Error:', error));
      }
      calendar.unselect(); // Unselect the date range
    }
  });
  calendar.render();
});
