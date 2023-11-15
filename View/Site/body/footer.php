 <!-- Footer-->
 <?php $dados = $this->findParamByParam('whatsapp'); ?>
 <footer class="footer text-center" style="background-color: <?=$this->color[0]['color']?> !important; color: <?=$this->color[3]['color']?>">
            <div class="container px-4 px-lg-5">
                <ul class="list-inline mb-5">
                    <li class="list-inline-item">
                        <a class="social-link rounded-circle text-white mr-3" href="https://api.whatsapp.com/send?phone=<?=$dados['valor']?>"><i class="fab fa-whatsapp"></i></i></a>
                    </li>
                    <!-- <li class="list-inline-item">
                        <a class="social-link rounded-circle text-white mr-3" href="#!"><i class="icon-social-twitter"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a class="social-link rounded-circle text-white" href="#!"><i class="icon-social-github"></i></a>
                    </li> -->
                </ul>
                <p class="text-muted small mb-0" style="color: <?=$this->color[3]['color']?> !important;">Copyright &copy; MMS</p>
            </div>
        </footer>
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="<?=ROTA_GERAL?>/Public/Estilos/js/scripts.js"></script>
    </body>