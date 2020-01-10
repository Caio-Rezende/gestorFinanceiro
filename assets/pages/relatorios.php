<?php
use models\mdGrupo;
use views\View;

global $dateSearch, $grupos, $contas, $contasOrd, $mesBusca, $tipoPago;
$gruposTipos = array();
?>

<script src="<?=URL?>/assets/js/jquery.jqplot.min.js" type="text/javascript"></script>
<script src="<?=URL?>/assets/js/jqplot.donutRenderer.min.js" type="text/javascript"></script>
<script src="<?=URL?>/assets/js/jqplot.dateAxisRenderer.min.js" type="text/javascript"></script>
<script src="<?=URL?>/assets/js/jqplot.highlighter.min.js" type="text/javascript"></script>
<script src="<?=URL?>/assets/js/relatorios.js" type="text/javascript"></script>
<style type="text/css">
  @import url(<?=URL?>/assets/css/relatorios.css);
</style>

<form action="index.php?control=Relatorio" method="POST" name="formRelatorios" id="formRelatorios">
    <div class="grid_6">
        <button type="button" id="btnAbrirFechar"></button>
        <br/>
        Exibir:
        <label>
            <input type="radio" name="tipoPago" value="all"
                   onclick="document.formRelatorios.submit();"
                   <?=($tipoPago == 'all') ? 'checked="checked"' : ''?>/>
            <span>Todos</span>
        </label>
        <label>
            <input type="radio" name="tipoPago" value="1"
                   onclick="document.formRelatorios.submit();"
                   <?=($tipoPago == '1') ? 'checked="checked"' : ''?>/>
            <span>Pagos</span>
        </label>
        <label>
            <input type="radio" name="tipoPago" value="0"
                   onclick="document.formRelatorios.submit();"
                   <?=($tipoPago == '0') ? 'checked="checked"' : ''?>/>
            <span>Não Pagos</span>
        </label>
    </div>
    <div class="grid_2 suffix_2" style="text-align: center">
        <br/>
        <input type="hidden" name="dateSearch" id="dateSearch" value="<?=date('Y-m-01', $dateSearch)?>">
        <button type="submit"
            onclick="$('#dateSearch').val('<?=date('Y-m-01',
                    mktime(0, 0, 0, date('n', $dateSearch) -1, 1, date('Y', $dateSearch))
            )?>');"
            title="Veja o relat&oacute;rio deslocado de um m&ecirc;s para o passado">
            &lAarr;
        </button>
        <button type="submit"
            onclick="$('#dateSearch').val('<?=date('Y-m-01',
                    mktime(0, 0, 0, date('n', $dateSearch) +1, 1, date('Y', $dateSearch))
            )?>')"
            title="Veja o relat&oacute;rio deslocado de um m&ecirc;s para o futuro">
            &rAarr;
        </button>
    </div>
    <div class="grid_2" style="text-align: right; cursor:pointer"
         onclick='document.location.href="index.php?control=Relatorio"'>
        Hoje: <i><?=date('d') . ' de ' . View::mesDoAno(date('n')) . ' de ' . date('Y')?></i>
    </div>
</form>

<br class="clear">

<div class='grid_2'>
    <h1>&nbsp;</h1>
    <div class='relatorio entrada' style='padding: 3px;'>
        <div>
        <?php
        $tf = FALSE;
        if (count($grupos) == 0) { ?>
        </div>
    </div>
    <div class="entrada" style="text-align: right; padding: 3px 3px 3px 3px">Parcial: </div>
    <br>
    <div class='relatorio saida' style='padding: 3px;'>
        <div>
        <?php
        } else {
            foreach($grupos as $grupo) {
                $gruposTipos[$grupo['id']] = $grupo['strTipo'];
                if (!$tf && $grupo['strTipo'] == mdGrupo::TIPO_SAIDA)  {
                    $tf = TRUE;?>
        </div>
    </div>
    <div class="entrada" style="text-align: right; padding: 3px 3px 3px 3px">Parcial: </div>
    <br>
    <div class='relatorio saida' style='padding: 3px;'>
        <div>
                <?php } ?>
            <a href="#grupo.<?=$grupo['id']?>."
                onclick="<?=$grupo['id']?>"
                title="<?=  str_replace('"', '\\"', $grupo['strNome'])?>"
                id="grupo.<?=$grupo['id']?>."
                name="grupo.<?=$grupo['id']?>.">
                <?=$grupo['strNome']?>
            </a>
            <div class="childGrupo" name="child.<?=$grupo['id']?>">
            <?php
                $nomesPresentes = array();
                $i = 0;
                foreach ($contas as $cnt) {
                    if ($cnt['idGrupo'] == $grupo['id'] && in_array($cnt['strNome'], $nomesPresentes) == FALSE ) {
                        $nomesPresentes[] = $cnt['strNome'];?>
                <a href="#childGrp.<?=$grupo['id']?>.<?=$i?>."
                    title="<?=  str_replace('"', '\\"', $cnt['strNome'])?>"
                    id="childGrp.<?=$grupo['id']?>.<?=$i?>."
                    name="childGrp.<?=$grupo['id']?>.<?=$i++?>.">
                    <?=$cnt['strNome']?>
                </a>
                <?php }
                }?>
            </div>
        <?php }
        }?>
        </div>
    </div>
    <div class="saida" style="text-align: right; padding: 3px 3px 3px 3px">Parcial: </div>
    <br>
    <div style="text-align: right; padding: 3px 3px 3px 3px">Total: </div>
