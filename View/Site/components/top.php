<?php require_once __DIR__ . "/../../../Config/dados.php"; ?>
<?php $dados = $this->findParamByParam('nome_site'); ?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title><?=$dados['valor']?></title>
        <!-- Favicon-->
        <!-- <link rel="icon" type="image/x-icon" href="<=ROTA_GERAL?>/Public/Estilos/assets/favicon.ico" /> -->
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Simple line icons-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="<?=ROTA_GERAL?>/Public/Estilos/css/styles.css" rel="stylesheet" />
    </head>
    <body id="page-top" style="color: <?=$this->color[3]['color']?> !important;">
        <!-- Navigation-->