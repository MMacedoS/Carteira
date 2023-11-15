
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
                <h4>Movimentações Saida</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>
                <button class="btn btn-danger" onclick="imprimir()" id="btn">Imprimir</button>
            </div>
        </div>
    </div>
<hr>
    <div class="row">   
        <div class="input-group">
            <div class="col-sm-2 mb-2">
                <input type="text" class="form-control bg-outline-danger border-0 small" placeholder="descricao" id="txt_busca" aria-label="Search" value="" aria-describedby="basic-addon2">
            </div>
            <div class="col-sm-3 mb-2">
                <input type="date" name="" id="start_date" class="form-control" value="<?=Date('Y-m-d') ?>">
            </div>
            <div class="col-sm-3 mb-2">
                <input type="date" name="" id="end_date" class="form-control" value="<?=Date('Y-m-d')?>">
            </div>   
            <div class="col-sm-3 mb-2">
                <select name="" id="status" class="form-control">
                    <option value="">Selecione o Tipo</option>
                    <option value="1">Dinheiro</option>
                    <option value="2">Cartão de Crédito</option>
                    <option value="3">Cartão de Débito</option>
                    <option value="4">Deposito/PIX</option>
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
        <div class="col-sm-4">
            <b>
                Movimentação de Saidas
            </b>
        </div>
        <div class="col-lg-8 col-sm-8 text-info text-right" id="total"></div>
    </div>
   
    <div class="row">
        <div class="table-responsive ml-3">
            <div id="table"></div>
        </div>
    </div>  
</div>


<!-- editar -->
<div class="modal fade" id="modalSaida" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelConsumo">Lança Saidas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">  
                <form action="" id="form" method="post">                   
                    <div class="form-row">
                        <div class="col-sm-8">
                            <label for="">Descrição</label>
                            <input type="text" name="descricao" class="form-control" id="descricao">
                        </div>
                        <div class="col-sm-4">
                            <label for="">Valor</label>
                            <input type="number" step="0.01" min="0"  value="0.00" class="form-control" name="valor" id="valor">
                        </div>

                        <div class="col-sm-6">
                            <label for="">Tipo de saida</label>
                            <select name="tipo" id="tipo" class="form-control">
                                <option  value="1">Adiantamento</option>
                                <option  value="2">Operacionais</option>
                                <option  value="3">Pessoal</option>
                                <option  value="4">Outros</option>
                            </select>
                        </div>  
                        <div class="col-sm-6">
                            <label for="">Selecione uma forma</label>
                            <select name="pagamento" id="pagamento" class="form-control">
                                <option  value="1">Dinheiro</option>
                                <option  value="2">Cartão de Crédito</option>
                                <option  value="3">Cartão de Débito</option>
                                <option  value="4">Deposito/PIX</option>
                            </select>
                        </div>  
                        <div class="col-sm-12 text-right">
                            <label for="">&nbsp;</label>
                            <button type="submit" name="salvar" id="btnSubmit" class="btn btn-primary Salvar mt-4"> &#10010; Adicionar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
