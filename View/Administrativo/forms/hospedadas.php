
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

<div class="container-fluid">    
    <div class="form-group">
        <div class="row">
            <div class="col-sm-8">
                <h4>Os andamento</h4>
               
            </div>
            <div class="col-sm-4 text-right">
                <a href="<?=ROTA_GERAL?>/Administrativo/consultas" class="btn btn-primary" id="novo">Voltar</a>
            </div>
        </div>
    </div>
    
<hr>
    <div class="row">   
        <div class="input-group">         
            
            <div class="col-sm-11 mt-2">
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome ou cpf" id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
            </div>

            <div class="input-group-append ">
                <button class="btn btn-primary ml-3" type="button" id="btn_busca">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>
    <div class="row mt-2">
        Legendas: <span class="bg-warning">fianalizar hoje|Ajustar data</span>
    </div>
<hr>
    <div class="row">        
            <?php
                $reservas = $this->buscaHospedadas($request);     
                            
                if(is_array($reservas)) {
                    foreach ($reservas as $key => $value) {
                        ?>

                    <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card <?=$value['dataSaida'] <= date('Y-m-d') ? 'border-left-danger bg-warning' : 'border-left-primary'?> shadow h-100 py-2">
                                <a href="#" class="hospedadas" id="<?=$value['id']?>">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                <?= $value['nome']?>
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">R$ <?=self::valueBr($value['valor'])?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-door-closed fa-2x text-gray-300"></i>
                                            </div>                                                         
                                        </div>
                                    </div>
                                </a>    
                            </div>
                        </div>  
            <?php }//foreach
                }//if
            ?>       
    </div>    

<!-- editar -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dados da Ordem</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
                <button class="btn btn-danger close" onclick="sair()"> <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body"  > 
                <input type="hidden" id="id">
                <div class="row">
                    <div class="col-sm-6 campos_modal mb-2">
                        Cliente: <p id="hospede"></p>
                    </div>
                    <div class="col-sm-3 campos_modal mb-2">
                        Codigo: <p id="codigo"></p>
                    </div>                  
                    <div class="col-sm-4 campos_modal mb-2">
                        Inicio: <p id="entrada"></p>
                    </div>
                    <div class="col-sm-4 campos_modal mb-2">
                        Termino: <p id="saida"></p>
                    </div>
                    <div class="col-sm-4 campos_modal mb-2">
                        Valor: <p id="diaria"></p>
                    </div>
                    <div class="col-sm-4 campos_modal mb-2">
                        Material: <p id="consumo"></p>
                    </div>
                    <div class="col-sm-4 campos_modal mb-2">
                        Aluguel: <p id="diarias"></p>
                    </div>
                    <div class="col-sm-4 campos_modal mb-2">
                        Pagamento: <p id="pagamento"></p>
                    </div>
                   
                </div>
                
                <h6 class="modal-title"> Ações</h6>
                <hr>
                <div class="row">
                    <div class="col-sm-2 campos_modal text-left mb-2 ck">
                        <button class="btn btn-primary checkout">Check-out</button>
                    </div>
                    <div class="col-sm-2 campos_modal text-left mb-2">
                        <button class="btn btn-success pagamento">Pagamento </button>
                    </div>
                    <div class="col-sm-2 campos_modal text-left mb-2">
                        <button class="btn btn-warning consumo">Consumo </button>
                    </div>
                    <div class="col-sm-2 campos_modal text-left mb-2">
                        <button class="btn btn-danger diarias">Diarias </button>
                    </div>
                    <div class="col-sm-2 campos_modal text-left mb-2 ed">
                        <button class="btn btn-info editar">Editar</button>
                    </div>
                    <div class="col-sm-2 campos_modal text-left mb-2">
                        <button type="button" class="btn btn-dart imprimir text-danger">Imprimir</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<!-- editar -->

