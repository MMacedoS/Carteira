
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
                <h4>Ordens de Serviços</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>
            </div>
        </div>
    </div>
<hr>
    <form id="form_search" method="POST">
        <div class="row">              
            <div class="col-sm-12 mb-2">
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome, cpf" name="busca" id="txt_busca" aria-label="Search" value="" aria-describedby="basic-addon2">
            </div>
                
            <div class="col-sm-3 mb-2">
                <input type="date" name="dt_entrada" id="busca_entrada" class="form-control" value="">
            </div>
            <div class="col-sm-3 mb-2">
                <input type="date" name="dt_saida" id="busca_saida" class="form-control" value="">
            </div>
            <div class="col-sm-3">
                <select name="status" id="busca_status" class="form-control">
                    <option value="">Selecione o status</option>
                    <option value="1">Orçamento</option>
                    <option value="2">Confirmada</option>
                    <option value="3">andamento</option>
                    <option value="4">Finalizada</option>
                    <option value="5">Cancelada</option>
                </select>
            </div>     
            <div class="col-sm-3 input-group-append float-right">
                <button class="btn btn-primary" type="button" onclick="pesquisa()">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>    
    </form>
<hr>
    <div class="row">
        <div class="table-responsive ml-3">
            <div id="table"></div>
        </div>
    </div>    

<!-- editar -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Ordens</h5>
                <button class="btn btn-danger close" onclick="sair()"> <span aria-hidden="true">&times;</span></button>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
           
            <form action="" id="form" method="POST">
                <div class="modal-body" id="">                               
                    <div class="form-row">
                        <input type="hidden" disabled id="id" >
                        <input type="hidden" disabled id="opcao" value="" >
                        <div class="col-sm-5">
                            <label for="">Data Entrada</label>
                            <input type="date" name="entrada" id="entrada" min="<?=date('Y-m-d')?>" class="form-control">
                        </div>

                        <div class="col-sm-5">
                            <label for="">Data Saida</label>
                            <input type="date" name="saida"  min="<?=self::addDdayInDate(date('Y-m-d'),1)?>" id="saida" class="form-control">
                        </div>

                        <div class="col-sm-2 mt-4">
                            <button class="btn btn-primary mt-2" type="button" id="buscaApt" >
                                <i class="fas fa-search fa-sm"></i>
                            </button>   
                        </div>                    
                    </div>
                    <div class="form-row hide" id="div_apartamento">

                        <div class="col-sm-12 mb-3">
                            <label for="">Hospede</label><br>
                            <select id="hospedes" class="selectized" name="hospedes">
                               
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="">Tipo</label><br>
                            <select class="form-control" name="tipo" id="tipo">
                                <option value="1">Serviço</option>
                                <option value="2">Material</option>
                                <option value="3">Manutenção</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="">Status</label><br>
                            <select class="form-control" name="status" id="status">
                                <option value="1">Orçamento</option>
                                <option value="2">Confirmada</option>
                                <option value="3">andamento</option>
                                <option value="4">Concluida</option>
                                <option value="5">Cancelada</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label for="">Valor</label>
                            <input type="number" class="form-control" onchange="valores()" name="valor" step="0.01" min="0.00" value="" id="valor">
                        </div>

                        <div class="col-sm-12">
                            <label for="">observação</label><br>
                            <textarea name="observacao" class="form-control" id="observacao" cols="30" rows="5"> &nbsp;</textarea>
                        </div>
                    </div>   

                    <small>
                        <div align="center" class="mt-1" id="mensagem"></div>
                        <div align="right" class="mt-1 fs" id="valores"></div>
                    </small>
                </div>
                <div class="modal-footer">
                    
                    <!-- <button  class="btn btn-secondary" onclick="sair()" >Fechar</button> -->
                    <button type="submit" name="salvar" id="btnSubmit" disabled class="btn btn-primary Salvar">Salvar</button>
                </div>
            </form>        
        </div>
        
    </div>
</div>
<!-- editar -->
</div>
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>

