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

    .modal-static {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
       top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
     }

    .modal-static-content {
        background-color: #fff;
        width: 80%;
        max-width: 400px;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        border-radius: 5px;
        text-align: center;
    }

    @media screen and (max-width: 767px) {
        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 390px;
            pointer-events: auto;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .2);
            border-radius: .3rem;
            outline: 0;
            left: -70px;
            }  
            
            .campos_modal{
            width: 125px;
        }            
    }
</style>

<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Vendas</h4>
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

<div id="contents_inputs">
    <div class="row">
        <div class="col-sm-4">
            <b>
                Movimentação de Vendas
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
</div>

<!-- editar -->
<div class="modal fade" id="modalVenda" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelVenda">Lança Vendas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >  
                <form action="" id="form-venda" method="post">
                    <div class="row">
                        <div class="table-responsive" style="height: 250px">
                            <table class="table bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            Produto
                                        </th>
                                        <th class="d-none d-sm-table-cell">
                                            Data
                                        </th>
                                        <th>
                                            Quantidade
                                        </th>
                                        <th class="d-none d-sm-table-cell">
                                            valor Unitario
                                        </th>
                                        <th class="d-none d-sm-table-cell">
                                            Total
                                        </th>
                                        <th>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="listaItens">

                                </tbody>
                            </table>
                           
                        </div>       
                        <div class="col-sm-6 text-right">
                            <small class="text-end">Registro(s) <span id="numeroItens">0</span></small> 
                        </div> 
                        <div class="col-sm-6 text-right">
                            <small class="text-end">Total R$ <span id="totalItens"></span></small> 
                        </div>      
                    </div>
                    <hr>
                    <div class="form-row">
                        <input type="hidden" name="id_venda" id="id_venda">
                        <div class="col-sm-4">
                            <label >Produto</label>
                            <select name="produto" class="form-control" id="produto">
                                
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label >quantidade</label>
                            <input type="number" step="0" min="0"  value="1" class="form-control" name="quantidade" id="quantidade">
                        </div>
                        <div class="col-sm-4 text-center">
                            <label >&nbsp;</label>
                            <button type="submit" name="salvar" class="btn btn-warning add-itens mt-4"> &#10010; Adicionar Produto</button>
                        </div>
                    </div>
                    <hr>
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseChecked" aria-expanded="false" aria-controls="collapseExample" >Concluir venda</button>

                    <div class="collapse" id="collapseChecked">
                        <div class="form-row">
                            <div class="col-sm-12 mt-3">
                                <label for="">Descrição</label>
                                <input type="text" name="descricao" class="form-control" id="descricao" value="">
                            </div>
                            <div class="col-sm-3">
                                <label >Valor</label>
                                <input type="number" name="valor" class="form-control" step="0.01" min="0" id="valor">
                            </div>
                            <div class="col-sm-3">
                                <label >Tipo Pagamento</label>
                                <select name="tipo" class="form-control" id="tipo_pagamento">
                                    <option value=""></option>
                                    <option value="1">Dinheiro</option>
                                    <option value="2">Cartão de Crédito</option>
                                    <option value="3">Cartão de Débito</option>
                                    <option value="4">Deposito/PIX</option>
                                </select>
                            </div>                        
                            <div class="col-sm-3">
                                <label >Status</label>
                                <select name="status" class="form-control" id="status">
                                    <option value="aberta">Aberta</option>
                                    <option value="fechada">Finalizada</option>
                                </select>
                            </div>
                            <div class="col-sm-3 text-center">
                                <label >&nbsp;</label>
                                <button type="submit" name="salvar" class="btn btn-success salvar-venda mt-4"> <i class="fas fa-fw fa-cart-plus"></i> Finalizar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>

