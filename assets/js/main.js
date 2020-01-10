$(function(){
    $('h3').click(function(){
        $('.relatedContent').hide('slow');
        $('[name="'+this.innerHTML+'"]').show('fast');
    });
    $('.relatedContent').hide('slow');
    $('[name="Dicas"]').show('fast');
});