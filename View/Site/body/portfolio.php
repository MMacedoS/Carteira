<?php 
    $dados = $this->findParamByParam('whatsapp');
    $color_text = $this->findColorByParam('session_seven_text');
    $color_bg = $this->findColorByParam('session_seven_bg');
?>
<style>
    .image-preview {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      text-align: center;
    }

    .image-preview img {
      max-width: 80%;
      max-height: 80%;
      margin-top: 5%;
    }
</style>
<!-- Portfolio-->
 <section class="content-section" id="portfolio" style="background-color: <?=$color_bg['color']?> !important; ">
            <div class="container px-4 px-lg-5">
                <!-- <div class="content-section-heading text-center">
                    <h3 class="text-secondary mb-0"></h3>
                    <h2 class="mb-5"><=$text_portifolio?></h2> -->
                <!-- </div> -->
                <div class="row gx-3 gy-4">
                    <?php if (isset($this->imagesSite) && is_array($this->imagesSite)){ 
                        foreach ($this->imagesSite as $key => $value) {
                         ?>
                    <div class="col-lg-6 image-card">
                            <div class="caption">
                                <!-- <div class="caption-content">
                                    <div class="h2">Estacção de estudos</div>
                                    <p class="mb-0">Um lapis amarelo com alguns envelopes, e está em uma mesa azul!</p>
                                </div> -->
                            </div>
                            <img class="img-fluid" src="<?=ROTA_GERAL?>/Public/Site/Images/<?=$value['imagem']?>" alt="..." />
                    </div>
                    <?php 
                        }
                }?>                    
                </div>

                <div class="image-preview" id="preview">
                    <img src="" alt="Imagem Ampliada">
                </div>
            </div>
        </section>

    <script>
        const imageCards = document.querySelectorAll('.image-card');
        const imagePreview = document.getElementById('preview');

        imageCards.forEach(card => {
        card.addEventListener('click', () => {
            const imgSrc = card.querySelector('img').src;
            imagePreview.querySelector('img').src = imgSrc;
            imagePreview.style.display = 'block';
        });
        });

        imagePreview.addEventListener('click', () => {
        imagePreview.style.display = 'none';
        });
  </script>