<script src="<?= URL ?>/assets/js/main.js" type="text/javascript"></script>
<style type="text/css">
  @import url(<?=URL?>/assets/css/main.css);
</style>
<div class="grid_6 push_3" style='text-align: justify'>
    <h1>
        Bem vindo ao sistema gestorFinanceiro v1.0
    </h1>
    Clique nos <font class='selected'>t&iacute;tulos</font> abaixo para mais informa&ccedil;&otilde;es.
    <br/>
    <br/>

    <h3>Dicas</h3>
    <div class='relatedContent' name='Dicas'>
        <p>
            Confira os menus passando o ponteiro do mouse sobre a palavra 'Home' na barra azul ao topo e navegue clicando entre as op&ccedil;&otilde;es algumas vezes at&eacute; se sentir mais a vontade.
        <p>
            Os itens que s&atilde;o naveg&aacute;veis no gestorFinanceiro causam o efeito de mudar o ponteiro por uma m&atilde;o e, al&eacute;m disso, &eacute; comum que algum efeito visual indique a sele&ccedil;&atilde;o a ser clicada.
        <p>
            Tamb&eacute;m &eacute; comum que dicas apareçam quando o ponteiro permanece sobre algum item que pode ser clicado.
        <p>
            Por exemplo, passe o ponteiro sobre a palavra <i>Home</i> &agrave; esquerda deste texto, alinhada mais ao topo. Voc&ecirc; ver&aacute; uma mensagem explicativa do que acontecer&aacute; caso clique nela.
        <p>
            O mesmo ocorre com os menus e campos do gestorFinanceiro.
    </div>

    <h3>Grupos</h3>
    <div class='relatedContent' name='Grupos'>
        <p>
            Agora que voc&ecirc; j&aacute; se acostumou com o gestorFinanceiro, procure adicionar novos Grupos de Conta, para categorizar as suas Contas.
        <p>
            Note que h&aacute; dois tipos de Grupos: <font class="entrada">Entrada</font> e <font class="saida">Sa&iacute;da</font>, que por todo gestorFinanceiro seguir&atilde;o essas mesmas fontes para indicar esses dois tipos de Grupo.
    </div>

    <h3>Contas</h3>
    <div class='relatedContent' name='Contas'>
        <p>
            As Contas s&atilde;o os registros das <font class="entrada">Entradas</font> e <font class="saida">Sa&iacute;das</font> financeiras controladas no gestorFinanceiro, e ter&atilde;o as configura&ccedil;&otilde;es para se registrar um descritivo, o valor da parcela, a data inicial de pagamento, a quantidade de parcelas futuras, a data da compra e se j&aacute; foi paga.
        <p>
            A data inicial de pagamento refere-se a quando ser&aacute; paga a conta, e ela &eacute; que aparece no Relat&oacute;rio. Note que ao cadastrar mais de uma parcela, ser&aacute; sempre considerado o dia da data inicial de pagamento para exibi&ccedil;&atilde;o no Relat&oacute;rio.
        <p>
            As parcelas futuras indicam quantas parcelas relacionadas &agrave;quela Conta existem, e ao realizar o cadastro as Contas futuras s&atilde;o calculadas mensalmente para o mesmo dia do m&ecirc;s. 
        <p>
            Cada Conta calculada &eacute; independente, ou seja, editar uma delas n&atilde;o afetar&aacute; as demais. J&aacute; caso queira excluir uma das parcelas, &eacute; necess&aacute;rio ou excluir as &uacute;ltimas parcelas primeiro ou ir em parcelas anteriores e reduzir o n&uacute;mero de parcelas futuras. A &uacute;nica ressalva &eacute; que ao reduzir o n&uacute;mero de parcelas as contas futuras j&aacute; cadastradas ser&atilde;o recalculadas e qualquer edi&ccedil;&atilde;o feita nelas &eacute; perdida.
    </div>

    <h3>Calend&aacute;rio</h3>
    <div class='relatedContent' name='Calend&aacute;rio'>
        <p>
            Aqui voc&ecirc; encontra uma vis&atilde;o mensal do que est&aacute; acontecendo com suas contas, podendo ver as entradas e sa&iacute;das nos dias de pagamento.
        <p>
            &Eacute; poss&iacute;vel tamb&eacute;m informar se a Conta foi paga ou n&atilde;o pelo Calend&aacute;rio.
    </div>

    <h3>Relat&oacute;rios</h3>
    <div class='relatedContent' name='Relat&oacute;rios'>
        <p>
            Nesta se&ccedil;&atilde;o voc&ecirc; encontra tr&ecirc;s (3) maneiras de visualizar as Contas e Grupos cadastrados em uma faixa de cinco (5) meses.
        <p>
            A primeira, mais descritiva, est&atilde;o os Grupos com seus totais por m&ecirc;s, com a possibilidade de se ver as Contas por Grupo ao se clicar em um dos Grupos ou no bot&atilde;o 'Abrir Grupos'.
        <p>
            A segunda maneira, os gr&aacute;ficos em rosca, est&atilde;o organizados por m&ecirc;s e apresentam tr&ecirc;s (3) faixas: a mais interna com a divis&atilde;o em <font class="entrada">Entrada</font> e <font class="saida">Sa&iacute;da</font>, a intermedi&aacute;ria com os Grupos e a mais externa com as Contas. Note que para esta vis&atilde;o existe um alinhamento entre as <font class="entrada">Entradas</font> com seus respectivos Grupos, e estes com suas respectivas Contas, e de maneira semelhante para as <font class="saida">Sa&iacute;das</font>. Note que ao passar o ponteiro sobre qualquer das fatias da rosca, ser&aacute; exibido o nome descritivo e o valor correspondentes.
        <p>
            Por &uacute;ltimo existem os gr&aacute;ficos de retas, um para comparar <font class="entrada">Entrada</font> e <font class="saida">Sa&iacute;da</font> na faixa dos cinco (5) meses e outro para comparar os Grupos na faixa dos cinco (5) meses. Note que ao passar o ponteiro em algum dos pontos da reta, ser&aacute; exibido o nome descritivo, o m&ecirc;s e o valor correspondentes.
    </div>

    <h3>Usu&aacute;rios</h3>
    <div class='relatedContent' name='Usu&aacute;rios'>
        <p>
            Os usu&aacute;rios do gestorFinanceiro podem acessar e editar qualquer conta ou grupo e ver os relat&oacute;rios.
    </div>
</div>