<!-- editar -->
<div class="modal fade" id="modalCheckout" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Deseja realizar o Check-out</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"  > 
                <form action="" method="post">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            Hospedes: <p id="nomeHospede"></p>
                        </div>
                        <div class="col-sm-3 mb-2">
                            Codigo: <p id="codigoReserva"></p>
                        </div>       
                        
                        <div class="col-sm-4 mb-2">
                            Valor: <p id="totalHospedagem"></p>
                        </div>
                        <div class="col-sm-4 mb-2">
                            Valor Pago: <p id="totalPago"></p>
                        </div>
                        <div class="col-sm-4 mb-2">
                            <p id="restante"></p>
                        </div>   
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="button" class="close mr-4" data-dismiss="modal" aria-label="Close">
                                X
                        </button>
                        <button type="button" name="salvar" disabled id="btn-checkout" class="btn btn-primary executar-checkout">Executar</button>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
<!-- editar -->
<!-- editar -->
<div class="modal fade" id="modalConsumo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelConsumo">Lança consumo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >  
                <form action="" id="form-consumo" method="post">
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
                                <tbody id="listaConsumo">

                                </tbody>
                            </table>
                           
                        </div>       
                        <div class="col-sm-6 text-right">
                            <small class="text-end">Registro(s) <span id="numeroConsumo">0</span></small> 
                        </div> 
                        <div class="col-sm-6 text-right">
                            <small class="text-end">Total R$ <span id="totalConsumo"></span></small> 
                        </div>      
                    </div>
                    <hr>
                    <div class="form-row">
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
                            <button type="submit" name="salvar" class="btn btn-primary Salvar-consumo mt-4"> &#10010; Adicionar consumo</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
<!-- editar -->

<div class="modal fade" id="modalDiarias" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelConsumo">Lança Diarias</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"   >
                <form action="" id="form-diarias" method="post">
                    <div class="row">
                        <div class="table-responsive" style="height: 250px">
                            <table class="table bordered">
                                <thead>
                                    <tr>
                                        <th >
                                            Data
                                        </th>
                                        <th >
                                            Calor
                                        </th>
                                        <th>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="listaDiarias">

                                </tbody>
                            </table>
                           
                        </div>       
                        <div class="col-sm-6 text-right">
                            <small class="text-end">Registro(s) <span id="numeroDiarias">0</span></small> 
                        </div> 
                        <div class="col-sm-6 text-right">
                            <small class="text-end">Total R$ <span id="totalDiarias"></span></small> 
                        </div>      
                    </div>
                    <hr>
                    <div class="form-row">
                        <div class="col-sm-4">
                            <label >Data</label>
                            <input type="date" class="form-control" name="data" id="dataDiaria">
                        </div>
                        <div class="col-sm-4">
                            <label >Valor</label>
                            <input type="number" step="0.01" min="0.00"  value="" class="form-control" name="valor" id="valorReserva">
                        </div>
                        <div class="col-sm-4 text-center">
                            <label >&nbsp;</label>
                            <button type="submit" name="salvar" class="btn btn-primary Salvar-diarias mt-4"> &#10010; Adicionar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>        
    </div>
</div>

