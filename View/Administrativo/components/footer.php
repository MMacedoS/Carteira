<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('#sair').click(function(){
        window.location.reload();
    });

    $('.btn-secondary').click(function(){
        window.location.reload();
    })

    $('#buttonBg').click(function(){
        event.preventDefault();
        var code = "<?=$_SESSION['code']?>";
        $.ajax({
            url:'<?=ROTA_GERAL?>/Administrativo/changeStatusBgGet/'+ code,
            method: 'GET',
            dataType: 'JSON',
            success: function(res){
                window.location.reload();
            }
        });
    });
</script>

</div>
</body>
</html>