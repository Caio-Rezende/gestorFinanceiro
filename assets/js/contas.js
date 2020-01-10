function alteraBolPaga(el) {
    var id   = jQuery(el).attr('data-id');
    var name = jQuery(el).attr('data-strNome');
    jQuery(el).click(function(){});
    $('.messageSpace').html("<h1>Mensagens:</h1><ul><li>Aguarde...</li></ul>");
    jQuery.post(
        'index.php?control=ctConta&method=alteraBolPaga&ajax',
        {
            id : id
        }
    ).done(
        function (data){
            if (data && data.ret == 1) {
                $('.messageSpace').html("<h1>Mensagens:</h1><ul><li>Altera&ccedil;&atilde;o de pagamento bem sucedida para conta: "+name+", caso n&atilde;o apare&ccedil;a abaixo a altera&ccedil;&atilde;o, recarregue a p&aacute;gina.</li></ul>");
            } else {
                $('.errorSpace').html("<h1>Erros:</h1><ul><li>Altera&ccedil;&atilde;o de pagamento n&atilde;o pode ser conclu&iacute;da para conta: "+name+".</li></ul>");
            }
        }
    ).fail(
        function (){
            $('.errorSpace').html("<h1>Erros:</h1><ul><li>Altera&ccedil;&atilde;o de pagamento n&atilde;o pode ser conclu&iacute;da para conta: "+name+".</li></ul>");
        }
    ).always(
        function(){
            jQuery(el).click(function(){alteraBolPaga(this);});
        }
    );
    event.stopPropagation();
    return false;
}