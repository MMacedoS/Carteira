<!-- Callout-->
<?php 
    $color_text = $this->findColorByParam('session_three_text');
    $color_bg = $this->findColorByParam('session_three_bg');
?>
<section class="callout" >
        <div class="container px-4 px-lg-5 text-center">
            <h2 class="mx-auto mb-5" style="color: <?=$color_text['color']?> !important;">
                <?=$this->text[6]['texto']?>
            </h2>
        <a class="btn btn-primary btn-xl" style="background-color: <?=$color_bg['color']?>;" href="#call_to_action"><?=$this->text[7]['texto']?></a>
    </div>
</section>