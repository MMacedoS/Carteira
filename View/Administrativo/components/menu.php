   <!-- Page Wrapper -->
   <div id="wrapper">

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?=ROTA_GERAL?>/Administrativo">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Adminstração</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?=$this->active == "" ? 'active' : ''?>">
        <a class="nav-link" href="<?=ROTA_GERAL?>/Administrativo">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Tela Administrativa</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <li class="nav-item <?=$this->active == "venda" ? 'active' : ''?>">
        <a class="nav-link" href="<?=ROTA_GERAL?>/Administrativo/venda">
            <i class="fas fa-fw fa-cart-plus"></i>
            <span>Vendas</span></a>
    </li>

     <!-- Nav Item - Pages Collapse Menu -->
     <li class="nav-item <?=$this->active == "cadastro" ? 'active' : ''?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCad" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Cadastros</span>
        </a>
        <div id="collapseCad" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">                                                  
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/funcionarios">Funcionários</a> 
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/Hospedes">Cliente</a>    
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/produtos">Produto</a>                 
            </div>
        </div>
    </li>

    <li class="nav-item <?=$this->active == "estoque" ? 'active' : ''?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEstoque" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-dice-five"></i>
            <span>Estoque</span>
        </a>
        <div id="collapseEstoque" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
            <?php 
                if($_SESSION['painel'] == 'Administrador'){
            ?>
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/entradaEstoque">Entrada</a>                    
                <!-- <a class="collapse-item" href="<=ROTA_GERAL?>/Administrativo/Empresa">Empresa</a>   -->
            <?php
                }
            ?>  
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/estoque">Estoque</a>                 
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item <?=$this->active == "reservas" ? 'active' : ''?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Ordem de</br>&nbsp;&nbsp; Serviços </span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header"></h6>
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/reservas">Criar</a>
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/consultas">Consultar</a>  
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/mapas">Mapas</a>                
            </div>
        </div>
    </li>

   <?php 
    if($_SESSION['painel'] == 'Administrador'){
   ?>
    <li class="nav-item <?=$this->active == "financeiro" ? 'active' : ''?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFinanceiro" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-dice-five"></i>
            <span>Financeiro</span>
        </a>
        <div id="collapseFinanceiro" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/entrada">Entrada</a>                    
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/movimentacoes">Movimentos</a>                  
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/saida">Saida</a>                 
            </div>
        </div>
    </li>

    <li class="nav-item <?=$this->active == "relatorio" ? 'active' : ''?>">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRelatorio" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-dice-five"></i>
            <span>Relatório</span>
        </a>
        <div id="collapseRelatorio" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/relacao/cafe">Café</a>                    
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/relacao/reservas">Reservas</a>                  
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/relacao/movimentos">Movimentos</a>                 
                <a class="collapse-item" href="<?=ROTA_GERAL?>/Administrativo/relacao/pagamentos">Relação Hospedagem</a>                 
            </div>
        </div>
    </li>

    <?php
    }
    ?>
    
    <div class="text-center d-none d-md-inline mb-3">
        <?php         
            if($this->background){                
                echo '<button type="button" class="rounded-circle border-0 btn btn-dark" id="buttonBg">Dark</button>';
            } else{
                echo '<button type="button" class="rounded-circle border-0 btn btn-light" id="buttonBg">light</button>';
            }?>        
    </div>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->


</ul>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column bg-<?= !$this->background ? 'light': 'dark'?> text-<?= !$this->background ? 'dark': 'light'?> ">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>
            
            <!-- Topbar Search -->
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <!-- <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2"> -->
                    <div class="input-group-append">
                        <!-- <h1></h1> -->
                        <!-- <button class="btn btn-primary" type="button"> -->
                            <!-- <i class="fas fa-search fa-sm"></i> -->
                        </button>
                    </div>
                </div>
            </form>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                        <form class="form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
                
                  <li class="nav-item dropdown no-arrow mx-1 d-none d-sm-inline-block">
                    <a class="nav-link dropdown-toggle btn-primary" href="<?=ROTA_GERAL?>/Administrativo/hospedadas" id="alertsDropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <!-- Counter - Alerts -->
                        OS andamento
                        <span class="badge badge-danger badge-counter"><?= count($this->buscaHospedadas())  ?>+</span>
                    </a>
                  </li>
                <!-- Nav Item - Messages -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-envelope fa-fw"></i>
                        <!-- Counter - Messages -->
                        <!-- <span class="badge badge-danger badge-counter">7</span> -->
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                        <h6 class="dropdown-header">
                            Message Center
                        </h6>
                       
                    </div>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?=$_SESSION['nome']?></span>
                        <img class="img-profile rounded-circle" src="<?=ROTA_GERAL?>/Estilos/img/undraw_profile.svg">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                       
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?=ROTA_GERAL?>/Login/logouf">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

        