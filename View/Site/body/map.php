<?php $dados = $this->findParamByParam('mapa'); ?>
<style>
    .map iframe {
        pointer-events: auto;
    }
</style>
<section>
 <div class="map" id="contact">
    <?=$dados['valor']?>
 </div>
</section>