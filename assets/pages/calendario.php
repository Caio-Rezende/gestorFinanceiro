<?php
use views\View;

global $dateSearch, $tipoDte, $tipoPago;
?>
<script src="<?=URL?>/assets/js/contas.js" type="text/javascript"></script>
<script src="<?= URL ?>/assets/js/fullcalendar.min.js" type="text/javascript"></script>
<script src="<?= URL ?>/assets/js/calendario.js" type="text/javascript"></script>
<script type='text/javascript'>
    var month = <?=date('n', $dateSearch)-1?>;
    var year  = <?=date('Y', $dateSearch)?>;
</script>
<style type="text/css">
  @import url(<?=URL?>/assets/css/calendario.css);
</style>
<form action='index.php?control=Calendario' method='POST' name="formCalendario" id="formCalendario">
    <div class="grid_5">
        Exibir pela data de:
        <label>
            <input type="radio" name="tipoDte" value="dte_inicial"
                   onclick="document.formCalendario.submit();"
                   <?=($tipoDte == 'dte_inicial') ? 'checked="checked"' : ''?>/>
            <span>Pagamento</span>
        </label>
        <label>
            <input type="radio" name="tipoDte" value="dte_compra"
                   onclick="document.formCalendario.submit();"
                   <?=($tipoDte == 'dte_compra') ? 'checked="checked"' : ''?>/>
            <span>Compra</span>
        </label>
        <br/>
        Exibir:
        <label>
            <input type="radio" name="tipoPago" value="all"
                   onclick="document.formCalendario.submit();"
                   <?=($tipoPago == 'all') ? 'checked="checked"' : ''?>/>
            <span>Todos</span>
        </label>
        <label>
            <input type="radio" name="tipoPago" value="1"
                   onclick="document.formCalendario.submit();"
                   <?=($tipoPago == '1') ? 'checked="checked"' : ''?>/>
            <span>Pagos</span>
        </label>
        <label>
            <input type="radio" name="tipoPago" value="0"
                   onclick="document.formCalendario.submit();"
                   <?=($tipoPago == '0') ? 'checked="checked"' : ''?>/>
            <span>Não Pagos</span>
        </label>
    </div>
    <div class="grid_2 suffix_3" style="text-align: center">
        <br/>
        <input type='hidden' name='dateSearch' id='dateSearch' value="<?=$dateSearch?>"/>
        <button type="submit"
            onclick="$('#dateSearch').val('<?
                echo mktime(0, 0, 0, date('n', $dateSearch) -1, 1, date('Y', $dateSearch))
            ?>')"
            title="Veja o calend&oacute;rio deslocado de um m&ecirc;s para o passado">
            &lAarr;
        </button>
        <button type="submit"
            onclick="$('#dateSearch').val('<?
                echo mktime(0, 0, 0, date('n', $dateSearch) +1, 1, date('Y', $dateSearch))
            ?>')"
            title="Veja o calend&oacute;rio deslocado de um m&ecirc;s para o futuro">
            &rAarr;
        </button>
    </div>
    <div class="grid_2" style="text-align: right; cursor:pointer"
         onclick='document.location.href="index.php?control=Calendario"'>
        Hoje: <i><?=date('d') . ' de ' . View::mesDoAno(date('n')) . ' de ' . date('Y')?></i>
    </div>
</form>
<div class='grid_2 prefix_5 suffix_5'>
    <h3>
        <?=View::mesDoAno(date('n', $dateSearch))?>
    </h3>
</div>
<div class='grid_12' id='calendario'
     data-src='<?=URL?>/index.php?control=Calendario&method=getEventos&tipoDte=<?=$tipoDte?>&tipoPago=<?=$tipoPago?>'>

</div>
<br class='clear'/>
<br/>
<br/>