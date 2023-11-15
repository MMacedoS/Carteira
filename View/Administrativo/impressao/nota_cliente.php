<style>
    #head td{
        width: 15% !important;
    }
</style>
<?php 
    $nome_empresa = $this->findParamByParam('nome_site'); 
    $endereco_empresa = $this->findParamByParam('nome_site'); 
    $number_empresa = $this->findParamByParam('whatsapp'); 
?>

<button class="btn btn-danger" onclick="imprimir()" id="btn">Imprimir</button>
<div id="contents_inputs" class="container mt-4">
    <div class="column mb-3">
        <h4 class="text-center"><?=$nome_empresa['valor']?></h4>   
        <hr> 
        <p><b>Endereço:</b> <?=$endereco_empresa['valor']?></p>  
        <p><b>Tel: </b><?=self::formatPhoneNumber($number_empresa['valor'])?></p>
    </div>
   <hr>
   <p>Dados Hospedagem</p>
    <div class="container">
        <div class="row">
            <table class="table table-sm mr-4" id="head">
                <thead>
                    <tr>
                        <td>
                            Hospede: 
                        </td>
                        <th colspan="3">
                            <?= $dados->nome?>
                        </th>
                        <td>
                            Apartamento:
                        </td>
                        <th>
                            <?= $dados->numero?>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            Data Entrada:
                        </td>
                        <th>
                            <?= self::prepareDateBr($dados->dataEntrada)?>
                        </th>
                        <td>
                            Data Saida:
                        </td>
                        <th>
                        <?= self::prepareDateBr($dados->dataSaida)?>
                        </th>
                        <td>
                            Vl. Hospedagem
                        </td>
                        <th>
                            R$: <?= self::valueBr($dados->valor)?>
                        </th>
                    </tr>
                </thead>
            </table>
            <h6><b>Consumos</b></h6>
            <table class="table table-sm mt-3 mr-4" id="content">
                    <thead>
                        <td>Descrição</td>
                        <td>Data</td>
                        <td>Quantidade</td>
                        <td>Valor</td>
                        <td>Total</td>
                    </thead>
                    <tbody>
                        <?php 
                            if(!empty($dados->lista_consumos))
                            {
                                foreach ($dados->lista_consumos as $key => $value) {?>
                                    <tr>
                                        <td><?= $value['descricao']?></td>
                                        <td>
                                            <?= self::prepareDateWithTimeBr($value['created_at'])?>
                                        </td>
                                        <td>
                                            <?= intVal($value['quantidade'])?>
                                        </td>                             
                                        <td>
                                            <?= self::valueBr($value['valorUnitario'])?>
                                        </td>     
                                        <td>
                                            <?= self::valueBr(($value['valorUnitario'] * $value['quantidade']))?>
                                        </td>                           
                                    </tr>     
                        <?php       }
                            echo '<tr><td colspan="4" class="text-right">Total </td>
                            <td>R$ ' . self::valueBr($dados->consumos) . '</td>
                            </tr>';
                            
                            }
                        ?>
                    </tbody>
            </table>
            <h6><b>Pagamentos</b></h6>
            <table class="table table-sm mt-3 mr-4" id="content">
                <thead>
                    <td>Descrição</td>
                    <td>Data</td>
                    <td>Tipo</td>
                    <td>Valor</td>
                </thead>
                <tbody>
                    <?php 
                            if(!empty($dados->pagamentos))
                            {
                                foreach ($dados->pagamentos as $key => $value) {
                                    // var_dump($value);
                                    ?>                                
                                    <tr>
                                        <td><?= $value['descricao']?></td>
                                        <td>
                                            <?= self::prepareDateWithTimeBr($value['created_at'])?>
                                        </td>
                                        <td>
                                            <?= self::prepareTipo($value['tipoPagamento'])?>
                                        </td>                             
                                        <td>
                                            <?= self::valueBr($value['valorPagamento'])?>
                                        </td>                              
                                    </tr>     
                        <?php       }
                            echo '<tr><td colspan="3" class="text-right">Total </td>
                                    <td>R$ ' . self::valueBr($dados->pag) . '</td>
                            </tr>';
                            }
                        ?>
                </tbody>
            </table>
        </div>
    </div>
    <hr>
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-4">
                Consumo + Hospedagem R$ <?= self::valueBr($dados->consumos + $dados->pag)?>
            </div>
            <div class="col-sm-4">
                Pagamentos Lançados R$ <?= self::valueBr($dados->pag)?>
            </div>
            <div class="col-sm-4">
                <?php 
                $total = (($dados->consumos + $dados->pag) - $dados->pag);
                
                if($total < 0){
                    echo 'Ainda resta consumir R$ ' . self::valueBr(
                        $total *(-1), 
                       );
                }else {
                    echo 'Resta pagar R$ ' . self::valueBr(
                        $total, 
                        );
                }

               
                ?>
            </div>
        </div>
        <div class="column">
            <h6 class="text-center">Agradecemos sua preferência!</h6>    
        </div>
    </div>
</div>