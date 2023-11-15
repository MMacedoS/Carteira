<?php

class ImpressaoController extends \Controller{
    
    protected $reserva_model;
    protected $consumo_model;
    protected $pagamento_model;
    protected $app_model;

    public function __construct() 
    {        
        $this->reserva_model = new ReservaModel();   
        $this->consumo_model = new ConsumoModel();    
        $this->pagamento_model = new PagamentoModel();   
        $this->app_model = new AppModel();          
    }
    
    public function cliente($request = null)
    {       
        $dados = (object)$this->reserva_model->getDadosReservas($request)['data'][0];
        $consumos = (object)$this->consumo_model->getDadosConsumos($request)['data'];
        $diarias = (object)$this->consumo_model->getDadosDiarias($request)['data'];
        var_dump($diarias);
        $pagamentos = $this->pagamento_model->getDadosPagamentos($request)['data'];
        $dados->lista_consumos = $consumos;
        $dados->lista_diarias = $diarias;
        $dados->pagamentos = $pagamentos;
        // $this->viewImpressao('nota_cliente',$dados);
    }   

    public function findParamByParam($param) {
        return $this->app_model->buscaParamByParam($param)[0];
    }

}