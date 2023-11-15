 <!-- Services-->
 <?php 
    $color_text = $this->findColorByParam('session_three_text');
    $color_bg = $this->findColorByParam('session_three_bg');
?>
 <section class="content-section bg-primary text-white text-center"  style="background-color: <?=$color_bg['color']?> !important; color: <?=$color_text['color']?> !important; " id="services">
            <div class="container px-4 px-lg-5">
                <div class="content-section-heading">
                    <!-- <h3 class="text-secondary mb-0">Serviços</h3> -->
                    <h2 class="mb-5"><?=$this->text[5]['texto']?></h2>
                </div>
                <div class="row gx-4 gx-lg-5">
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <span class="service-icon rounded-circle mx-auto mb-3"><i class="fa-solid fa-wifi"></i></span>
                        <h4><strong><?=$servicos[0]?></strong></h4>
                        <p class="text-faded mb-0" style="color: <?=$color_text['color']?> !important; ">
                            Acesso ao
                            <i class="fas fa-heart"></i>
                            Mundo!
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                        <span class="service-icon rounded-circle mx-auto mb-3"><i class="fa-solid fa-bed"></i></span>
                        <h4><strong><?=$servicos[1]?></strong></h4>
                        <p class="text-faded mb-0" style="color: <?=$color_text['color']?> !important; ">apartamentos climatizados.</p>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-5 mb-md-0">
                        <span class="service-icon rounded-circle mx-auto mb-3"><i class="fas fa-apple-alt"></i></span>
                        <h4><strong><?=$servicos[2]?></strong></h4>
                        <p class="text-faded mb-0" style="color: <?=$color_text['color']?> !important; ">
                            delícias frescas e muito mais..
                        </p>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <span class="service-icon rounded-circle mx-auto mb-3"><i class="icon-mustache"></i></span>
                        <h4><strong><?=$servicos[3]?></strong></h4>
                        <p class="text-faded mb-0" style="color: <?=$color_text['color']?> !important; ">sua satifação é nossa alegria!</p>
                    </div>
                </div>
            </div>
        </section>
        