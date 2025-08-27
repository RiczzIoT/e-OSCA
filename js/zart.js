$(document).ready(function () {
  $('#calendar').fullCalendar({
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'month,agendaWeek,agendaDay'
    },
    events: [
      {
        title: 'Event 1',
        start: '2023-06-01'
      },
      {
        title: 'Event 2',
        start: '2023-06-02'
      }
    ]
  });

  var ctx = document.getElementById('ageChart').getContext('2d');
  var ageChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['0-9', '10-19', '20-29', '30-39', '40-49', '50-59', '60-69', 
               '70-79', '80-89', '90-99', '100-109', '110-119', '130+'],
      datasets: [{
        label: 'Number of Age',
        data: ageGroups,  // Data provided from PHP
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  var ctx2 = document.getElementById('nameChart').getContext('2d');
  var nameChart = new Chart(ctx2, {
    type: 'pie',
    data: {
      labels: ['Total Residents', 'Infants'],
      datasets: [{
        label: 'Residents by Total',
        data: [totalResidents, infantsCount],  // Data provided from PHP
        backgroundColor: [
          'rgba(255, 99, 132, 0.8)',
          'rgba(54, 162, 235, 0.8)',
          'rgba(255, 206, 86, 0.8)',
          'rgba(75, 192, 192, 0.8)',
          'rgba(153, 102, 255, 0.8)',
          'rgba(255, 159, 64, 0.8)',
          'rgba(255, 99, 132, 0.8)',
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: 'right',
        labels: {
          fontColor: '#333'
        }
      },
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var dataset = data.datasets[tooltipItem.datasetIndex];
            var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
              return previousValue + currentValue;
            });
            var currentValue = dataset.data[tooltipItem.index];
            var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
            return currentValue + ' (' + percentage + '%)';
          }
        }
      }
    }
  });
});