<script>
    var hospede = null;
    function valores(){
        var dias = moment($('#saida').val()).diff(moment($('#entrada').val()), 'month');
         var valor = $("#valor").val();
            $('#valores').removeClass('text-success');
            $('#valores').addClass('text-success');
            $('#valores').text("Valor Total da Estadia: R$" + valor * dias);
      }

    function formatDate(value)
    {
        const date = value.split('-');
        return ''+date[2]+ '/' + date[1] + '/' + date[0];
    }

    $(document).ready(function() {
        $('#hospedes').selectize({});
        showData("<?=ROTA_GERAL?>/Reserva/getAll")
        .then((response) => createTable(response))
        .then(() => hideLoader());  
    });

    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Ordens");
        $('#modal').modal('show');        
    });


    function sair(){
        redirecionarPagina("<?=ROTA_GERAL?>/Administrativo/reservas");
    }

    function pesquisa() {                
        // Executa a função com base no valor do input
        showDataWithData("<?=ROTA_GERAL?>/Reserva/buscaReservas/",  new FormData(document.getElementById("form_search")))
        .then((response) => createTable(response))
        .then(() => hideLoader());    
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
        var thArray = ['Cod', 'Hospede', 'Dt.Entrada','Dt.Saida', 'Tipo', 'Situação']; 
        var table = document.createElement('table');
        table.className = 'table table-sm mr-4 mt-3';
        var thead = document.createElement('thead');
        var headerRow = document.createElement('tr');

        thArray.forEach(function(value) {
            var th = document.createElement('th');
            th.textContent = value;
            
            if (value === 'CPF' || value === 'Endereço' || value === 'Cod') {
                th.classList.add('d-none', 'd-sm-table-cell');
            }
            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        table.appendChild(thead);

        var tbody = document.createElement('tbody');

            data.forEach(function(item) {
                var tr = document.createElement('tr');
                if (item.dataEntrada) {
                        dateEntrada = formatDate(item.dataEntrada);
                        dateSaida = formatDate(item.dataSaida);
                    } 

                if (item.status == '1') {
                     status = 'Orçamento';
                } if (item.status == '2') {
                    status = 'Confirmada';
                } 
                if (item.status == '3') {
                    status = 'Andamento';
                } 
                if (item.status == '4') {
                    status = 'Finalizada';
                } 
                if (item.status == '5') {
                    status = 'Cancelada';
                } 

                if (item.tipo == '1') {
                    tipo = 'seviço';
                } 
                if (item.tipo == '2') {
                    tipo = 'material';
                } 
                if (item.tipo == '3') {
                    tipo = 'manutenção';
                } 

                thArray.forEach(function(value, key) {
                        var td = document.createElement('td');
                        td.textContent = item[key];

                        if (value === 'Dt.Entrada') {
                            td.textContent = dateEntrada;
                        }

                        if (value === 'Dt.Saida') {
                            td.textContent = dateSaida;
                        }
                        
                        if (value === 'Situação') {
                            td.textContent = status;
                        } 
                        
                        if (value === 'Tipo') {
                            td.textContent = tipo;
                        } 

                        if (value === 'CPF' || value === 'Endereço' || value === 'Cod') {
                            td.classList.add('d-none', 'd-sm-table-cell');
                        }
                        tr.appendChild(td);
                    });
                                    // Adiciona os botões em cada linha da tabela
                var buttonsTd = document.createElement('td');

                var editButton = document.createElement('button');
                editButton.innerHTML = '<i class="fa fa-edit"></i>';
                editButton.className = 'btn btn-edit';
                buttonsTd.appendChild(editButton);

                // Adicionando a ação para o botão "Editar"
                editButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                editarRegistro(rowData);
                });

                tr.appendChild(buttonsTd);
                tbody.appendChild(tr);                
            });

            table.appendChild(tbody);

            var destinationElement = document.getElementById('table');
            destinationElement.appendChild(table);

        return table;
    }

    function editarRegistro(rowData)
    {
        showData("<?=ROTA_GERAL?>/Reserva/buscaReservaPorId/" + rowData[0])
            .then((response) => preparaModalEditarReserva(response.data));
    }

    function activeRegistro(rowData)
    {
        Swal.fire({
            title: 'Deseja cancelar esta reserva?',
            showDenyButton: true,
            confirmButtonText: 'Sim',
            denyButtonText: `Não`,
        }).then((result) => {
            if (result.isConfirmed) {
                showData("<?=ROTA_GERAL?>/Reserva/changeStatusReservas/" + rowData[0])
                    .then((response) => showSuccessMessage('Registro atualizado com sucesso!'));
            } else if (result.isDenied) {
                Swal.fire('nenhuma mudança efetuada', '', 'info')
            }
        })   
    }

    $(document).on('click','#buscaApt',function() {
            var dataEntrada = moment($('#entrada').val());
            var dataSaida = moment($('#saida').val());           
            
            var opcao = $('#opcao').val();   

            if(dataSaida >= dataEntrada){
                valores();
                buscaApartamento(
                    dataEntrada._i,
                    dataSaida._i
                );

                $('.Salvar').attr('disabled', false);
            }            
        });

    function buscaApartamento(
        dataEntrada,
        dataSaida
    ){
        $('#div_apartamento').removeClass('hide');
        populaHospede(hospede);
    }

    function preparaModalEditarReserva(data) 
    {
        $('#entrada').val(data[0].dataEntrada);
        $('#saida').val(data[0].dataSaida);
        $('#tipo').val(data[0].tipo);
        $('#valor').val(data[0].valor);
        $('#status').val(data[0].status);
        $('#observacao').val(data[0].obs);        
        $('#inp-qtdeHosp').val(data[0].qtde_hosp);
        $('#id').val(data[0].id);
        
        $('#div_apartamento').removeClass('hide');       
        hospede = data[0].hospede_id;
        populaHospede(hospede);
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar Reservas");
        $('#modal').modal('show'); 
        $('.Salvar').attr('disabled', false);
        return ;
    }

    function populaHospede(hospede = null){
        showData("<?=ROTA_GERAL?>/Hospede/getAllSelect")
       .then((response) => {
            let hospedes = response.map(element => {
               return { id: element.id, title: element.nome}
            });
            console.log("Hospede" + hospede);
            prepareSelect(hospedes, '#hospedes', hospede);
       });

    }

    $(document).on('click','.Salvar',function(){
        event.preventDefault();
        var id = $('#id').val();
        var dataEntrada = moment($('#entrada').val());
        var dataSaida = moment($('#saida').val());
        
        if(dataSaida > dataEntrada){
            if(id == ''){
                return createData('<?=ROTA_GERAL?>/Reserva/salvarReservas', new FormData(document.getElementById("form"))).then( (response) => {
                    hospede = null;
                    window.location.href="<?=ROTA_GERAL?>/Administrativo/Reservas/";
                });
            }
        
            return updateData('<?=ROTA_GERAL?>/Reserva/atualizarReserva/' + id, new FormData(document.getElementById("form"))).then( (response) => {
                hospede = null;
            });
        }
    });
</script>

<?php if (isset($_GET['hospede']) && !empty($_GET['hospede'])) {?>
    <script>
        hospede = <?=$_GET['hospede'];?> ;
        $('#novo').click();       
    </script>
<?php } ?>