</div>
<?php
$charts = 0;
foreach ($contasOrd as $mes => $grupos) {
    $time     = mktime(0, 0, 0, date('n', $dateSearch) + $charts - 2, 1, date('Y', $dateSearch));
    $total = $parcial = 0;?>
    <div class='grid_2 values' style="text-align: right" name="chartID.<?=$charts?>" data-date="<?=date('d-M-y', $time)?>">
        <h<?=($mes == $mesBusca)?3:1?>><?=$mes?></h<?=($mes == $mesBusca)?3:1?>>
        <div class='relatorio entrada' style='padding: 3px 3px 3px 3px'>
            <?php
            $tf = FALSE;
            if (count ($grupos) == 0) { ?>
        </div>
        <div class="entrada" name="parcial" id="parcialE.<?=$charts?>"
             style='padding: 3px 40px 3px 3px'><?=number_format($parcial, 2, ',', '.')?></div>
        <br>
        <div class='relatorio saida' style='padding: 3px 3px 3px 3px'>
            <?php
            } else {
                foreach($grupos as $id => $grupo) {
                    $valor    = $grupo['parcial'];
                    $indexCnt = $grupo['contas'];
                    if (!$tf && $gruposTipos[$id] == mdGrupo::TIPO_SAIDA) {
                        $tf = TRUE;?>
        </div>
        <div class="entrada" name="parcial" id="parcialE.<?=$charts?>"
             style='padding: 3px 40px 3px 3px'><?=number_format($parcial, 2, ',', '.')?></div>
        <br>
        <div class='relatorio saida' style='padding: 3px 3px 3px 3px'>
                <?php $parcial = 0;
                    }
                    $parcial += $valor;
                    $total   = ($gruposTipos[$id] == mdGrupo::TIPO_ENTRADA)
                        ? $total + $valor
                        : $total - $valor;
                    ?>
                <a href="#grupo.<?=$id?>." class="relatorioValor" style="width: auto"
                    onclick="<?=$id?>"
                    name="grupo.<?=$id?>.">
                    <?=($valor > 0.00 ? number_format($valor, 2, ',', '.') : '-')?>
                </a>
                <?php
                    $totalPresente = array();
                    foreach ($indexCnt as $index) {
                        if (substr($index, 0, 2) == 'z.') {
                            $index    = (int) substr($index, 2);
                            $addValor = FALSE;
                        } else {
                            $addValor = TRUE;
                        }
                        $cnt = $contas[$index];
                        if (array_key_exists($cnt['strNome'], $totalPresente) === FALSE) {
                            $totalPresente[$cnt['strNome']] = 0;
                        }
                        $totalPresente[$cnt['strNome']] += ($addValor ? $cnt['numValor'] : 0);
                    }
                    $i = 0;?>
                <div class="childGrupoValor" name='child.<?=$id?>'>
                <?php foreach ($totalPresente as $nome => $val) {
                        echo '<div name="childGrp.' . $id . '.' . $i++ . '.">'
                            . ($val > 0.00 ? number_format($val, 2, ',', '.') : '-')
                            . '</div>';
                    }?>
                </div>
            <?php }
            }?>
        </div>
        <div class="saida" name="parcial" id="parcialS.<?=$charts?>"
             style='padding: 3px 40px 3px 3px'><?=number_format($parcial, 2, ',', '.')?></div>
        <br>
        <div class='relatorio <?=($total >= 0)?'entrada':'saida'?>'
             id="total.<?=$charts++?>"
              style='padding: 3px 3px 3px 3px'>
            <?=number_format($total, 2, ',', '.')?>
        </div>
    </div>
<?php } ?>
<br class="clear">
<br>
<br>
<?php for($charts = -2; $charts < 3; $charts++){
    $time = strtotime($charts . ' months', $dateSearch);
    $mesChart = View::mesDoAno(date('n', $time)).date(' (Y)', $time);?>
    <div class="grid_4" id="chart.<?=$charts + 2?>"
         data-date="<?=date('d-M-y', $time)?>"
         name="<h<?=($mesBusca == $mesChart)?3:1?>><?=$mesChart?></h<?=($mesBusca == $mesChart)?3:1?>>"
         >

    </div>
<?php } ?>
<div class="grid_4" id="chartResumo"></div>
<br class="clear">
<div class="grid_12" id="chartGruposResumo" style="height: 600px"></div>
<br class="clear">
<br>
<br>
<br>