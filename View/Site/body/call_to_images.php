 <!-- Call to Action-->
 <?php 
    $dados = $this->findParamByParam('whatsapp');
    $color_text = $this->findColorByParam('session_six_text');
    $color_bg = $this->findColorByParam('session_six_bg');
?>
 <section class="content-section bg-primary text-white" id="call_to_image" style="background-color: <?=$color_bg['color']?> !important; color: <?=$color_text['color']?> !important;">
            <div class="container px-4 px-lg-5 text-center">
                <h2 class="mb-4"><?=$this->text[8]['texto']?></h2>
                <p> <i class="fab fa-whatsapp"></i> WhatsApp: <?=self::formatPhoneNumber($dados['valor'])?></p>
                <!-- <a class="btn btn-xl btn-light me-4" href="#!">Clique aqui!</a> -->
                <a class="btn btn-xl btn-dark" target="_blank"  href="https://api.whatsapp.com/send?phone=<?=$dados['valor']?>" style="background-color: <?=$color_bg['color']?>;">Entre em contato! <i class="fab fa-whatsapp"></i></a>
            </div>
        </section>