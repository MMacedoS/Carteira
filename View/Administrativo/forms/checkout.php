
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
                <h4>Check-out</h4>
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
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome, cpf" id="txt_busca" aria-label="Search" value="<?=$request?>" aria-describedby="basic-addon2">
            </div>

            <div class="input-group-append">
                <button class="btn btn-primary" type="button" id="btn_busca">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>
<hr>
    <div class="row">        
            <?php
                $reservas = $this->buscaCheckout($request);                    
                if(is_array($reservas)) {
                    foreach ($reservas as $key => $value) {
                        ?>

                    <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <a href="#" class="hospedadas" id="<?=$value['id']?>">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                <?= $value['nome']?>
                                                </div>
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Saida:
                                                <?php echo implode('/',
                                                    array_reverse(
                                                        explode('-', $value['dataSaida'])
                                                    ));?>
                                                </div>                      
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Codigo:
                                                <?= $value['id']?>
                                                </div> 
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">APT <?= $value['numero']?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-door-open fa-2x text-gray-300"></i>
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
            <div class="modal-body" id="">  
                <form action="" method="post">
                    <input type="hidden" id="idReserva">
                    <div class="row">
                        <div class="col-sm-6 mb-2">
                            Hospedes: <p id="nomeHospede"></p>
                        </div>
                        <div class="col-sm-3 mb-2">
                            Codigo: <p id="codigoReserva"></p>
                        </div>
                        <div class="col-sm-3 mb-2">
                            Apartamento: <p id="numeroApartamento"></p>
                        </div>          
                        
                        <div class="col-sm-4 mb-2">
                            Consumo: <p id="totalHospedagem"></p>
                        </div>
                        <div class="col-sm-4 mb-2">
                            Diarias R$: <p id="totalDiarias"></p>
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
                        <button type="button" id="btnSubmit" class="btn btn-success executar-pagamento mr-4">Add Pagamento</button>
                        <button type="button" class="close mr-4 btn btn-danger" data-dismiss="modal" aria-label="Close">
                                X
                        </button>
                        <button type="button" name="salvar" id="btnCK" class="btn btn-primary executar-checkout ck">Executar</button>
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
            <div class="modal-body" id="">  
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
                            <label for="">Tipo</label>
                            <select name="tipo" class="form-control" id="tipo">
                                <option value="2">Cartão de Crédito</option>
                                <option value="3">Cartão Débito</option>
                                <option value="4">Déposito/PIX</option>
                                <option value="1">Dinheiro</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="">Valor</label>
                            <input type="number" step="0.01" min="0.00"  value="0" class="form-control" name="valor" id="valor">
                        </div>
                        <div class="col-sm-3">
                            <label for="">Descrição</label>
                            <input type="text" value="" class="form-control" name="descricao" id="descricao">
                        </div>
                        <div class="col-sm-3 text-center">
                            <label for="">&nbsp;</label>
                            <button type="submit" name="salvar" id="btnSubmit" class="btn btn-primary Salvar-pagamento mt-4"> &#10010; Pagamento</button>
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
let url = "<?=ROTA_GERAL?>/";

var totalConsumos = 0;
 var subTotal = 0;

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
                
            }
        })
        .done(function(data) {
            if(data.status === 200){
                return Swal.fire({
                    icon: 'success',
                    title: 'OhoWW...',
                    text: data.message,
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/checkout">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/checkout">Atualizar?</a>'
            })
        });
        return "";
    }

    function getRequisicaoGetViaAjax(controle_metodo, tipo) {            
        $.ajax({
            url: url+controle_metodo,
            method:'GET',
            processData: false,
            dataType: 'json',
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
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/checkout">Atualizar?</a>'
                }).then(()=>{
                    window.location.reload();    
                })
            } 
            if(data.status === 422)           
                return Swal.fire({
                    icon: 'warning',
                    title: 'ops...',
                    text: "Algo de errado aconteceu!",
                    footer: '<a href="<?=ROTA_GERAL?>/Administrativo/checkout">Atualizar?</a>'
            })
        });
        return "";
    }

    function calculaCheckout(consumos, diarias, pagamento)
    {        
        return (consumos + diarias) - pagamento;
    }

$(document).ready(function(){
        $(document).on("click",".fechar",function(){ 
            $('#modalCheckout').modal('hide');
        });
        
        $('.js-example-basic-single').select2();    
    
        $(document).on('click', '.hospedadas', function(){     
            var code=$(this).attr("id");       
            getRequisicaoGetViaAjax('Reserva/getDadosReservas/'+ code, "Checkout");                                           
        }); 
        
        $(document).on('click', '.executar-checkout', function(){
            var code=$('#idReserva').val();
            // envioRequisicaoGetViaAjax("Reserva/executaCheckout/" + code);  
            showData('<?=ROTA_GERAL?>/Reserva/getDadosReservas/'+ code).then(function(res){
                var total = calculaCheckout(
                    parseFloat(res.data[0].consumos),
                    parseFloat(res.data[0].diarias),
                    parseFloat(res.data[0].pag)
                ).toFixed(2);
           
                if(total == 0) {
                    showData("<?=ROTA_GERAL?>/Reserva/executaCheckout/" + code).then(function(res) {
                        showSuccessMessage(res.message);
                    });
                    return;
                } 
                showErrorMessage("Não foi possivel executar o checkout");
                return false;
            });
        });

        $(document).on('click', '.executar-pagamento', function(){
            var code=$('#idReserva').val();  
            showData("<?=ROTA_GERAL?>/Pagamento/getDadosPagamentos/"+ code)
            .then( (response) => {preparaModalHospedadas(response.data, "Pagamento"),  hideLoader()});     
        });
});

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
        
            default:
                break;
        }
        
    }

    function prepareCheckout(data)
    {
        $('#idReserva').val(data[0].id);
        $('#nomeHospede').text(data[0].nome);
        $('#codigoReserva').text(data[0].id);
        $('#numeroApartamento').text(data[0].numero);
        $('#totalHospedagem').text("R$ " + parseFloat(data[0].consumos).toFixed(2));        
        $('#totalDiarias').text("R$ " + parseFloat(data[0].diarias).toFixed(2));
        $('#totalPago').text("R$ " + data[0].pag);
        var total = calculaCheckout(
            parseFloat(data[0].consumos),
            parseFloat(data[0].diarias),
            parseFloat(data[0].pag)
        ).toFixed(2);
        data[0].status == 4 ? $('.ck').hide() : '';
        comparaDateMenorAtual(data[0].dataSaida) ? $('#btnCK').hide() : '';
        totalConsumos = ( parseFloat(data[0].consumos) + parseFloat(data[0].diarias) );
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
            $('#btnSubmit').attr('disabled',false);
        }
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
                        '<a href="#" id="'+element.id+'" class="alterar-pagamento" alt="alterar"><span style="font-size:25px;">&#9997;</span></a> &nbsp;'+
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

    $(document).on('click','.Salvar-pagamento',function(){
            event.preventDefault();
            var code=$('#idReserva').val();  
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
                        $('.executar-pagamento').click();
                        }
                    }
                })  
            }
        });

        $(document).on('click', '.remove-pagamento', function(){
            var code=$(this).attr("id");  
            $.ajax({
                url: url+ "Pagamento/getRemovePagamento/" + code ,
                method:'GET',
                processData: false,
                dataType: 'json     ',
                success: function(data){
                    if(data.status === 200){
                       $('.executar-pagamento').click();
                    }
                }
            })    
        });

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

</script>