<!-- editar -->
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
<script>
     $(document).ready(function(){
        showData("<?=ROTA_GERAL?>/Financeiro/findAllSaidas")
        .then((response) => createTable(response)).then(() => hideLoader());
    });

    
    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Entradas");
        $('#modalApartamentos').modal('show');        
    });

    function pesquisa() {
        // Obtém o valor do input
        let data = new FormData();
        data.append('search', $('#txt_busca').val());
        data.append('startDate', $('#start_date').val());
        data.append('endDate', $('#end_date').val());
        data.append('status', $('#status').val());  
        // Executa a função com base no valor do input
        showDataWithData("<?=ROTA_GERAL?>/Financeiro/findSaidasByParams/",data)
        .then((response) => createTable(response));;    
    }

    function destroyTable() {
        var table = document.getElementById('table');
        if (table) {
        table.remove(); // Remove a tabela do DOM
        }
    }

    function createTable(data) {
        if (data.length < 0) {
            
            return ;
        }
        // Remove a tabela existente, se houver
        var tableContainer = document.getElementById('table');
        var existingTable = tableContainer.querySelector('table');
        if (existingTable) {
            existingTable.remove();
        }
        var thArray = ['Cod', 'Descrição', 'Tipo de Pagamento', "Data",'Valor']; 
        var table = document.createElement('table');
        table.className = 'table table-sm mr-4 mt-3';
        var thead = document.createElement('thead');
        var headerRow = document.createElement('tr');
        
        var totalValue = 0; 

        thArray.forEach(function(value) {
            var th = document.createElement('th');
            th.textContent = value;
            
            if (value === 'Descrição' || value === 'Tipo' || value === 'Cod') {
                th.classList.add('d-none', 'd-sm-table-cell');
            }
            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        table.appendChild(thead);

        var tbody = document.createElement('tbody');

            data.forEach(function(item) {
                var tr = document.createElement('tr');

                totalValue += parseFloat(item[4]);
                if (item.created_at) {
                        created_at = formatDateWithHour(item.created_at);
                    } 

                thArray.forEach(function(value, key) {
                        var td = document.createElement('td');
                        td.textContent = item[key];
                        
                        if (item[key] === '1' && value == 'Tipo de Pagamento') {
                            td.textContent = 'Dinheiro';
                        } if (item[key] === '2' && value == 'Tipo de Pagamento') {
                            td.textContent = 'Cartão de Crédito';
                        } if (item[key] === '3' && value == 'Tipo de Pagamento') {
                            td.textContent = 'Cartão de Débito';
                        } if (item[key] === '4' && value == 'Tipo de Pagamento') {
                            td.textContent = 'Deposito/PIX';
                        }
                        // venda
                        if (item[key] === null && value == 'Tipo') {
                            td.textContent = 'Venda';
                        }
                        if (item[key] !== null && value == 'Tipo') {
                            td.textContent = 'Hospedagem';
                        }

                        if (value === 'Data') {
                            td.textContent = created_at;
                        }

                        if (value === 'Dt.Saida') {
                            td.textContent = dateSaida;
                        }

                        if (value === 'Descrição' || value === 'Tipo' || value === 'Cod') {
                            td.classList.add('d-none', 'd-sm-table-cell');
                        }
                        tr.appendChild(td);
                    });
                                    // Adiciona os botões em cada linha da tabela
                var buttonsTd = document.createElement('td');

                var delButton = document.createElement('button');
                delButton.innerHTML = '<i class="fa fa-trash"></i>';
                delButton.className = 'btn btn-edit';
                
                buttonsTd.appendChild(delButton);

                // var clearButton = document.createElement('button');
                // clearButton.innerHTML = '<i class="fa fa-trash"></i>';
                // buttonsTd.appendChild(clearButton);

                // var activateButton = document.createElement('button');
                // activateButton.innerHTML = '<i class="fa fa-check"></i>';
                // activateButton.className = 'btn btn-activate';
                // buttonsTd.appendChild(activateButton);

                // // Verificar o valor e definir o ícone e classe apropriados
                // if (item.status === '0') {           
                //     activateButton.querySelector('i').className = 'fa fa-times-circle text-danger';
                //     activateButton.title = 'devolvido';
                // } else {
                //     activateButton.querySelector('i').className = 'fa fa-check-circle text-success';
                //     activateButton.title = 'Recebido';
                // }

                // Adicionando a ação para o botão "deletar"
                delButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                    deletarRegistro(rowData);
                });

                // // Adicionando a ação para o botão "Editar"
                // activateButton.addEventListener('click', function() {
                // var rowData = Array.from(tr.cells).map(function(cell) {
                //     return cell.textContent;
                // });
                // // Chame a função desejada passando os dados da linha
                // deletarRegistro(rowData);
                // });

                tr.appendChild(buttonsTd);
                tbody.appendChild(tr);                
            });

            table.appendChild(tbody);

            var destinationElement = document.getElementById('table');
            destinationElement.appendChild(table);

        $('#total').text("Total de saidas " + totalValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

        return table;
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
            title: 'Deseja remover esta saida?',
            showDenyButton: true,
            confirmButtonText: 'Sim',
            denyButtonText: `Não`,
        }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {                
                deleteData("<?=ROTA_GERAL?>/Financeiro/deleteSaidaById/" + rowData[0]);
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
        return createData('<?=ROTA_GERAL?>/Financeiro/insertSaida', new FormData(document.getElementById("form")));
    });  


    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Reservas");
        $('#modalSaida').modal('show');        
    });

</script>