<!-- editar -->
<div class="modal fade" id="modalPagamento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labelPagamento">Lançar Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">   
                <form action="" id="form-pagamento" method="post">
                    <div class="row">
                        <div class="table-responsive" style="height: 250px">
                            <table class="table bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            Data
                                        </th>
                                        <th class="d-none d-sm-table-cell">
                                            Descrição
                                        </th>
                                        <th class="d-none d-sm-table-cell">
                                            Tipo
                                        </th>
                                        <th>
                                            Valor
                                        </th>
                                        <th>
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="listaPagamento">

                                </tbody>
                            </table>
                           
                        </div>    
                        <div class="col-sm-3 text-right text-success">
                            <small class="text-end">Registro(s) <span id="numeroPagamento">0</span></small> 
                        </div> 

                        <div class="col-sm-3 text-right text-danger">
                            <small class="text-end">Consumos(s) R$ <span id="totalConsumos"></span> </small> 
                        </div>   

                        <div class="col-sm-6 text-right">
                            <small class="text-end">Total R$ <span id="totalPagamento"></span></small> 
                        </div>      
                    </div>
                    <hr>
                    <div class="form-row">
                        <div class="col-sm-3">
                            <label >Tipo</label>
                            <select name="tipo" class="form-control" id="tipo">
                                <option value="2">Cartão de Crédito</option>
                                <option value="3">Cartão Débito</option>
                                <option value="4">Déposito/PIX</option>
                                <option value="1">Dinheiro</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label >Valor</label>
                            <input type="number" step="0.01" min="0.00"  value="0" class="form-control" name="valor" id="valor">
                        </div>
                        <div class="col-sm-3">
                            <label >Descrição</label>
                            <input type="text" value="" class="form-control" name="descricao" id="descricao">
                        </div>
                        <div class="col-sm-3 text-center">
                            <label >&nbsp;</label>
                            <button type="submit" name="salvar" class="btn btn-primary Salvar-pagamento mt-4"> &#10010; Pagamento</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
<!-- editar -->

<!-- cons -->
    <div id="changeConsumo" class="modal-static">
        <div class="modal-static-content">
            <span class="close" id="closeModal">&times;</span>
            <h2>Alterar Consumo</h2>
            <form id="swal-form-consumo">
                <input type="hidden" name="swal_id_consumo" id="swal_id_consumo">
                <div class="col-sm-12">
                    <label>Qtdo<input id="swal-cons-input1" name="quantidade" class="form-control" type="number"></label>
                </div>
                <div class="col-sm-12">
                    <label>Valor<input id="swal-cons-input2" class="form-control" name="valor" placeholder="valor" type="number" step="0.1" min="0"></label>
                </div>
                <div class="col-sm-12 float-right">
                    <button id="saveChangesConsumo" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    
<!-- diaria -->
<div id="changeDiaria" class="modal-static">
        <div class="modal-static-content">
            <span class="close" id="closeModal">&times;</span>
            <h2>Alterar Diária</h2>
            <form id="swal-form-diaria">
                <input type="hidden" name="" id="swal_id_diaria">
                <div class="col-sm-12">
                    <label>Data<input id="swal-dia-input1" name="data" class="form-control" type="date"></label>
                </div>
                <div class="col-sm-12">
                    <label>Valor<input id="swal-dia-input2" class="form-control" name="valor" placeholder="valor" type="number" step="0.1" min="0"></label>
                </div>
                <div class="col-sm-12 float-right">
                <button id="saveChangesDiaria" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>

<!-- editar -->
<div class="modal fade" id="modalReserva" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Atualização da Reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
           
            <form action="" id="formReserva" method="POST">
                <div class="modal-body">                                
                    <div class="form-row">
                        <input type="hidden" disabled id="inp-id" >
                        <input type="hidden" disabled id="opcao" value="" >
                        <div class="col-sm-6">
                            <label >Data Entrada</label>
                            <input type="date" name="entrada" id="inp-entrada" class="form-control">
                        </div>

                        <div class="col-sm-6">
                            <label >Data Saida</label>
                            <input type="date" name="saida" id="inp-saida" class="form-control">
                        </div>                                    
                    </div>
                    <div class="form-row" id="div_apartamento">

                        <div class="col-sm-12">
                            <label >Hospede</label><br>
                            <select id="select_hospedes" class="selectized" name="hospedes">
                               
                            </select>
                        </div>                      

                        <div class="col-sm-4">
                            <label >Tipo</label><br>
                            <select class="form-control" name="tipo" id="inp-tipo">
                                <option value="1">Serviço</option>
                                <option value="2">Material</option>
                                <option value="3">Manutenção</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label >Status</label><br>
                            <select class="form-control" name="status" id="inp-status">
                                <option value="1">Orçamento</option>
                                <option value="2">Confirmada</option>
                                <option value="3">andamento</option>
                                <option value="4">Concluida</option>
                                <option value="5">Cancelada</option>
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label >Valor</label>
                            <input type="number" class="form-control" onchange="valores()" name="valor" step="0.01" min="0.00" value="" id="inp-valor">
                        </div>

                        <div class="col-sm-12">
                            <label >observação</label><br>
                            <textarea name="observacao" class="form-control" value="tudo certo" id="inp-observacao" cols="30" rows="5"> &nbsp;</textarea>
                        </div>
                    </div>   

                    <small>
                        <div align="center" class="mt-1" id="mensagem"></div>
                        <div align="right" class="mt-1 fs" id="valores"></div>
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="sair" data-dismiss="modal">Fechar</button>
                    <button type="button" name="salvar" id="btnSubmit" class="btn btn-primary SalvarReserva">Salvar</button>
                </div>
            </form>        
        </div>
        
    </div>
