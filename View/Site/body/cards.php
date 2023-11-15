 <!-- Portfolio-->
 <?php 
    $color_text = $this->findColorByParam('session_five_text');
    $color_bg = $this->findColorByParam('session_five_bg');
?>
 <section class="content-section" id="portfolio">
            <div class="container px-1 px-lg-1">
                <div class="content-section-heading text-center">
                    <h3 class="text-secondary mb-0"></h3>
                    <!-- <h2 class="mb-5"><
                    ?=$text_portifolio?></h2> -->
                </div>
                <div class="row gy-4 gx-3">
                    <?php 
                        if (isset($this->cards) && is_array($this->cards)){
                        foreach($this->cards as $card){
                    ?>
                    <div class="col-lg-6 mr-3">
                        <a class="portfolio-item" href="#!">
                            <div class="caption">
                                <div class="caption-content" style="color: <?=$color_text['color']?> !important;">
                                    <div class="h2"><?=$card['nome']?></div>
                                    <p class="mb-0"><?=$card['descricao']?></p>
                                    <p><b>R$ <?=self::valueBr($card['valor_atual'])?></b> <s>R$ <?=self::valueBr($card['valor_anterior'])?></s></p>
                                </div>
                            </div>
                            <img class="img-fluid" src="<?=ROTA_GERAL?>/Public/Site/Card_APT/<?=$card['imagem']?>" alt="..." />
                        </a>
                    </div>
                    <?php }
                    } ?>
                </div>
            </div>
        </section>