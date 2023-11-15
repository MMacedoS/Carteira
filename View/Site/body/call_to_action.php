<?php 
    $color_text = $this->findColorByParam('session_eight_text');
    $color_bg = $this->findColorByParam('session_eight_bg');
    $color_bg_btn = $this->findColorByParam('session_eight_bg_btn');
    $dados = $this->findParamByParam('whatsapp');
?>
 <!-- Call to Action-->
 <section class="content-section bg-primary text-white" id="call_to_action" style="background-color: <?=$color_bg['color']?> !important; color: <?=$color_text['color']?> !important;">
            <div class="container px-4 px-lg-5 text-center">
                <h2 class="mb-4"><?=$this->text[9]['texto']?></h2>
                <p> <i class="fab fa-whatsapp"></i> WhatsApp: <?=self::formatPhoneNumber($dados['valor'])?></p>
                <!-- <a class="btn btn-xl btn-light me-4" href="#!">Clique aqui!</a> -->
                <a class="btn btn-xl" target="_blank" style="background-color: <?=$color_bg_btn['color']?>;" href="https://api.whatsapp.com/send?phone=<?=$dados['valor']?>">Entre em contato! <i class="fab fa-whatsapp"></i></a>
            </div>
        </section>