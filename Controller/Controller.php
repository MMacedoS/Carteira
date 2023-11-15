<?php

require_once "./Config/autoload.php";
require_once 'Trait/DateTrait.php';
require_once 'Trait/GeneralTrait.php';
require_once 'Trait/RequestTrait.php';

require_once __DIR__ . "/../Trait/ErrorLoggingTrait.php";

class Controller {

    use GeneralTrait;
    use DateTrait;
    use RequestTrait;
    use ErrorLoggingTrait;

    public $active = "";

    public $images;
    public $cards;
    public $color;
    public $text;
    public $imagesSite;

    protected $site_model;
    protected $disciplina_id;
    protected $background;
    protected $url;

    public function __construct(){
        $this->site_model = new SiteModel();
        $this->url = ROTA_GERAL;
    }

    public function viewSite($view) {
        require "View/Site/{$view}.php";
    }

    public function viewAdmin($view, $request = null, $page = null) {
        $sub_view = $page;
        $painel = $view;
        
        if(!is_null($request)) {
            $dados = explode('_@_', $request);  
            $texto = $dados[0];
            if(count($dados) == 1){
                $chave = str_replace('page=', '', $dados[0]);
                $texto = '';
            }      
            
            $status = $dados[1];
            $entrada = $dados[2];
            $saida = $dados[3];
        }

        require "View/Administrativo/views.php";
    }

    public function viewImpressao($painel, $dados) {
        require "View/Administrativo/impressao.php";
    }
}
