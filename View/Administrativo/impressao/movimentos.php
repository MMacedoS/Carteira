
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
                <h4>Relação de Movimentos</h4>
            </div>
            <div class="col-sm-4 text-right">           
                <button class="btn btn-danger" onclick="imprimir()" id="btn">Imprimir</button>
            </div>
        </div>
    </div>
<hr>
    <div class="row">   
        <div class="input-group">
            <div class="col-sm-2 mb-2">
                <label for="">Descrição</label>
                <input type="text" class="form-control bg-outline-danger border-0 small" placeholder="" id="txt_busca" aria-label="Search" value="" aria-describedby="basic-addon2">
            </div>
            <div class="col-sm-3 mb-2">
                <label for="">Data Inicial</label>
                <input type="date" name="" id="start_date" class="form-control" value="<?=Date('Y-m-d') ?>">
            </div>
            <div class="col-sm-3 mb-2">
                <label for="">Data Final</label>
                <input type="date" name="" id="end_date" class="form-control" value="<?=Date('Y-m-d')?>">
            </div>   
            <div class="col-sm-3 mb-2">
                <label for="">Movimento</label>
                <select name="" id="status" class="form-control">
                    <option value="">Ambos</option>
                    <option value="1">Entrada</option>
                    <option value="2">Saida</option>
                </select>
            </div>  
            <div class="input-group-append float-right">
                <button class="btn btn-primary ml-3" type="button" onclick="pesquisa()" id="btn_busca">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>
        <hr>
    </div>
    <div id="contents_inputs">
        <div class="row">
            <div class="col-sm-1">Movimentos</div>
            <div class="col-sm-4 text-success" style="text-align: end" id="entradaTotal"></div>
            <div class="col-sm-3 text-danger" style="text-align: end" id="saidaTotal"></div>
            <div class="col-sm-4 text-info" style="text-align: end" id="total"></div>
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
        showData("<?=ROTA_GERAL?>/Financeiro/findMovimentos")
        .then((response) => createTable(response)).then(() => hideLoader());
        hideLoader()
    });
    
    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Entradas");
        $('#modalEntrada').modal('show');        
    });

    function pesquisa() {
        // Obtém o valor do input
        let data = new FormData();
        data.append('description', $('#txt_busca').val());
        data.append('startDate', $('#start_date').val());
        data.append('endDate', $('#end_date').val());
        data.append('status', $('#status').val());  
        // Executa a função com base no valor do input
        showDataWithData("<?=ROTA_GERAL?>/Financeiro/findMovimentosByParams/",data)
        .then((response) => createTable(response));;   
        hideLoader(); 
    }

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
        var thArray = ['Cod', 'Descrição', 'Tipo.', "Movimento",'Valor']; 
        var table = document.createElement('table');
        table.className = 'table table-sm mr-4 mt-3';
        var thead = document.createElement('thead');
        var headerRow = document.createElement('tr');
        var totalValue = 0; 
        var saidaValue = 0; 
        var entradaValue = 0; 
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

                thArray.forEach(function(value, key) {
                        var td = document.createElement('td');
                        // venda
                        if ('Tipo') {
                            td.textContent = prepareTipo(item.tipo);
                        }

                        if (value === 'Movimento') {
                            if(item.entrada_id) {
                                entradaValue += parseFloat(item.valor);
                                td.textContent = "Entrada"; 
                            }
                            if(item.saida_id) {
                                saidaValue += parseFloat(item.valor);
                                td.textContent = "Saida"; 
                            }
                        }

                        if (value === 'Descrição') {
                            td.textContent = item.descricao;
                        }

                        if (value === 'Valor') {  
                            totalValue += parseFloat(item.valor);                          
                            td.textContent = parseFloat(item.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        }

                        if (value === 'Cod') {
                            td.textContent = item.id;
                        }

                        tr.appendChild(td);
                    });
                                    // Adiciona os botões em cada linha da tabela
                var buttonsTd = document.createElement('td');

                var editButton = document.createElement('button');
                editButton.innerHTML = '<i class="fa fa-edit"></i>';
                editButton.className = 'btn btn-edit';

                var delButton = document.createElement('button');
                delButton.innerHTML = '<i class="fa fa-trash"></i>';
                delButton.className = 'btn btn-edit';
                if(item.pagamento_id !== null) {
                    editButton.hidden = true;
                    delButton.hidden = true;
                } 
                buttonsTd.appendChild(editButton);
                buttonsTd.appendChild(delButton);

                // var clearButton = document.createElement('button');
                // clearButton.innerHTML = '<i class="fa fa-trash"></i>';
                // buttonsTd.appendChild(clearButton);

                var activateButton = document.createElement('button');
                activateButton.innerHTML = '<i class="fa fa-check"></i>';
                activateButton.className = 'btn btn-activate';
                buttonsTd.appendChild(activateButton);

                // Verificar o valor e definir o ícone e classe apropriados
                if (item.status === '0') {           
                    activateButton.querySelector('i').className = 'fa fa-times-circle text-danger';
                    activateButton.title = 'devolvido';
                } else {
                    activateButton.querySelector('i').className = 'fa fa-check-circle text-success';
                    activateButton.title = 'Recebido';
                }

                // Adicionando a ação para o botão "Editar"
                editButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                editarRegistro(rowData);
                });

                delButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                deletarRegistro(rowData);
                });

                // Adicionando a ação para o botão "Editar"
                activateButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                activeRegistro(rowData);
                });

                tr.appendChild(buttonsTd);
                tbody.appendChild(tr);                
            });

            table.appendChild(tbody);

            var destinationElement = document.getElementById('table');
            destinationElement.appendChild(table);

            $('#saidaTotal').text("Vl. Saida: " + saidaValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
            $('#entradaTotal').text("Vl. Entrada: " + entradaValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
            totalValue = entradaValue - saidaValue;
            $('#total').text("Vl. Total: " + totalValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));
        
        hideLoader()
        return table;
    }

    function createPaymentTypeChart(data) {
        var paymentTypes = ['Dinheiro', 'Cartão de Crédito', 'Cartão de Débito', 'Deposito/PIX'];
        var paymentTypeCounts = [0, 0, 0, 0];

        data.forEach(function(item) {
            var tipoPagamento = item[2]; // Supondo que o tipo de pagamento esteja na terceira posição (índice 2) do array

            switch (tipoPagamento) {
                case '1':
                    paymentTypeCounts[0]+= parseFloat(item.valor);
                    break;
                case '2':
                    paymentTypeCounts[1]+= parseFloat(item.valor);
                    break;
                case '3':
                    paymentTypeCounts[2]+= parseFloat(item.valor);
                    break;
                case '4':
                    paymentTypeCounts[3]+= parseFloat(item.valor);
                    break;
            }
        });

        var ctx = document.getElementById('paymentTypeChart').getContext('2d');
        var paymentTypeChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: paymentTypes,
                datasets: [{
                    data: paymentTypeCounts,
                    backgroundColor: ['#FF5733', '#36A2EB', '#FFC300', '#4CAF50']
                }]
            },
            options: {
                responsive: true,
                legend: {
                    display: true,
                    position: 'right'
                },
                title: {
                    display: true,
                    text: 'Gráfico de Pagamento por Tipo'
                }
            }
        });
    }

    function editarRegistro(rowData)
    {
        showData("<?=ROTA_GERAL?>/Financeiro/findEntradaById/" + rowData[0])
            .then((response) => prepareModalEditarEntrada(response.data));
        console.log(rowData[0]);
    }

    function deletarRegistro(rowData)
    {
        Swal.fire({
            title: 'Deseja remover esta entrada?',
            showDenyButton: true,
            confirmButtonText: 'Sim',
            denyButtonText: `Não`,
        }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {                
                deleteData("<?=ROTA_GERAL?>/Financeiro/deleteEntradaById/" + rowData[0]);
            } else if (result.isDenied) {
                Swal.fire('nenhuma mudança efetuada', '', 'info')
            }
        })
    }
   
    function prepareModalEditarEntrada(data) {
        $('#descricao').val(data[0].descricao);           
        $('#valor').val(data[0].valor);
        $('#pagamento').val(data[0].tipoPagamento);
        $('#id').val(data[0].id);
        $('#btnSubmit').text('Atualizar');
        $('#exampleModalLabel').text("Atualizar Entrada");
        $('#modalEntrada').modal('show');   
    }

    $(document).on('click','.Salvar',function(){
        event.preventDefault();
        $('.Salvar').prop('disabled', true);
        var id = $('#id').val();
        if(id == '') return createData('<?=ROTA_GERAL?>/Financeiro/salvarEntradas', new FormData(document.getElementById("form")));
    
        return updateData('<?=ROTA_GERAL?>/Financeiro/atualizarEntradas/' + id, new FormData(document.getElementById("form")), id);
    });   
</script>

