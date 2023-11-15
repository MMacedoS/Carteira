<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';
require_once 'Trait/DateModelTrait.php';

class ReservaModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    use DateModelTrait;
    
    protected $conexao;

    protected $model = 'reserva';

    protected $consumo_model;

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
        $this->consumo_model = new ConsumoModel();
    }

    public function prepareInsertReserva($dados)
    {
        $validation = self::requiredParametros($dados);

        if(is_null($validation)){
            
            if($this->verificareservaSeExiste($dados))
            {   
                return $this->insertReserva($dados); 
            }

            return self::message(422, 'Reserva existente!');
        }

        return $validation;
    }

    private function verificaReservaSeExiste($dados)
    {
        $hospede = (int)$dados['hospedes'];
        $dataEntrada = (string)$dados['entrada'];
        $dataSaida = (string)$dados['saida'];
        $tipo = (int)$dados['tipo'];
        $status = (int)$dados['status'];
        
        $cmd = $this->conexao->query(
            "SELECT 
                *
            FROM
                $this->model
            WHERE
                hospede_id = '$hospede'
            AND
                tipo = '$tipo'
            AND
                dataEntrada = '$dataEntrada'
            AND
                dataSaida = '$dataSaida'"
        );

        if($cmd->rowCount()>0)
        {
            return false;
        }

        return true;
    }

    private function insertReserva($dados)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    $this->model 
                SET 
                    dataReserva = :dataReserva, 
                    dataEntrada = :dataEntrada, 
                    dataSaida = :dataSaida,
                    obs = :observacao,
                    tipo = :tipo,
                    hospede_id = :hospede_id,
                    valor = :valor,
                    status = :status,
                    funcionario =  :funcionario,
                    qtde_hosp = :qtde_hosp
                    "
                );

            $cmd->bindValue(':dataReserva', Date('Y-m-d'));
            $cmd->bindValue(':dataEntrada',$dados['entrada']);
            $cmd->bindValue(':dataSaida',$dados['saida']);
            $cmd->bindValue(':valor',$dados['valor']);
            $cmd->bindValue(':status',$dados['status']);
            $cmd->bindValue(':tipo',$dados['tipo']);
            $cmd->bindValue(':observacao',$dados['observacao']);
            $cmd->bindValue(':hospede_id',$dados['hospedes']);
            $cmd->bindValue(':funcionario',$_SESSION['code']);
            $cmd->bindValue(':qtde_hosp',$dados['qtde_hosp']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    private function inserirValoresDiaria($dataInicial, $dataFinal, $valor, $idReserva) {
        $dataAtual = new DateTime($dataInicial);
        $dataFim = new DateTime($dataFinal);

        while ($dataAtual < $dataFim) {
            $valorData = $dataAtual->format('Y-m-d');

            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    diarias
                SET 
                    reserva_id = :reserva_id, 
                    data = :data, 
                    valor = :valor
                    "
                );

            $cmd->bindValue(':reserva_id', $idReserva);
            $cmd->bindValue(':data', $valorData);
            $cmd->bindValue(':valor', $valor);
            $cmd->execute();
            $dataAtual->modify('+1 month');
        }
    }

    private function removerValoresDiaria($idReserva) {
        $cmd = $this->conexao->prepare(
            "DELETE FROM
                diarias
            WHERE   
                reserva_id = :reserva_id"
            );

        $cmd->bindValue(':reserva_id', $idReserva);
        $cmd->execute();
    }

    public function prepareUpdatereserva($dados, $id)
    {
        $validation = self::requiredParametros($dados);

        if(is_null($validation)){            
            return $this->updateReserva($dados, $id); 
        }

        return $validation;
    }

    private function calculeReserva(int $id)
    {
        $reserva = self::findById($id);
            
        if($reserva['data'][0]['status'] == 3) {
            $this->removerValoresDiaria($id);

            $this->inserirValoresDiaria(
                $reserva['data'][0]['dataEntrada'], 
                $reserva['data'][0]['dataSaida'], 
                $reserva['data'][0]['valor'] ,
                 $id
             );

         }
    }

    private function updateReserva($dados, int $id)
    {
        $this->conexao->beginTransaction();
        try {               
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    dataEntrada = :dataEntrada, 
                    dataSaida = :dataSaida,
                    obs = :observacao,
                    tipo = :tipo,
                    hospede_id = :hospede_id,
                    valor = :valor,
                    status = :status,
                    funcionario =  :funcionario
                WHERE 
                    id = :id"
                );

                $cmd->bindValue(':dataEntrada',$dados['entrada']);
                $cmd->bindValue(':dataSaida',$dados['saida']);
                $cmd->bindValue(':valor',$dados['valor']);
                $cmd->bindValue(':status',$dados['status']);
                $cmd->bindValue(':tipo',$dados['tipo']);
                $cmd->bindValue(':observacao',$dados['observacao']);
                $cmd->bindValue(':hospede_id',$dados['hospedes']);
                $cmd->bindValue(':funcionario',$_SESSION['code']);
                $cmd->bindValue(':id',$id);
                $dados = $cmd->execute();
            $this->calculeReserva($id);
            $this->conexao->commit();
            return self::message(201, "dados Atualizados!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function findReservas($nome, $entrada, $saida, $status)
    {
        $SQL = "SELECT 
                   r.id,
                    h.nome,
                    r.dataEntrada,
                    r.dataSaida,
                    r.tipo,
                    r.status
                FROM 
                    $this->model r 
                left join
                    hospede h on h.id = r.hospede_id
                WHERE
                    r.status LIKE '%$status%' 
                ";
        
        if(!empty($entrada) && !empty($saida)){
            $SQL.= "
            AND
            (
                dataEntrada 
                    BETWEEN 
                        '$entrada' 
                    AND 
                        '$saida'
                                       
            )";
        }

        if(!empty($nome)){
            $SQL.= "
            AND
            (
                h.nome LIKE '%$nome%' 
                                       
            )";
        }

        $SQL.= " order by id desc";

        $cmd  = $this->conexao->query(
            $SQL
        );

        if($cmd->rowCount() > 0)
        {            
            return $cmd->fetchAll();
        }

        return false;
        
    }

    public function getAll() {
        $cmd = $this->conexao->query(
            "SELECT 
                r.id,
                h.nome,
                r.dataEntrada,
                r.dataSaida,
                r.tipo,
                r.status
            FROM
                $this->model r
            left join
            hospede h
            on h.id = r.hospede_id
            where r.status != 3 and r.status != 4
            order by r.id desc"
        );
        
        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll();
        }
    }

    public function findAllReservas($nome, int $off = 0)
    {
        $off = $off;
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                hospede h 
            ON 
                r.hospede_id = h.id
            LEFT JOIN 
                empresa_has_hospede eh
            ON 
                eh.hospede_id = h.id
            WHERE
                h.nome LIKE '%$nome%'
             AND
                r.status <=2   
             AND
                r.dataEntrada >= DATE_SUB(curdate(), INTERVAL 3 DAY) 
            ORDER BY
                r.id DESC
            LIMIT 12 offset $off 
            
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return false;
        
    }

    public function getAllReservas($hospede = '', $dataEntrada = '', $dataSaida = '', $situacao = 1 )
    {
        try {
            $cmd  = $this->conexao->query(
                "SELECT
                r.id,
                r.dataEntrada,
                r.dataSaida,
                r.tipo,
                r.qtde_hosp,
                r.status,
                h.nome,
                SUM(COALESCE(d.valor,0)) as valor
            FROM
                $this->model r
            INNER JOIN
                hospede h
            ON
                r.hospede_id = h.id
            LEFT JOIN
                empresa_has_hospede eh
            ON
                eh.hospede_id = h.id
            LEFT JOIN
                diarias d
            ON
                d.reserva_id = r.id
            WHERE
                h.nome LIKE '%$hospede%'               
                AND (
                    (
                        ('$dataEntrada' BETWEEN r.dataEntrada AND r.dataSaida) 
                        OR
                        ('$dataSaida' BETWEEN r.dataEntrada AND r.dataSaida)
                    )
                    and r.status = '$situacao'
                )               
            GROUP BY
                r.id,
                r.dataEntrada,
                r.dataSaida,
                r.tipo,
                r.qtde_hosp,
                r.status,
                h.nome,
            ORDER BY
                r.id DESC;            
                "
            );
    
            if($cmd->rowCount() > 0)
            {
                return $cmd->fetchAll(PDO::FETCH_ASSOC);
            }
    
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        
    }

    public function prepareChangedReserva($id)
    {
        $reserva = self::findById($id);

        if(is_null($reserva)) {
            return self::messageWithData(422, 'reserva não encontrado', []);
        }

        $reserva['data'][0]['status'] == '1' ? $status = 5 : $status = 1;
        
        return $this->updateStatusReserva(
                $status,
                $id
            );
    }

    private function updateStatusReserva($status, $id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    status = :status
                WHERE 
                    id = :id"
                );
            $cmd->bindValue(':status',$status);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            
            return self::messageWithData(200, "dados Atualizados!!", []);

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    private function updateStatusCheckoutReserva($status, $id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    status = :status
                WHERE 
                    id = :id"
                );
            $cmd->bindValue(':status',$status);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            
            return self::messageWithData(200, "dados Atualizados!!", []);

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function prepareCheckinReserva($id)
    {
        $reserva = self::findById($id);

        if(is_null($reserva)) {
            return self::messageWithData(422, 'reserva não encontrado', []);
        }

        $qtde_dias = self::countDaysInReserva((object)$reserva['data'][0]);

        $this->inserirValoresDiaria(
           $reserva['data'][0]['dataEntrada'], 
           $reserva['data'][0]['dataSaida'], 
           $reserva['data'][0]['valor'] ,
            $id
        );

        return $this->updateStatusReserva(
            3,
            $id
        );
    }

   private function getReservasPorData($dataStart, $dataEnd)
    {
        $cmd = $this->conexao->query(
            "SELECT 
                *
            FROM 
                reserva
            WHERE 
                (status <= 3)
            AND 
            ( 
                (
                    dataEntrada >= '$dataStart' 
                    AND 
                    dataEntrada < '$dataEnd'
                ) 
                OR 
                (
                    dataSaida > '$dataStart' 
                    AND 
                    dataSaida <= '$dataEnd'
                ) 
                OR 
                (
                    dataEntrada <= '$dataStart' 
                    AND 
                    dataSaida >= '$dataEnd' 
                )
            )"
        );

        if($cmd->rowCount() > 0) {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function buscaReservasConcuidas($texto)
    {
        $texto = trim($texto[0]);

        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                hospede h 
            ON 
                r.hospede_id = h.id
            LEFT JOIN 
                empresa_has_hospede eh
            ON 
                eh.hospede_id = h.id
            WHERE
                h.nome LIKE '%$texto%'         
            OR 
                r.id LIKE '%$texto%  
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];        
    }

    public function buscaHospedadas($texto)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                hospede h 
            ON 
                r.hospede_id = h.id
            LEFT JOIN 
                empresa_has_hospede eh
            ON 
                eh.hospede_id = h.id
            WHERE
               r.status = 3
            AND
                h.nome LIKE '%$texto%' 
            OR 
                r.id = '$texto'
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
        
    }

    public function executaCheckout($id)
    {
        $reserva = self::findById($id);

        if(is_null($reserva)) {
            return self::messageWithData(422, 'OS não encontrado', []);
        }

        return $this->updateStatusCheckoutReserva(
            4,
            $id
        );
    }

    public function buscaCheckin($nome)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                hospede h 
            ON 
                r.hospede_id = h.id
            LEFT JOIN 
                empresa_has_hospede eh
            ON 
                eh.hospede_id = h.id
            WHERE
               (
                    r.status = 1 
                OR
                    r.status= 2
               )
               AND
                dataEntrada <= curdate()
               AND
                dataEntrada >= DATE_SUB(curdate(), INTERVAL 1 DAY)
            AND
                h.nome LIKE '%$nome%'
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return array();
        
    }

    public function buscaCheckout($nome)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                hospede h 
            ON 
                r.hospede_id = h.id
            LEFT JOIN 
                empresa_has_hospede eh
            ON 
                eh.hospede_id = h.id
            WHERE
                r.status = 3
               AND
                dataSaida <= curdate()
            AND
                h.nome LIKE '%$nome%'
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
        
    }

    public function buscaConfirmada($nome)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                hospede h 
            ON 
                r.hospede_id = h.id
            LEFT JOIN 
                empresa_has_hospede eh
            ON 
                eh.hospede_id = h.id
            WHERE
                r.status = 2
            AND
                h.nome LIKE '%$nome%'
            AND 
                dataEntrada LIKE '%$nome%'
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
        
    }

    public function buscaReservadas($nome)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                hospede h 
            ON 
                r.hospede_id = h.id
            LEFT JOIN 
                empresa_has_hospede eh
            ON 
                eh.hospede_id = h.id
            WHERE
               (
                    r.status = 1 
                OR
                    r.status= 2
               )
               AND
                dataEntrada >= curdate()
               AND
                YEAR(dataEntrada) = YEAR(CURDATE())
            AND
                h.nome LIKE '%$nome%'
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return array();
        
    }

    public function getDadosReservas($id){
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome, 
                COALESCE((SELECT sum(valorUnitario * quantidade) FROM consumo c where c.reserva_id = r.id), 0) as consumos,
                COALESCE((SELECT sum(valor) FROM diarias d where d.reserva_id = r.id), 0) as diarias,
                COALESCE((SELECT sum(p.valorPagamento) FROM pagamento p where p.reserva_id = r.id), 0) as pag
            FROM 
                `reserva` r 
            INNER JOIN 
                hospede h 
            on 
                r.hospede_id = h.id 
            WHERE 
                r.id = $id
            "
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return self::messageWithData(201, 'reserva encontrada', $dados);
        }

        return self::messageWithData(422, 'nehum dado encontrado', []);
    }

    public function getDadosDiarias($id){
        $cmd  = $this->conexao->query(
            "SELECT 
               *
            FROM 
                diarias 
            WHERE 
                reserva_id = $id
            "
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return self::messageWithData(201, 'reserva encontrada', $dados);
        }

        return self::messageWithData(422, 'nehum dado encontrado', []);
    }

    public function updateDiarias($dados, $id)
    {
        $valor = $dados['valor'];
        $data =  $dados['data'];
        
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    diarias
                SET 
                    data = :data, 
                    valor = :valor
                WHERE 
                    id = :id
                    "
                );

            $cmd->bindValue(':data',$data);
            $cmd->bindValue(':valor',$valor);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(200, "dados atualizados!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function getRemoveDiarias($id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "DELETE FROM
                    diarias
                WHERE 
                    id = :id
                    "
                );
            $cmd->bindValue(':id',$id);
            $cmd->execute();
            $this->conexao->commit();
            return self::message(200, "dados REMOVIDOS!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function findDiariasById($id)
    {
        $cmd = $this->conexao->query(
            "SELECT 
                *
            FROM
                diarias
            WHERE
                id = $id
            "
        );

        if($cmd->rowCount() > 0)
        {

            return self::messageWithData(201,'Dados encontrados', $cmd->fetchAll(PDO::FETCH_ASSOC));
        }

        return false;
    }

    public function gerarDiarias()
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                *
            FROM 
                configuracao
            WHERE 
                parametro = 'gerar_diaria'
            "
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC)[0]['valor'];
            // var_dump(strtotime(Date('Y-m-d 17:00:00')), strtotime(Date('Y-m-d H:i:s')));
            if (strtotime($dados) < strtotime(Date('Y-m-d H:i:s'))) {
                $this->verificaGerarDiarias($dados);
            }

            return true;
        }
        
    }
    
    private function verificaGerarDiarias($param)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                id,
                valor,
                gerarDiaria
            FROM 
                reserva
            WHERE 
                status = 3
            AND
                tipo = 1
            AND
                gerarDiaria <= '$param'
            "
        );
       
        if($cmd->rowCount() > 0)
        {           
            $data = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $this->prepareGerarDiarias($data, $param);
        }

        return $this->updateConfiguracaoGerarDiaria(self::addDayInDate(Date('Y-m-d 16:00:00'), 1));
    }

    private function prepareGerarDiarias($dados, $param)
    {
        if(empty($dados))
        {
            return null;
        }

        foreach ($dados as $key => $value) {
            $dias = round((strtotime(Date('Y-m-d H:i:s')) - strtotime($value['gerarDiaria']))/86400);

            if($dias < 1){
                $dias = 1;
            }

            for ($i=1; $i <= $dias; $i++) { 
                $this->insertDiariaConsumo($value, self::addDayInDate($param, $i -1 ));
                $this->updateGerarDiaria($value['id'], self::addDayInDate($param, $i));

                $this->updateConfiguracaoGerarDiaria(self::addDayInDate($param, $i));
            }            
        }

        return "atalizações consumos feitas";
    }

    private function insertDiariaConsumo($value, $data)
    {        
        $this->consumo_model->insertDiaria($value, $data);
    }

    private function updateGerarDiaria($id, $data)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    gerarDiaria = :gerarDiaria
                WHERE 
                    id = :id"
                );
            $cmd->bindValue(':gerarDiaria',$data);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            
            return self::messageWithData(200, "dados Atualizados!!", []);

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }


    private function updateConfiguracaoGerarDiaria($data)
    {
        $cmd  = $this->conexao->prepare(
            "UPDATE 
                configuracao
            SET 
              valor = :data 
            WHERE 
                parametro = :param
            "
        );

        $cmd->bindValue(':data',$data);
        $cmd->bindValue(':param',"gerar_diaria");

        $cmd->execute();

        return "atualizado configurações";
    }

    public function buscaMapaReservas($startDate,$endDate)
    {
        try {      
            $cmd = $this->conexao->prepare(
                "SELECT all_dates.date_value AS start, IFNULL(count(r.dataEntrada), 0) AS title
                FROM (
                    SELECT DATE_ADD(:entrada, INTERVAL n.num DAY) AS date_value
                    FROM (
                        SELECT (a.a + (10 * b.a) + (100 * c.a)) num
                        FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
                        CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
                        CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) c
                    ) n
                    WHERE DATE_ADD(:entrada, INTERVAL n.num DAY) <= :saida
                ) all_dates
                LEFT JOIN reserva r ON r.dataEntrada <= all_dates.date_value AND r.dataSaida >= all_dates.date_value
                where r.status < 4
                GROUP BY all_dates.date_value
                ORDER BY all_dates.date_value                
                "
                );

            $cmd->bindValue(':entrada', $startDate);
            $cmd->bindValue(':saida',$endDate);
            $cmd->execute();
            
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);

            
        // Converter os resultados para o formato do FullCalendar
        $eventos_fullcalendar = [];
        foreach ($dados as $evento) {
            if($evento['title'] > 0) {
                $evento_fullcalendar = [
                    'title' => $evento['title'] . " OS", // Título do evento, com a quantidade de reservas
                    'start' => $evento['start'], // Data de início da reserva
                ];
                $eventos_fullcalendar[] = $evento_fullcalendar;
            }
        }

        return $eventos_fullcalendar;

            
            // // Criar a tabela temporária
            //     $sql_create_tmp_table = "CREATE TEMPORARY TABLE tmp_dates (date_value DATE)";
            //     $this->conexao->exec($sql_create_tmp_table);

            //     // Preencher a tabela temporária com as datas do intervalo
            //     $sql_fill_tmp_table = "INSERT INTO tmp_dates (date_value)
            //                         SELECT DATE_ADD(:entrada, INTERVAL n.num DAY) AS date_value
            //                         FROM (
            //                             SELECT (a.a + (10 * b.a) + (100 * c.a)) num
            //                             FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) a
            //                             CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) b
            //                             CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) c
            //                         ) n
            //                         WHERE DATE_ADD(:entrada, INTERVAL n.num DAY) <= :saida";
            //     $this->conexao->prepare($sql_fill_tmp_table)->execute(['entrada' => $startDate, 'saida' => $endDate]);

            //     // Executar a consulta SQL com a tabela temporária
            //     $sql_query = "SELECT d.date_value AS start, COUNT(r.dataEntrada) AS title
            //                 FROM tmp_dates d
            //                 LEFT JOIN reserva r ON r.dataEntrada = d.date_value
            //                 WHERE d.date_value BETWEEN :entrada AND :saida
            //                 GROUP BY d.date_value
            //                 ORDER BY d.date_value";

            //     $stmt = $this->conexao->prepare($sql_query);
            //     $stmt->execute(['entrada' => $startDate, 'saida' => $endDate]);
            //     $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            //     $eventos_fullcalendar = [];
            //     foreach ($eventos as $evento) {
            //         $evento_fullcalendar = [
            //             'title' => $evento['title'] . ' Apt Reservados', // Título do evento, concatenando a contagem com a string
            //             'start' => $evento['start'], // Data de início da reserva
            //             'end' => $evento['start'], // Neste exemplo, estamos assumindo que não há hora de término, então a data de início também é usada como data de término
            //         ];
            //         $eventos_fullcalendar[] = $evento_fullcalendar;
            //     }
                
            // return $eventos_fullcalendar;

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}