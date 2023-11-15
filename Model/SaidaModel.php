<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';
require_once 'Trait/DateModelTrait.php';
require_once 'Trait/DataDropTrait.php';

class SaidaModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    use DateModelTrait;
    use DataDropTrait;
    
    protected $conexao;

    protected $model = 'saida';

    public function __construct() 
    {
        $this->model = 'saida';
        $this->conexao = ConexaoModel::conexao();
    }

    // public function buscaSaida($off = 0)
    // {
    //     if(is_null($off) || empty($off))
    //     {
    //         return $this->saida($off = 0);
    //     } 
    //     $dados = explode('_@_', $off);   

    //     if(count($dados) == 1)
    //     {
    //         $chave = str_replace('page=', '', $dados[0]);
    //         return $this->saida($chave);
    //     }

    //     return $this->saidaComParams(
    //         $dados[1],
    //         $dados[2], 
    //         $dados[3]
    //     );
    // }

    public function saida($off = 0)
    {
       
        $cmd  = $this->conexao->query(
            "SELECT 
                id,
                descricao,
                tipoPagamento,
                created_at,
                valor 
            FROM
                $this->model
            ORDER BY
                id DESC
            LIMIT 12 offset $off "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll();
        }

        return [];
    }

    public function saidaComParams($texto = 0, $entrada, $saida, $tipo)
    {
        $entrada = date($entrada . ' 00:00:00');
        $saida = date($saida . ' 23:59:59');
       
        $sql  = "SELECT 
                    id,
                    descricao,
                    tipoPagamento,
                    created_at,
                    valor 
                FROM
                    $this->model
                WHERE
                    created_at between '$entrada' and '$saida' 
                ";

        if(!empty($texto)){
                
            $sql.= "
            AND
               descricao = $texto                 
            ";
        }

        if(!empty($tipo)){
                
            $sql.= "
            AND
               tipoPagamento = $tipo                 
            ";
        }

        $sql.= "
            ORDER BY id desc               
        ";

        $cmd  = $this->conexao->query(
            $sql
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll();
        }

        return [];
    }

    public function insertSaida($dados)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    saida
                SET 
                    valor = :valor, 
                    descricao = :descricao, 
                    tipoPagamento = :tipopagamento,
                    tipo = :tipo,
                    funcionario = :funcionario"
                );

            $cmd->bindValue(':valor',$dados['valor']);
            $cmd->bindValue(':descricao',$dados['descricao']);
            $cmd->bindValue(':tipo',$dados['tipo']);
            $cmd->bindValue(':tipopagamento',$dados['pagamento']);
            $cmd->bindValue(':funcionario',$_SESSION['code']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function buscaSaidasByParams($params)
    {
        if(is_null($params) || empty($params))
        {
            return $this->saida($off = 0);
        } 

        return $this->saidaComParams(
            $params['search'],
            $params['startDate'], 
            $params['endDate'],
            $params['status']
        );
    }

    public function deleteById($id)
    {
        if(is_null($id)) {
            return null;
        }
        
        $entrada = $this->findById($id);

        if($entrada) {
            $this->conexao->beginTransaction();
            try {      
                $cmd = $this->conexao->prepare(
                    "DELETE FROM 
                        $this->model
                    WHERE 
                        id = :id"
                    );

                $cmd->bindValue(':id',$id);
                $cmd->execute();

                
                self::dropRegister($entrada['data']);
    
                $this->conexao->commit();
                return self::message(201, "Registro deletado!!");
    
            } catch (\Throwable $th) {
                $this->conexao->rollback();
                return self::message(422, $th->getMessage());
            }
        }
    }
}