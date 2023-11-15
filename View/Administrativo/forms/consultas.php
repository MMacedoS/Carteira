<style>
    .graficos-container {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      width: 100%;
      height: 30rem;
    }

    .grafico {
      flex: 1 0 45%; /* Define a largura das divs filhas */
      margin: 10px;
    }

    .grafico canvas {
      width: 100%;
      height: 100%;
    }

    .qtde{
        margin-top:-80px
    }

    @media screen and (max-width: 768px) {
        .qtde{
            margin-top:-40px;
        }
    }
  </style>

<!-- Page Heading -->

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0">Painel de Consultas</h1>

</div>

<!-- Content Row -->
<!-- <div class="row"> -->

<!-- Earnings (Monthly) Card Example -->
<div class="row">
    <!-- <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <a href="<=ROTA_GERAL?>/Administrativo/reservas">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Reservas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><count?></div>
                        </div>
                        <div class="col-auto">
                        <small class="mr-3" id="reservadas"></small>
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>                    
                    </div>
                </div>
            </a>    
        </div>
    </div> -->
</div>
<hr>
<div class="row ">
    <div class="graficos-container">
        <div class="grafico col-xl-3">
            <h3 class="h3 mb-0">Entradas e saidas</h4>
            <canvas id="canvas2"></canvas>
        </div>
        <div class="grafico col-xl-7">
            <h3 class="h3 mb-0">Linha de movimentação</h4>
            <canvas id="canvas1"></canvas>
        </div>
    </div>
</div>
<hr>


<script>
    $(document).ready(function() {
        
        showData("<?=ROTA_GERAL?>/Consulta/hospedadas")
        .then(function(response) {
                hideLoader();
                hospedadas = response;

            const canvas1 = document.getElementById("canvas1");
            criarGraficoBarras(canvas1, hospedadas, "Valor da Hospedagem",);
            $('#hospedadas').text(hospedadas.length);
            const canvas2 = document.getElementById("canvas2");
            createChart(canvas2, hospedadas, 25);
            const canvas3 = document.getElementById("canvas3");
            fillCanvasWithNumber(canvas3, hospedadas);

        });

        showData("<?=ROTA_GERAL?>/Consulta/checkin")
        .then(function(response) {
                hideLoader();
            $('#checkin').text(response.length);
        });

        showData("<?=ROTA_GERAL?>/Consulta/checkout")
        .then(function(response) {
                hideLoader();
            $('#checkout').text(response.length);
        });

        showData("<?=ROTA_GERAL?>/Consulta/confirmada")
        .then(function(response) {
                hideLoader();
            $('#confirmadas').text(response.length);
        });

        showData("<?=ROTA_GERAL?>/Consulta/reservada")
        .then(function(response) {
                hideLoader();
            $('#reservadas').text(response.length);
        });

    });

function criarGraficoBarras(canvas, dados, titulo = "Valor da Hospedagem",) {
    
  // Extrair os valores das reservas
  const valores = dados.map((dado) => parseFloat(dado.valor));

  // Extrair os nomes dos apartamentos
  const nomes = dados.map((dado) => `Apt. ${dado.numero}`);

  // Criar o gráfico de barras usando a biblioteca Chart.js
  new Chart(canvas, {
    type: "bar",
    data: {
      labels: nomes,
      datasets: [
        {
          label: titulo,
          data: valores,
          backgroundColor: "rgba(54, 162, 235, 0.5)",
          borderColor: "rgba(54, 162, 235, 1)",
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
}

function createChart(canva,data, maxValue) {
  // Calcula a porcentagem de preenchimento com base na quantidade do array e no valor máximo
  var percentage = (data.length / maxValue) * 100;

  // Configuração do gráfico
  var chart = new Chart(canva, {
    type: 'pie',
    data: {
      labels: [percentage.toFixed(1) + '% Lotado', (100 - percentage).toFixed(1) + '% Disponivel'],
      datasets: [
        {
          data: [percentage, 100 - percentage],
          backgroundColor: ['rgba(150, 0, 25, 0.5)', 'rgba(0, 255, 0, 0.5)'] // Cores de preenchimento
        }
      ]
    },
    options: {
      tooltips: {
        callbacks: {
          label: function(tooltipItem, data) {
            var dataset = data.datasets[tooltipItem.datasetIndex];
            var currentValue = dataset.data[tooltipItem.index];
            return currentValue + '%';
          }
        }
      }
    }
  });
}

function fillCanvasWithNumber(canvas, dados) {
  // Obter o contexto 2D do canvas
  var context = canvas.getContext('2d');

  const valores = dados.reduce((acumulador, value) => {
        return acumulador + parseInt(value.qtde_hosp);
    }, 0);
  
  // Limpar o canvas
  context.clearRect(0, 0, canvas.width, canvas.height);
  
  // Configurar o estilo de preenchimento
  context.fillStyle = 'black';
  context.font = 'bold 48px Arial';
  context.textAlign = 'center';
  context.textBaseline = 'middle';
  
  // Obter as dimensões do canvas
  var canvasWidth = canvas.width;
  var canvasHeight = canvas.height;
  
  // Preencher o número centralizado no canvas
  context.fillText(valores, canvasWidth / 2, canvasHeight / 2);
}

</script>
