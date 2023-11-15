<!DOCTYPE html>
<html lang='pt-br'>
  <head>
    <meta charset='utf-8' />
    <style>
      table.fc-scrollgrid-sync-table {
        width: 95% !important;
        height: 70vh !important;
      }

      table.fc-col-header {
          width: 95% !important;
      }
      div#calendar {
          height: 81vh;
      }
    </style>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-br',
            eventSources: [{
            url: '<?=ROTA_GERAL?>/Consulta/mapa/',
            method: 'POST',
            failure: function() {
                alert('Erro ao carregar eventos.');
            }
            }],

            datesSet: function(info) {
            var start = info.start;
            var end = info.end;
            
            // Converter as datas para o formato esperado pela API
            var startDate = start.toISOString().split('T')[0];
            var endDate = end.toISOString().split('T')[0];

            // Fazer a requisição à API e receber os eventos
            fazerRequisicaoEventos(startDate, endDate, function(eventos) {
                // Limpar os eventos existentes
                calendar.getEventSources().forEach(function(eventSource) {
                eventSource.remove();
                });

                // Adicionar os novos eventos ao calendário
                calendar.addEventSource(eventos);
            });
            }
        });

        calendar.render();
        });


        
function fazerRequisicaoEventos(startDate, endDate, callback) {
  // Fazer a requisição à sua API para obter os eventos
  // Utilize a startDate e endDate na sua requisição para obter os eventos do intervalo desejado

    // Exemplo de requisição usando fetch:
    fetch(`<?=ROTA_GERAL?>/Consulta/mapa/${startDate}_@_${endDate}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json'
      },
      // Incluir as datas no corpo da requisição, se necessário
    })
    .then(response => response.json())
    .then(data => {
      // Chamar o callback passando os eventos recebidos da API
      callback(data);
    })
    .catch(error => {
      console.error('Erro na requisição:', error);
      // Lidar com o erro da requisição, se necessário
    });
 
}
    </script>
  </head>
  <body>
    <div id='calendar'></div>
  </body>
</html>