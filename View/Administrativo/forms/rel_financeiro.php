<?php
  $movimentos = $this->buscaEntrada($request);
?>
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
                <h4>Movimentações Entrada</h4>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary" id="novo">Adicionar</button>
            </div>
        </div>
    </div>
<hr>
    <div class="row">   
        <div class="input-group">
            <!-- <div class="col-sm-12 mb-2">
                <input type="text" class="form-control bg-light border-0 small" placeholder="busca por nome, cpf" id="txt_busca" aria-label="Search" value="<=$request?>" aria-describedby="basic-addon2">
            </div> -->
            <div class="col-sm-3 mb-2">
                <input type="date" name="" id="busca_entrada" class="form-control" value="<?=@$entrada ? $entrada : Date('Y-m-d') ?>">
            </div>
            <div class="col-sm-3 mb-2">
                <input type="date" name="" id="busca_saida" class="form-control" value="<?=@$saida ? $saida : Date('Y-m-d')?>">
            </div>
            <!-- <div class="col-sm-3 mb-2">
                <select name="" id="" class="form-control">
                    <option value="">Selecione uma empresa</option>
                    <option value="">Confirmada</option>
                    <option value="">Hospedadas</option>
                </select>
            </div>     -->
            <div class="col-sm-3 mb-2">
                <select name="" id="busca_status" class="form-control">
                    <option value="">Selecione o Tipo</option>
                    <option <?=$status == 1 ? 'selected': '';?> value="1">Dinheiro</option>
                    <option <?=$status == 2 ? 'selected': '';?> value="2">Cartão de Crédito</option>
                    <option <?=$status == 3 ? 'selected': '';?> value="3">Cartão de Débito</option>
                    <option <?=$status == 4 ? 'selected': '';?> value="4">Deposito/PIX</option>
                </select>
            </div>  
            <div class="input-group-append float-right">
                <button class="btn btn-primary ml-3" type="button" id="btn_busca">
                    <i class="fas fa-search fa-sm"></i>
                </button>   
            </div>
        </div>
    </div>
<hr>
   

<script src="<?=ROTA_GERAL?>/Estilos/js/moment.js"></script>
