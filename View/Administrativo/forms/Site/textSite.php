<style>
    .image-preview {
      display: none;
      max-width: 300px;
      max-height: 300px;
    }
    img#preview {
    width: 300px;
}
</style>
<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Lista Texto</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>
            </div>
        </div>
    </div>
<hr>

<div class="row">   
        <div class="input-group">
            <div class="col-sm-5 mb-2">
                <label for="txt_busca">Descrição</label>
                <input type="text" class="form-control bg-outline-danger border-0 small" placeholder="ex: primary" id="txt_busca" aria-label="Search" value="" aria-describedby="basic-addon2">
            </div>
            <div class="col-sm-3 mb-2">
                <label for="start_date">Dt. Postagem</label>
                <input type="date" name="" id="start_date" class="form-control" value="<?=Date('Y-m-d') ?>">
            </div>
            <div class="col-sm-3 mb-2">
                <label for="end_date">Dt. final Postagem</label>
                <input type="date" name="" id="end_date" class="form-control" value="<?=Date('Y-m-d')?>">
            </div>   
            
            <div class="input-group-append float-right">
                <button class="btn btn-primary ml-3" type="button" onclick="pesquisa()" id="btn_busca">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>
<hr>
    <div class="row">
        <div class="table-responsive ml-3">
            <div id="table"></div>
        </div>
    </div>
</div>


<!-- editar -->
<div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelEntrada">Resgitro de Texto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="">  
                <form action="" id="form" method="post" enctype="multipart/form-data">                   
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <div class="col-sm-12">
                            <label for="sessao">Sessão</label>
                            <select id="sessao" class="form-control" name="sessao">
                                <option value="titulo">Titulo</option>
                                <option value="mensagem_titulo">mensagem do titulo</option>
                                <option value="button_titulo">texto botão titulo</option>
                                <option value="messagem_call">mensagem call</option>
                                <option value="button_call">Texto botão call</option>
                                <option value="messagem_offer">mensagem serviços</option>
                                <option value="messagem_cards">mensagem anterior Apts</option>
                                <option value="button_cards">Texto botão Apts</option>
                                <option value="mensagem_call_image">Mensagem acima Imagems</option>
                                <option value="mensagem_call_map">Mensagem acima mapa</option>                              

                            </select>
                        </div>
                        <div class="col-sm-12">
                            <label for="texto">texto</label>
                            <input type="text" class="form-control" id="texto" name="texto" value="" />
                        </div>
                    </div>
                    <hr> 
                    <div class="form-row">
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


<script>
    
    $(document).ready( function () {
        showData("<?=ROTA_GERAL?>/Site/findAllText")
        .then((response) => createTable(response)).then(() => hideLoader());
    });
    
    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Texto");
        $('#modalEntrada').modal('show');        
    });

    function pesquisa() {
        // Obtém o valor do input
        let data = new FormData();
        data.append('search', $('#txt_busca').val());
        data.append('startDate', $('#start_date').val());
        data.append('endDate', $('#end_date').val());
        data.append('status', $('#status').val());  
        // Executa a função com base no valor do input
        showDataWithData("<?=ROTA_GERAL?>/Site/findTextByParams/",data)
        .then((response) => createTable(response));;    
    }

    function destroyTable() {
        var table = document.getElementById('table');
        if (table) {
        table.remove(); // Remove a tabela do DOM
        }
    }

    function createTable(data) {        
        // Remove a tabela existente, se houver
        if (data.length === 0) {
            return;
        }
        var tableContainer = document.getElementById('table');
        var existingTable = tableContainer.querySelector('table');
        if (existingTable) {
            existingTable.remove();
        }
        var thArray = ['Cod', 'Sessão', 'Texto', 'Data']; 
        var table = document.createElement('table');
        table.className = 'table table-sm mr-4 mt-3';
        var thead = document.createElement('thead');
        var headerRow = document.createElement('tr');

        thArray.forEach(function(value) {
            var th = document.createElement('th');
            th.textContent = value;
            
            if (value === 'Sessão' || value === 'Data' || value === 'Cod') {
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
                        td.textContent = item[key];

                        if (value === 'Data') {
                            td.textContent = created_at;
                        }

                        if (value === 'Sessão' || value === 'Data' || value === 'Cod') {
                            td.classList.add('d-none', 'd-sm-table-cell');
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
                // if(item.pagamento_id !== null) {
                //     editButton.hidden = true;
                    delButton.hidden = true;
                // } 
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
                } 
                if (item.status === '1') {
                    activateButton.querySelector('i').className = 'fa fa-check-circle text-success';
                    activateButton.title = 'Recebido';
                }

                // Adicionando a ação para o botão "Editar"
                editButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chamando a função desejada passando os dados da linha
                editarRegistro(rowData);
                });

                delButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chamando a função desejada passando os dados da linha
                deletarRegistro(rowData);
                });

                // Adicionando a ação para o botão "Editar"
                activateButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chamando a função desejada passando os dados da linha
                    deletarRegistro(rowData);
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
        showData("<?=ROTA_GERAL?>/Site/findTextById/" + rowData[0])
            .then((response) => prepareModalEditarBanner(response));
    }

    function deletarRegistro(rowData)
    {
        Swal.fire({
            title: 'Deseja remover esta card?',
            showDenyButton: true,
            confirmButtonText: 'Sim',
            denyButtonText: `Não`,
        }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {                
                deleteData("<?=ROTA_GERAL?>/Site/desativarTextoById/" + rowData[0]);
            } 
            if (result.isDenied) {
                Swal.fire('nenhuma mudança efetuada', '', 'info')
            }
        })
    }
   
    function prepareModalEditarBanner(data) {
        $('#sessao').val(data[0].sessao);           
        $('#texto').val(data[0].texto);
        $('#id').val(data[0].id);
        $('#btnSubmit').text('Atualizar');
        $('#exampleModalLabel').text("Atualizar Texto");
        $('#modalEntrada').modal('show');   
    }

    $(document).on('click','.Salvar',function(){
        event.preventDefault();
        $('.Salvar').prop('disabled', true);
        var id = $('#id').val();
        if(id == '') {
            return createData('<?=ROTA_GERAL?>/Site/saveText', new FormData(document.getElementById("form")));
        }
    
        return updateData('<?=ROTA_GERAL?>/Site/updateText/' + id, new FormData(document.getElementById("form")), id);
    });   
</script>