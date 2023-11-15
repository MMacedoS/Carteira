
<style>
    .hide{
        visibility: hidden;
    }

    .select2 {
        width:100%!important;
    }

    .fs{
        font-size: 21px;
    }
</style>

<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Relação Café</h4>
            </div>
            <div class="col-sm-4 text-right">               
                <button class="btn btn-danger" onclick="imprimir()" id="btn">Imprimir</button>
            </div>
        </div>
    </div>
<hr>
</div>
    <div id="contents_inputs">
        <div class="row">
            <div class="col-sm-6">Lista de Hospedes para Café da manhã</div>
            <div class="col-lg-6 col-sm-12 text-info" style="text-align: end" id="total"></div>
        </div>

        <!-- <div class="row">
            <canvas id="paymentTypeChart" width="400" height="400"></canvas>
        </div> -->
        <div class="row">
            <div class="table-responsive ml-3">
                <div id="table"></div>
            </div>
        </div>
    </div>
<script>
    
    $(document).ready(function(){
        showData("<?=ROTA_GERAL?>/Reserva/findAllCafe")
        .then((response) => createTable(response)).then(() => hideLoader());
    });

    function destroyTable() {
        var table = document.getElementById('table');
        if (table) {
        table.remove(); // Remove a tabela do DOM
        }
    }

    function createTable(data) {        
        // Remove a tabela existente, se houver
        var tableContainer = document.getElementById('table');
        var existingTable = tableContainer.querySelector('table');
        if (existingTable) {
            existingTable.remove();
        }
        var thArray = ['Cod', 'Hóspede', 'Apartamento', 'Situação']; 
        var table = document.createElement('table');
        table.className = 'table table-sm mr-4 mt-3';
        var thead = document.createElement('thead');
        var headerRow = document.createElement('tr');
        var totalValue = 0; 
        thArray.forEach(function(value) {
            var th = document.createElement('th');
            th.textContent = value;
            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        table.appendChild(thead);

        var tbody = document.createElement('tbody');

            data.forEach(function(item) {
                var tr = document.createElement('tr');

                totalValue += parseFloat(item[3]);

                thArray.forEach(function(value, key) {
                        var td = document.createElement('td');
                        td.textContent = item[key];
                        tr.appendChild(td);
                    });
                                    // Adiciona os botões em cada linha da tabela
                var buttonsTd = document.createElement('td');

                var activateButton = document.createElement('button');
                activateButton.innerHTML = '<i class="fa fa-check"></i>';
                activateButton.className = 'btn btn-activate';
                buttonsTd.appendChild(activateButton);

                tbody.appendChild(tr);                
            });

            table.appendChild(tbody);

            var destinationElement = document.getElementById('table');
            destinationElement.appendChild(table);

            $('#total').text("Total de Café " + totalValue);
          
        return table;
    }
</script>