<!-- cons -->
<div id="changeItens" class="modal-static">
    <div class="modal-static-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Alterar Itens Vendas</h2>
        <form id="swal-form-itens">
            <input type="hidden" name="swal_id_item" id="swal_id_item">
            <div class="col-sm-12">
                <label>Qtdo<input id="swal-cons-input1" name="quantidade" class="form-control" type="number"></label>
            </div>
            <div class="col-sm-12 float-right">
                <button type="button" id="saveChangesItens" class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- editar -->
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
<script>
    var totalItens = 0;
     $(document).ready(function(){
        showData("<?=ROTA_GERAL?>/Vendas/findAllVendas")
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
        showDataWithData("<?=ROTA_GERAL?>/Vendas/findVendaByParams/",data)
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
        var thArray = ['Cod', 'Descrição', "Data", "Valor","Status",'Pagamento', "Tipo"]; 
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

                if (item.created_at) {
                        created_at = formatDateWithHour(item.created_at);
                    } 

                thArray.forEach(function(value, key) {
                        var td = document.createElement('td');
                        
                        if (item.tipoPagamento === '1' && value == 'Tipo') {
                            td.textContent = 'Dinheiro';
                        } if (item.tipoPagamento === '2' && value == 'Tipo') {
                            td.textContent = 'Cartão de Crédito';
                        } if (item.tipoPagamento === '3' && value == 'Tipo') {
                            td.textContent = 'Cartão de Débito';
                        } if (item.tipoPagamento === '4' && value == 'Tipo') {
                            td.textContent = 'Deposito/PIX';
                        }
                        if (item.tipoPagamento === null && value == 'Tipo') {
                            td.textContent = '----';
                        }
                        // venda
                        if (value == 'Valor') {
                            td.textContent = item.valor_venda;
                        }
                        
                        if (value === 'Data') {
                            td.textContent = created_at;
                        }

                        if (value === 'Descrição') {
                            td.textContent = item.descricao;
                        }

                        if (value === 'Cod') {
                            td.textContent = item.id;
                        }                        

                        if (value === 'Status') {
                            td.textContent = item.status;
                        }

                        if (value === 'Pagamento') {
                            totalValue += parseFloat(item.valor ? item.valor : 0);
                            td.textContent = item.valor ? item.valor : 'Aguardando';
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

                // var editButton = document.createElement('button');
                // editButton.innerHTML = '<i class="fa fa-edit"></i>';
                // editButton.className = 'btn btn-edit';

                // buttonsTd.appendChild(editButton);
                buttonsTd.appendChild(delButton);              

                // Adicionando a ação para o botão "deletar"
                delButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                    deletarRegistro(rowData);
                });

                 // Adicionando a ação para o botão "Editar"
                //  editButton.addEventListener('click', function() {
                // var rowData = Array.from(tr.cells).map(function(cell) {
                //     return cell.textContent;
                // });
                // // Chame a função desejada passando os dados da linha
                //  editarRegistro(rowData);
                // });

                tr.appendChild(buttonsTd);
                tbody.appendChild(tr);                
            });

            table.appendChild(tbody);

            var destinationElement = document.getElementById('table');
            destinationElement.appendChild(table);

        $('#total').text("Total de vendas " + totalValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }));

        return table;
    }

    function editarRegistro(rowData)
    {
        showData("<?=ROTA_GERAL?>/Vendas/findEntradaById/" + rowData[0])
            .then((response) => prepareModalEditarEntrada(response));
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
                deleteData('<?=ROTA_GERAL?>/Vendas/deleteVendas/'+ rowData[0]);
            } else if (result.isDenied) {
                Swal.fire('nenhuma mudança efetuada', '', 'info')
            }
        })
    }
   
    function prepareModalEditarVenda(data) {
        $('#descricao').val(data.descricao);           
        $('#valor').val(data.valor);
        $('#tipoPagamento').val(data.tipoPagamento);
        $('#id_venda').val(data.id);
        $('#btnSubmit').text('Atualizar');
        $('#exampleModalLabel').text("Atualizar Vendas");
        $('#modalVenda').modal('show');   
    }

    $(document).on('click','.Salvar',function(){
        event.preventDefault();        
        $('.Salvar').prop('disabled', true);
        return createData('<?=ROTA_GERAL?>/Vendas/addVenda', new FormData(document.getElementById("form")));
    });  


    $('#novo').click(function(){        
        showData('<?=ROTA_GERAL?>/Vendas/addVenda',[])
        .then((response) => {
            prepareModalEditarVenda(response)            
            showData("<?=ROTA_GERAL?>/Vendas/getVendasItems/"+ response.id)
            .then( (data) => {prepareTableConsumo(data),  hideLoader()});
        }).then((data) => {
            $('#produto option').detach();
            showData('<?=ROTA_GERAL?>/Produto/getDadosProdutos').then((data) => {
                if(data.status === 201){                    
                    data.data.map(element => {
                        var newOption = $('<option value="' + element.id + '">' + element.descricao + '</option>');
                        $("#produto").append(newOption);
                    })   
                }
            });
        });   
        hideLoader();     
    });

    function prepareTableConsumo(data)
    {
        $("#listaItens tr").detach();
        data.map(element => {
            var newOption = $('<tr>'+
                    '<td>'+element.descricao+'</td>' +
                    '<td class="d-none d-sm-table-cell">'+formatDateWithHour(element.created_at)+'</td>' +
                    '<td>'+element.quantidade+'</td>' +
                    '<td class="d-none d-sm-table-cell">R$ '+element.valor+'</td>' +
                    '<td class="d-none d-sm-table-cell">R$ '+
                    parseFloat(element.valor * element.quantidade).toFixed(2)
                    +'</td>' +
                    '<td>'+
                        '<a href="#" id="'+element.id+'" class="alterar-itens" alt="alterar"><span style="font-size:25px;">&#9997;</span></a> &nbsp;'+
                        '<a href="#" id="'+element.id+'" class="remove-itens" >&#10060;</a>'+
                    '</td>'+
                '</tr>');
            $("#listaItens").append(newOption);
        })

        $('#numeroItens').text(data.length);
        $('#totalItens').text(calculaConsumo(data).toFixed(2));
        totalItens = calculaConsumo(data);
    }
    
    function calculaConsumo(data)
    {
        var valor = 0;
        data.forEach(element => {
            valor += element.valor * element.quantidade;
        });

        return valor;
    }

    $(document).on('click', '.alterar-itens', function(){
        let code=$(this).attr("id");    

        showData("<?=ROTA_GERAL?>/Vendas/getItensPorId/" + code ).then(function(data){
            if(data.id){
                $('#swal_id_item').val(data.id);
                $('#swal-cons-input1').val(data.quantidade);
                $("#changeItens").modal('show');
            }
        });  
    });
    
    $(document).on('click', '.remove-itens', function(){
        let code = $(this).attr("id");  
        updateDataWithData('<?=ROTA_GERAL?>/Vendas/deleteItensById/'+ code).then(() => hideLoader()); 
        $('#novo').click();
    });

    $(document).on('click', '#saveChangesItens', function(event) {
            event.preventDefault();
            $('#saveChangesDiaria').prop('disabled', true);
            let code=$("#swal_id_item").val(); 
            updateDataWithData('<?=ROTA_GERAL?>/Vendas/updateItensById/'+ code, new FormData(document.getElementById("swal-form-itens")));                                  
            $('#novo').click();
        });
    
    $(document).on('click','.salvar-venda',function(){
            event.preventDefault();
            let code=$("#id_venda").val(); 
            id_reserva = code;

            updateData('<?=ROTA_GERAL?>/Vendas/updateVendas/' + code, new FormData(document.getElementById("form-venda")));                                  
    });

    $(document).on('click','.add-itens',function(){
        event.preventDefault();
        let code=$("#id_venda").val(); 
        id_reserva = code;

        updateDataWithData('<?=ROTA_GERAL?>/Vendas/addItensByIdVenda/' + code, new FormData(document.getElementById("form-venda")));                                  
        $('#novo').click();
    });

</script>