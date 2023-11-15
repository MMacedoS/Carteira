<?php

require_once "./Config/autoload.php";
require_once __DIR__ . "/../Trait/ErrorLoggingTrait.php";

class LoginController extends Controller{  
    use ErrorLoggingTrait;

    protected $login_model;
    protected $url;

    public function __construct() 
    {        
        $this->login_model = new LoginModel();
        $this->validLogin();
        $this->url = ROTA_GERAL;
    }

    public function findParamByParam($param) {
        $app_model = new AppModel();     
        return $app_model->buscaParamByParam($param)[0];
    }

    public function index() 
    {       
        require 'View/Login/index.php';
    }

    private function validLogin() 
    {
        if (
            isset($_SESSION['painel']) || 
            isset($_SESSION['logado'])
        ) {
            return $this->preparePainel();
        }
    }

    private function preparePainel() 
    {
        $painel_session =  $_SESSION['painel'];

        switch ($painel_session) {
           
            case 'Administrador':
                header('Location: '.$this->url.'/Administrativo');
            break;

            case 'Recepcao':
                header('Location: '.$this->url.'/Administrativo');
            break;
            
        }
    }

    public function logar() {
        
       $login = $this->login_model->logar($_POST);
       if(!is_null(
            $login
            )
        ) {
            $_SESSION['code'] = $login[0]['id'];
            $_SESSION['nome'] = $login[0]['nome'];
            $_SESSION['painel'] = $login[0]['painel'];
            $_SESSION['logado'] = true;            
        }
        
        return header('Location: '.$this->url.'/Login');
    }

    public function logouf() 
    {
        session_start();
        session_destroy();            
        return header('Location: '.$this->url.'/Login');    
    }


}