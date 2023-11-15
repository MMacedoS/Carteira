<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';
require_once 'Trait/DateModelTrait.php';
require_once 'Trait/DataDropTrait.php';

class EntradaModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    use DateModelTrait;
    use DataDropTrait;
    
    protected $conexao;

    protected $model = 'entrada';

    public function __construct() 
    {
        $this->model = 'entrada';
        $this->conexao = ConexaoModel::conexao();
    }

    public function buscaEntrada($off = 0)
    {
        if(is_null($off) || empty($off))
        {
            return $this->entrada($off = 0);
        } 
        $dados = explode('_@_', $off);   

        if(count($dados) == 1)
        {
            $chave = str_replace('page=', '', $dados[0]);
            return $this->entrada($chave);
        }
        return true;
    }

    public function buscaEntradaByParams($params)
    {
        if(is_null($params) || empty($params))
        {
            return $this->entrada($off = 0);
        } 

        return $this->entradaComParams(
            $params['search'],
            $params['startDate'], 
            $params['endDate'],
            $params['status']
        );
    }

    public function entrada($off = 0)
    {         
        $cmd  = $this->conexao->query(
            "SELECT 
                id,
                descricao,
                tipoPagamento,
                created_at,
                pagamento_id,
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

    public function entradaComParams($texto = 0, $entrada, $saida, $tipoPagamento)
    {
        $entrada = date($entrada . ' 00:00:00');
        $saida = date($saida . '  23:59:59');
       
        $sql  = "SELECT 
             id,
            descricao,
            tipoPagamento,
            created_at,
            pagamento_id,
            valor 
        FROM
            $this->model
        WHERE
            created_at BETWEEN '$entrada' AND '$saida'
        AND (('$tipoPagamento' = '' or '$tipoPagamento' is null) or tipoPagamento = '$tipoPagamento')
        AND descricao LIKE '%$texto%'";

        $cmd  = $this->conexao->query(
            $sql
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll();
        }

        self::logError($sql);

        return [];
    }

    public function insertEntrada($dados)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    $this->model
                SET 
                    valor = :valor, 
                    descricao = :descricao, 
                    tipoPagamento = :tipopagamento,
                    funcionario = :funcionario"
                );

            $cmd->bindValue(':valor',$dados['valor']);
            $cmd->bindValue(':descricao',$dados['descricao']);
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

    public function updateEntrada($dados, $id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model
                SET 
                    valor = :valor, 
                    descricao = :descricao, 
                    tipoPagamento = :tipopagamento,
                    funcionario = :funcionario
                WHERE 
                    id = :id"
                );

            $cmd->bindValue(':valor',$dados['valor']);
            $cmd->bindValue(':descricao',$dados['descricao']);
            $cmd->bindValue(':tipopagamento',$dados['pagamento']);
            $cmd->bindValue(':funcionario',$_SESSION['code']);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados atualizados!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
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