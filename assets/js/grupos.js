$(function(){
    $('#btnNovaContaGrupo').click(function(){
        document.forms['grupo'].action = document.forms['grupo'].action + '&method=novaContaGrupo';
        document.forms['grupo'].submit();
    });
});