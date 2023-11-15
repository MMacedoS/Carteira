<!-- About-->
<?php $color_text = $this->findColorByParam('session_two_text');
$color_bg = $this->findColorByParam('session_two_bg');
?>
<section class="content-section " id="about" style="background-color: <?=$color_bg['color']?> !important; color: <?=$color_text['color']?> !important;">
            <div class="container px-4 px-lg-5 text-center">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-10">
                        <h2> <?=$this->text[3]['texto']?></h2>
                        <!-- <p class="lead mb-5">
                            Nisto aqui podemos buscar mais
                            <a href="https://unsplash.com/">Unsplash</a>
                            !
                        </p> -->
                        <a class="btn btn-dark btn-xl" style="background-color: <?=$color_text['color']?>;" href="#services"><?=$this->text[4]['texto']?></a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services-->