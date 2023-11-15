<div class="container">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Clientes</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>
            </div>
        </div>
    </div>

    <div class="row">        
        <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" onkeyup="pesquisa()" placeholder="busca por ..." id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" onclick="pesquisa()">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive ml-3">
            <div id="table">

            </div>
        </div>
    </div>

    

<!-- editar -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cadastro de Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <form action="" id="form" method="POST">
                <div class="modal-body" id="">                               
                    <div class="form-row">
                        <div class="col-sm-6">
                            <input type="hidden" disabled id="id" >
                            <label for="">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="nome do funcionario" required value="">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email" required value="">
                        </div>
                    </div>                   
                    <div class="form-row">
                        <div class="col-sm-6">
                            <label for="">CPF</label>
                            <input type="text" name="cpf" id="cpf"  pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" maxlength="18" class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label for="">Telefone</label>
                            <input type="text" name="telefone" id="telefone"  pattern="\([0-9]{2}\) [0-9]{4,5}-[0-9]{4}" maxlength="14" class="form-control">
                        </div>                       
                        <div class="col-sm-12">
                            <label for="">Endereço</label>
                            <input type="text" name="endereco" id="endereco"  placeholder="ex: Rua 1, n 20, cidade - BA" class="form-control">
                        </div>   
                    </div>    
                    
                    <div class="form-row">
                        <div class="col-sm-4">
                            <label for="">Tipo</label>
                            <select name="tipo" class="form-control" id="tipo">
                                <option value="1">Normal</option>
                                <option value="2">Representante</option>
                            </select>
                        </div>

                        <!-- <div class="col-sm-5">
                            <label for="">Empresa</label>
                            <select name="empresa" class="form-control" id="empresa">
                                <option value="">Selecione ...</option>
                                <option value="1">MMS</option>
                            </select>
                        </div> -->
                        <!-- <div class="col-sm-1 mt-1">
                            <label for="">&nbsp;</label>
                            <button class="btn btn-primary mt-4">+</button>

                        </div> -->

                        <div class="col-sm-4">
                            <label for="">Status</label>
                            <select name="status" class="form-control" id="status">
                                <option value="1">Disponível</option>
                                <option value="1">Inativo</option>
                            </select>
                        </div>
                    </div>   

                    <small>
                        <div align="center" class="mt-1" id="mensagem">
                            
                        </div>
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
                    <button type="submit" name="salvar" id="btnSubmit" class="btn btn-primary Salvar">Salvar</button>
                </div>
            </form>        
        </div>
        
    </div>
</div>
<!-- editar -->

</div>

<script>
    $(document).ready(function(){
        showData("<?=ROTA_GERAL?>/Hospede/getAll")
        .then((response) => createTable(response));
    });

    
    $('#novo').click(function(){
        $('#exampleModalLabel').text("Cadastro de Clientes");
        $('#modal').modal('show');        
    });

    function pesquisa() {
        // Obtém o valor do input
        var input = document.getElementById('txt_busca');
        var valor = input.value;
        
        // Executa a função com base no valor do input
        showData("<?=ROTA_GERAL?>/Hospede/buscaHospedes/"+valor)
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
        var tableContainer = document.getElementById('table');
        var existingTable = tableContainer.querySelector('table');
        if (existingTable) {
            existingTable.remove();
        }
        var thArray = ['Cod', 'Nome', 'CPF','Endereço', 'Telefone']; 
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

                thArray.forEach(function(value, key) {
                        var td = document.createElement('td');
                        td.textContent = item[key];
                        
                        if (item[key] === '1' && value === 'Status') {
                            td.textContent = 'Ativo';
                        } if (item[4] === '0' && value === 'Status') {
                            td.textContent = 'Suspenso';
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

                // var clearButton = document.createElement('button');
                // clearButton.innerHTML = '<i class="fa fa-trash"></i>';
                // buttonsTd.appendChild(clearButton);

                var activateButton = document.createElement('button');
                activateButton.innerHTML = '<i class="fas fa-calendar"></i>';
                activateButton.className = 'btn btn-activate';
                buttonsTd.appendChild(activateButton);

                // // Verificar o valor e definir o ícone e classe apropriados
                // if (item.status === '2') {           
                //     activateButton.querySelector('i').className = 'fa fa-times-circle text-danger';
                //     activateButton.title = 'Ocupado';
                // } else {
                //     activateButton.querySelector('i').className = 'fa fa-check-circle text-success';
                //     activateButton.title = 'Ativo';
                // }

                // Adicionando a ação para o botão "Editar"
                editButton.addEventListener('click', function() {
                var rowData = Array.from(tr.cells).map(function(cell) {
                    return cell.textContent;
                });
                // Chame a função desejada passando os dados da linha
                editarRegistro(rowData);
                });

                // Adicionando a ação para o botão "Editar"
                activateButton.addEventListener('click', function() {
                    var rowData = Array.from(tr.cells).map(function(cell) {
                        return cell.textContent;
                    });
                // Chame a função desejada passando os dados da linha
                    redirectWithModal(rowData);
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
        showData("<?=ROTA_GERAL?>/Hospede/buscaHospedePorId/" + rowData[0])
            .then((response) => preparaModalEditarHospedes(response.data));
        console.log(rowData[0]);
    }

    function activeRegistro(rowData)
    {
        showData("<?=ROTA_GERAL?>/Hospede/changeStatusHospedes/" + rowData[0])
            .then((response) => showSuccessMessage('Registro atualizado com sucesso!'));
    }

    function preparaModalEditarHospedes(data) {
        $('#nome').val(data[0].nome);
        $('#email').val(data[0].email);         
        $('#cpf').val(data[0].cpf);         
        $('#telefone').val(data[0].telefone);          
        $('#endereco').val(data[0].endereco);
        $('#tipo').val(data[0].tipo);
        $('#empresa').val(data[0].empresa);
        $('#status').val(data[0].status);
        $('#id').val(data[0].id);
        $('#btnSubmit').addClass('Atualizar');
        $('#exampleModalLabel').text("Atualizar Clientes");
        $('#modal').modal('show');   
    }

    $(document).on('click','.Salvar',function(){
        event.preventDefault();
        var id = $('#id').val();
        if(id == '') return createData('<?=ROTA_GERAL?>/Hospede/salvarHospedes', new FormData(document.getElementById("form"))).then( (response) => alert(response));
    
        return updateData('<?=ROTA_GERAL?>/Hospede/atualizarHospedes/' + id, new FormData(document.getElementById("form")), id);
    });

    function handleFormSubmit(form) {
        // Redireciona para a página de destino
        redirectWithModal(form.action);

        // Retorna false para evitar o envio do formulário pelo método tradicional
        return false;
    }

    function redirectWithModal(rowData) {
        var redirectUrl = "<?=ROTA_GERAL?>/Administrativo/Reservas/index"; 
            redirectUrl += "?hospede=" + rowData[0]  + '&showModal=true';
        
        // Redireciona para a página de destino
        window.location.href = redirectUrl;
    }

    const telefone = document.getElementById('telefone');

    telefone.addEventListener('input', function(e) {
    const value = e.target.value;
    const numbers = value.replace(/\D/g, '');
    const formatted = numbers.replace(/(\d{2})(\d{4,5})(\d{4})/, '($1) $2-$3');
    e.target.value = formatted;
    });

    const cpf = document.getElementById('cpf');

    cpf.addEventListener('input', function(e) {
    const value = e.target.value;
    const numbers = value.replace(/\D/g, '');
    const formatted = numbers.length > 11 ? numbers.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5') : numbers.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
    e.target.value = formatted;
    });

</script>