</div>
<!-- editar -->

</div>
<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
<script>
    let url = "<?=ROTA_GERAL?>/";
    var id_reserva = null

    var totalConsumos = 0;
    var subTotal = 0;
    var hospede = null;

      function valores(){
        var dias = moment($('#inp-saida').val()).diff(moment($('#inp-entrada').val()), 'days');
         var valor = $("#valor").val();
            $('#valores').removeClass('text-success');
            $('#valores').addClass('text-success');
            $('#valores').text("Valor Total da Estadia: R$" + valor * dias);
      }
      
      function envioRequisicaoPostViaAjax(controle_metodo, dados) {
          $.ajax({
              url: url+controle_metodo,
              method:'POST',
              data: dados,
              dataType: 'JSON',
              contentType: false,
	          cache: false,
	          processData:false,
              success: function(data){
                  if(data.status === 422){
                      $('#mensagem').removeClass('text-danger');
                      $('#mensagem').addClass('text-success');
                      $('#mensagem').text(data.message);
                  }
              }
          })
          .done(function(data) {
              if(data.status === 201){
                  return Swal.fire({
                      icon: 'success',
                      title: 'OhoWW...',
                      text: data.message,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/reservas">Atualizar?</a>'
                  }).then(()=>{
                    window.location.reload();    
                })
              }
              if(data.status === 200){
                    return ;
              }
              return Swal.fire({
                      icon: 'warning',
                      title: 'ops...',
                      text: data.message,
                      footer: '<a href="<?=ROTA_GERAL?>/Administrativo/reservas">Atualizar?</a>'
                  })
          });
      }

    function envioRequisicaoGetViaAjax(controle_metodo) {            
        $.ajax({
            url: url+controle_metodo,
            method:'GET',
            processData: false,
            dataType: 'json     ',
            success: function(data){
                if(data.status === 201){
                    preparaModalEditarReserva(data.data);
                }
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/hospedadas">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/hospedadas">Atualizar?</a>'
            })
        });
        return "";
    }

    function getRequisicaoGetViaAjax(controle_metodo, tipo) {            
        $.ajax({
            url: url+controle_metodo,
            method:'GET',
            processData: false,
            dataType: 'json     ',
            success: function(data){
                if(data.status === 201){
                    preparaModalHospedadas(data.data, tipo);
                }
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/hospedadas">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/hospedadas">Atualizar?</a>'
            })
        });
        return "";
    }

    function preparaModalEditarReserva(data) 
    {
        var id_reserva = null;
        $('#id').val(data[0].id);
        $('#hospede').text(data[0].nome);
        $('#codigo').text(data[0].id);
        $('#entrada').text(formatDate(data[0].dataEntrada));
        $('#saida').text(formatDate(data[0].dataSaida));
        $('#diaria').text("R$ " + parseFloat(data[0].valor).toFixed(2));

        totalConsumos = parseFloat(data[0].consumos) + parseFloat(data[0].diarias);
        subTotal = calculaCheckout(
            parseFloat(data[0].consumos),
            parseFloat(data[0].pag)
        );
        data[0].status == 4 ? $('.ck').hide() : '';
        data[0].status == 4 ? $('.ed').css('display','none') : '';
        comparaDateMenorAtual(data[0].dataSaida) ? $('.ck').hide() : '';
        $('#consumo').text("R$ " + parseFloat(data[0].consumos).toFixed(2));
        $('#diarias').text("R$ " + parseFloat(data[0].diarias).toFixed(2));
        $('#pagamento').text("R$ " + parseFloat(data[0].pag).toFixed(2));
        $('#modal').modal('show');   
    }

    function comparaDateMenorAtual(data) {
        var dataAtual =  moment();
        var dataComparar = moment(data);
        console.log(dataComparar);
        console.log(dataAtual);
        if (dataComparar.isBefore(dataAtual, 'day')) {
            return true
        }

        return false;
    }

    function preparaModalHospedadas(data, tipo) 
    {
        $('#label'+tipo).text(tipo);
        $('#modal'+tipo).modal('show');  

        switch (tipo) {
            case 'Consumo':
                    prepareTableConsumo(data);
                break;

                case 'Pagamento':
                    prepareTablePagamento(data);
                break;

                case 'Checkout':
                    prepareCheckout(data);
                break;
                
                case 'Diarias':
                    prepareTableDiarias(data);
                break;
        
            default:
                break;
        }
        
    }

    function prepareTableConsumo(data)
    {
        $("#listaConsumo tr").detach();
        data.map(element => {
            var newOption = $('<tr>'+
                    '<td>'+element.descricao+'</td>' +
                    '<td class="d-none d-sm-table-cell">'+formatDateWithHour(element.created_at)+'</td>' +
                    '<td>'+element.quantidade+'</td>' +
                    '<td class="d-none d-sm-table-cell">R$ '+element.valorUnitario+'</td>' +
                    '<td class="d-none d-sm-table-cell">R$ '+
                    parseFloat(element.valorUnitario * element.quantidade).toFixed(2)
                    +'</td>' +
                    '<td>'+
                        '<a href="#" id="'+element.id+'" class="alterar-consumo" alt="alterar"><span style="font-size:25px;">&#9997;</span></a> &nbsp;'+
                        '<a href="#" id="'+element.id+'" class="remove-consumo" >&#10060;</a>'+
                    '</td>'+
                '</tr>');
            $("#listaConsumo").append(newOption);
        })

        $('#numeroConsumo').text(data.length);
        $('#totalConsumo').text(calculaConsumo(data).toFixed(2));
        totalConsumos = calculaConsumo(data);
    }

    function prepareTableDiarias(data)
    {
        $("#listaDiarias tr").detach();
        data.map(element => {
            var newOption = $('<tr>'+
                    '<td class="d-none d-sm-table-cell">'+formatDate(element.data)+'</td>' +
                    '<td>R$ '+
                    parseFloat(element.valor).toFixed(2)
                    +'</td>' +
                    '<td>'+
                        '<a href="#" id="'+element.id+'" class="alterar-diarias" alt="alterar"><span style="font-size:25px;">&#9997;</span></a> &nbsp;'+
                        '<a href="#" id="'+element.id+'" class="remove-diarias" >&#10060;</a>'+
                    '</td>'+
                '</tr>');
            $("#listaDiarias").append(newOption);
        })

        $('#numeroDiarias').text(data.length);
        $('#totalDiarias').text(calculaDiarias(data).toFixed(2));
        // totalDiarias = calculaConsumo(data);
    } 

    function prepareTablePagamento(data)
    {
        $("#listaPagamento tr").detach();
        data.map(element => {
            var newOption = $('<tr>'+                    
                    '<td>'+formatDate(element.dataPagamento)+'</td>' +
                    '<td class="d-none d-sm-table-cell">'+element.descricao+'</td>' +
                    '<td class="d-none d-sm-table-cell">'+
                        prepareTipo(element.tipoPagamento)
                    +'</td>' +
                    '<td>R$ '+parseFloat(element.valorPagamento).toFixed(2)+'</td>' +
                    '<td>'+            
                        // '<a href="#" id="'+element.id+'" class="alterar-pagamento" alt="alterar"><span style="font-size:25px;">&#9997;</span></a> &nbsp;'+
                        '<a href="#" id="'+element.id+'" class="remove-pagamento" >&#10060;</a>'+
                    '</td>'+
                '</tr>');
            $("#listaPagamento").append(newOption);
        })

        $('#numeroPagamento').text(data.length);
        $('#totalConsumos').text(parseFloat(totalConsumos).toFixed(2));
        $('#totalPagamento').text(calculaPagamento(data).toFixed(2));
        if(subTotal > 0){
            $('#valor').val(subTotal);
        }
    }

    function prepareCheckout(data)
    {
        $('#nomeHospede').text(data[0].nome);
        $('#codigoReserva').text(data[0].id);
        $('#totalHospedagem').text("R$ " + (parseFloat(data[0].consumos) + parseFloat(data[0].diarias)).toFixed(2));
        $('#totalPago').text("R$ " + data[0].pag);
        var total = calculaCheckout(
            parseFloat(data[0].consumos),
            parseFloat(data[0].pag),
            parseFloat(data[0].diarias)
        ).toFixed(2);
        
        if(total > 0) {
            $('#restante').addClass('text-danger');
            $('#restante').text("Resta pagar R$ " + total);
            return ;
        }
        $('#restante').addClass('text-success');
        $('#restante').text("Crédito disponivel R$ " + total * (-1));

        if(total == 0)
        {
            $('#restante').text("Fechamento disponivel");
            $('#btn-checkout').attr('disabled',false);
        }
    }

    function calculaConsumo(data)
    {
        var valor = 0;
        data.forEach(element => {
            valor += element.valorUnitario * element.quantidade;
        });

        return valor;
    }

    function calculaDiarias(data)
    {
        var valor = 0;
        data.forEach(element => {
            valor += parseFloat(element.valor);
        });

        return valor;
    }

    function calculaCheckout(consumos, pagamento, diarias)
    {        
        return (consumos + diarias) - pagamento;
    }

    $('#btn_busca').click(function(){
        var texto = $('#txt_busca').val();        
        window.location.href ="<?=ROTA_GERAL?>/Administrativo/hospedadas/"+texto;
    });

    $(document).ready(function(){
        $('#select_hospedes').selectize({});
        setInterval(function(){
            if(id_reserva){
                showData("<?=ROTA_GERAL?>/Reserva/getDadosReservas/"+ id_reserva)
                .then((response) => {
                    preparaModalEditarReserva(response.data)
                    hideLoader()
                });
            }
        }, 60000);
        $(document).on("click",".fechar",function(){ 
            id_reserva = null;
            $('#modalCheckout').modal('hide');
        });
        
        $('.js-example-basic-single').select2();    
    
        $(document).on('click', '.hospedadas', function(){     
            let code=$(this).attr("id"); 
            id_reserva = code;    
            showData("<?=ROTA_GERAL?>/Reserva/getDadosReservas/"+ code)
            .then((response) => {preparaModalEditarReserva(response.data),  hideLoader()});
        });    
        
        $(document).on('click', '.checkout', function(){
            let code=$("#id").val(); 
            id_reserva = code;    
            showData("<?=ROTA_GERAL?>/Reserva/getDadosReservas/"+ code)
            .then( (response) => {preparaModalHospedadas( response.data, "Checkout"),  hideLoader()});  
        });

        $(document).on('click', '.pagamento', function()
        {
            let code=$("#id").val();  
            id_reserva = code;
            showData("<?=ROTA_GERAL?>/Pagamento/getDadosPagamentos/"+ code)
            .then( (response) => {preparaModalHospedadas( response.data, "Pagamento"),  hideLoader()});                      
        });

        $(document).on('click', '.consumo', function(){
            let code=$("#id").val();  
            id_reserva = code;
            $('#produto option').detach();
            $.ajax({
                url: url+ "Produto/getDadosProdutos",
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 201){                    
                        data.data.map(element => {
                            var newOption = $('<option value="' + element.id + '">' + element.descricao + '</option>');
                            $("#produto").append(newOption);
                        })
                        showData("<?=ROTA_GERAL?>/Consumo/getDadosConsumos/"+ code)
                        .then( (response) => {preparaModalHospedadas( response.data, "Consumo"),  hideLoader()});   
                    }
                }
            })    
            
        });

        $(document).on('click', '.diarias', function(){
            let code=$("#id").val();  
            id_reserva = code;
            showData("<?=ROTA_GERAL?>/Reserva/getDadosDiarias/"+ code)
            .then( (response) => preparaModalHospedadas(response.data, "Diarias"));   
        });

        $(document).on('click', '.editar', function(){
            let code=$("#id").val();  
            id_reserva = code;  
            populaHospede(hospede);          
           
            $.ajax({
                url: url+ 'Reserva/getDadosReservas/'+ code,
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 201){
                        $('#inp-entrada').val(data.data[0]['dataEntrada']);
                        $('#inp-saida').val(data.data[0]['dataSaida']);
                        hospede = data.data[0].hospede_id;
                        populaHospede(hospede);
                        $('#inp-tipo').val(data.data[0]['tipo']);
                        $('#inp-valor').val(data.data[0]['valor']);
                        $('#inp-status').val(data.data[0]['status']);
                        $('#inp-observacao').val(data.data[0]['obs']);
                        $('#inp-placa').val(data.data[0]['placa']);
                        $('#inp-qtdeHosp').val(data.data[0].qtde_hosp);
                        $('#exampleModalLabel').text("Dados Informativos");
                        $('#modalReserva').modal('show');  
                    }
                }
            })    
            
        });

        $(document).on('click', '.SalvarReserva', function(){
            let code=$("#id").val();  
            id_reserva = code;
            updateData('<?=ROTA_GERAL?>/Reserva/atualizarReserva/'+ code, new FormData(document.getElementById("formReserva")));            
        });


        $(document).on('click','.Salvar-pagamento',function() {
            event.preventDefault();            
            $('.Salvar-pagamento').prop('disabled', true);
            let code=$("#id").val(); 
            id_reserva = code;
            if ($('#valor').val() > 0) {
                $.ajax({
                    url: url+ 'Pagamento/addPagamento/' + code,
                    method:'POST',
                    data: new FormData(document.getElementById("form-pagamento")),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    success: function(data){
                        if(data.status === 201){
                            $('.pagamento').click();
                                setInterval(() => {
                                    $('.Salvar-pagamento').prop('disabled', false);
                                }, 500);                            
                        }
                    }
                })  
            }
        });

        $(document).on('click','.Salvar-consumo',function(){
            event.preventDefault();
            let code=$("#id").val(); 
            id_reserva = code;
            $.ajax({
                url: url+ 'Consumo/addConsumo/' + code,
                method:'POST',
                data: new FormData(document.getElementById("form-consumo")),
                processData: false,
                dataType: 'json',
                contentType: false,
	            cache: false,
                success: function(data){
                    if(data.status === 201){
                       $('.consumo').click();
                    }
                }
            })  
        });

        $(document).on('click', '.alterar-consumo', function(){
            let code=$(this).attr("id");  
            
            id_reserva = code;
            $.ajax({
                url: url+ "Consumo/getConsumoPorId/" + code ,
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 201){
                        $('#swal_id_consumo').val(code);
                        $('#swal-cons-input1').val(data.data[0].quantidade);
                        $('#swal-cons-input2').val(data.data[0].valorUnitario);
                        $('#saveChangesDiaria').prop('disabled', false);
                        $("#changeConsumo").modal('show');
                    }
                }
            })    
        });

        $(document).on('click', '#saveChangesConsumo', function(event) {
            event.preventDefault();
            $('#saveChangesDiaria').prop('disabled', true);
            let code=$("#swal_id_consumo").val(); 
            envioRequisicaoPostViaAjax('Consumo/updateConsumo/'+ code, new FormData(document.getElementById("swal-form-consumo")));                                  
            $('.consumo').click();
        });

        $(document).on('click', '#saveChangesDiaria', function(event) {
            event.preventDefault();
            $('#saveChangesDiaria').prop('disabled', true);
            let code=$("#swal_id_diaria").val(); 
            envioRequisicaoPostViaAjax('Reserva/updateDiaria/'+ code, new FormData(document.getElementById("swal-form-diaria")));                                  
            $('.diarias').click();
        });

        $(document).on('click', '.alterar-diarias', function(event){
            event.stopPropagation();

            let code=$(this).attr("id");  

            id_reserva = code;
            
            $.ajax({
                url: url+ "Reserva/getDiariasPorId/" + code ,
                method:'GET',
                processData: false,
                dataType: 'json',
                success: function(data) {
                    if(data.status === 201){
                        
                        $('#swal_id_diaria').val(code);
                        $('#swal-dia-input1').val(data.data[0].data);
                        $('#swal-dia-input2').val(data.data[0].valor);
                        $('#saveChangesDiaria').prop('disabled', false);
                        $("#changeDiaria").modal('show');                      
                        
                    }
                }
            })    
        });

        $(document).on('click', '.remove-consumo', function(){
            let code=$(this).attr("id");  
            id_reserva = code;
            $.ajax({
                url: url+ "Consumo/getRemoveConsumo/" + code ,
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 200){
                       $('.consumo').click();
                    }
                }
            })    
        });

        $(document).on('click', '.remove-diarias', function(){
            let code=$(this).attr("id");  
            id_reserva = code;
            $.ajax({
                url: url+ "Reserva/getRemoveDiarias/" + code ,
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 200){
                       $('.diarias').click();
                    }
                }
            })    
        });

        $(document).on('click', '.remove-pagamento', function(){
            let code=$(this).attr("id"); 
            id_reserva = code; 
            $.ajax({
                url: url+ "Pagamento/getRemovePagamento/" + code ,
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 200){
                       $('.pagamento').click();
                    }
                }
            })    
        });

        $(document).on('click', '.executar-checkout', function(){
            let code=$("#id").val(); 
            id_reserva = code;
            showData("<?=ROTA_GERAL?>/Reserva/executaCheckout/" + code).then(function(){ showSuccessMessage('executado com sucesso!'); hideLoader()});  
        });

        $(document).on('click', '.imprimir', function(event){
            event.preventDefault();
            let code=$("#id").val(); 
            id_reserva = code;
            redirectUrl(code)
        });
    });

    function redirectUrl(params)
    {
        window.open('<?=ROTA_GERAL?>/Administrativo/cliente/' + params, '_blank');
    }

    function sair(){
        redirecionarPagina("<?=ROTA_GERAL?>/Administrativo/hospedadas");
    }

    function buscaApartamento(
        dataEntrada,
        dataSaida
    ){
        $.ajax({
            url: '<?=ROTA_GERAL?>/Reserva/reservaBuscaPorData/',
            method:'POST',
            data: {
                dataEntrada: dataEntrada,
                dataSaida: dataSaida
            },
            dataType: 'JSON',
            success: function(data){
                if(opcao == '')
                    populaHospede(hospede);                  
            }
        })
    }

    function populaHospede(hospede = null){
        showData("<?=ROTA_GERAL?>/Hospede/getAllSelect")
       .then((response) => {
            let hospedes = response.map(element => {
               return { id: element.id, title: element.nome}
            });
            prepareSelect(hospedes, '#select_hospedes', hospede);
       });

    }

    
</script>