<?php
use models\mdGrupo;
use views\View;
use views\Button;

global $conta, $grupos, $mdGrupo, $contasOrd;
?>
<script src="<?=URL?>/assets/js/contas.js" type="text/javascript"></script>
<div class="grid_3">
    <h1>Grupo*</h1>
    <div class="relatorio" style="height: 300px">
        <?php
            foreach ($grupos as $grupo) {?>
            <a href="index.php?control=ctConta&idGrupo=<?=$grupo['id']
                . (isset($_GET['date'])
                    ?'&date='.$_GET['date']
                    :'')
                . (isset($_GET['tipoDte'])
                    ?'&tipoDte='.$_GET['tipoDte']
                    :'')?>"
                title="Escolher este grupo"
                class="<?php
                   echo ($grupo['strTipo'] == mdGrupo::TIPO_ENTRADA ? 'entrada' : 'saida');
                   echo ($grupo['id'] == $_GET['idGrupo'] ? ' selected' : '');
                ?>">
               <?=$grupo['strNome']?>
            </a>
        <?php }
        ?>
    </div>
</div>
<?php if ($mdGrupo->getId() == '') {?>
<div class="grid_9">
    &lArr; Selecione &agrave; esquerda um grupo para editar as contas nele.
</div>
<?php } else {?>
    <div class="grid_5">
        <h2><?=($conta->getId() != '')?'Edite esta':'Adicione uma'?> Conta</h2>
        <form name='conta' action="index.php?control=ctConta" method="POST">
            <input type='hidden' name='conta[id]' value='<?=$conta->getId()?>'>
            <input type='hidden' name='conta[idGrupo]' value='<?=$_GET['idGrupo']?>'>
            <div class="line">
                <label for="conta.strNome">Descritivo</label>
                <br>
                <input type="text" name="conta[strNome]" id="conta.strNome"
                    value="<?=htmlentities($conta->getStrNome(), ENT_COMPAT, 'UTF-8')?>"
                    title="O nome da loja, item comprado, algo que te faça lembrar da
                    conta, ex.: Panificadora Tal, T&ecirc;nis Branco"
                    required>
            </div>
            <div class="line">
                <label for="conta.numValor">Valor da parcela</label>
                <br>
                <input type="number" name="conta[numValor]" id="conta.numValor"
                    value="<?=$conta->getNumValor()?>"
                    title="Valor pago àquela conta, ou o valor da parcela paga"
                    min=".01" step=".01"
                    required>
            </div>
            <div class="line grid_3 alpha">
                <label for="conta.dteInicial">Data Inicial Pagamento</label>
                <br>
                <input type="date" name="conta[dteInicial]" id="conta.dteInicial"
                    value="<?=$conta->getDteInicial()?>"
                    title="O dia do pagamento"
                    required>
            </div>
            <div class="line grid_2 omega">
                <label for="conta.intParcelas">Parcelas Futuras</label>
                <br>
                <input type="number" name="conta[intParcelas]" id="conta.intParcelas"
                    value="<?
                        echo (
                            $conta->getIntParcelas()
                                ? $conta->getIntParcelas() - $conta->getIntParcelaAtual()
                                : 0
                        );
                    ?>"
                    title="A quantidade de parcelas"
                    min='0' step='1'
                    required>
            </div>
            <div class="line grid_3 alpha">
                <label for="conta.dteCompra">Data Compra</label>
                <br>
                <input type="date" name="conta[dteCompra]" id="conta.dteCompra"
                    value="<?=$conta->getDteCompra() ? $conta->getDteCompra() : date('Y-m-d')?>"
                    title="A data da compra, caso seja diferente do pagamento">
            </div>
            <div class="line grid_2 omega">
                <label for="conta.bolPaga">Paga?</label>
                <input type="checkbox" name="conta[bolPaga]" id="conta.bolPaga"
                    value="1"
                    title="A conta já foi paga?"
                    <?=$conta->getBolPaga() ? 'checked="checked"' : ''?>>
            </div>
            <br class="clear">
            <div class="btnLine">
                <?php
                $btnSalvar = Button::addSave(array('form' => 'conta'));
                if($conta->getId() != '') {
                    $btnNovo = Button::addNew(array(
                        'form'   => 'conta',
                        'action' => '&idGrupo=' . $_GET['idGrupo']
                    ));
                    $btnExcluir = Button::addDelete(array('form' => 'conta'));
                ?>
                    {{views\Button|<?=$btnNovo?>}}
                    {{views\Button|<?=$btnSalvar?>}}
                    {{views\Button|<?=$btnExcluir?>}}
                <?php } else { ?>
                    {{views\Button|<?=$btnSalvar?>}}
                <?php } ?>
            </div>
        </form>
    </div>
    <div class="grid_4">
        <h1>Contas cadastradas para (<?php
            $class = ($mdGrupo->getStrTipo() == mdGrupo::TIPO_ENTRADA ? 'entrada' : 'saida' );
            echo '<font class="' . $class . '">'
                . $mdGrupo->getStrNome()
                . '</font>';
        ?>)</h1>
        <div class="relatorio" style="height: 300px">
            <?php
                $thisMonth = mktime(0, 0, 0, date('n')+1, 0);
                foreach ($contasOrd as $mes => $contas) {
                    $h = ($mes == $thisMonth) ? 3 : 2;
                    $mes = View::mesDoAno(date('n', $mes))
                        . date(' (Y)', $mes);
                    echo "<h{$h}>".$mes."</h{$h}>";
                    foreach ($contas as $contaR){?>
                        <a href="index.php?control=ctConta&id=<?=$contaR['id']?>"
                            title="Editar cadastro"<?=($conta->getId() == $contaR['id'])?' class="selected"':''?>>
                            <?=$contaR['strNome']?> (<?php
                                echo '<font class="' . $class . '">'
                                    . number_format($contaR['numValor'], 2, ',', '.')
                                    . '</font>';
                            ?>)
                            <input type="checkbox"
                                data-id='<?=$contaR['id']?>'
                                data-strNome="<?=htmlentities($contaR['strNome'], ENT_COMPAT, 'UTF-8')?>"
                                onclick='alteraBolPaga(this)'
                                title="A conta já foi paga?"
                                <?=$contaR['bolPaga'] ? 'checked="checked"' : ''?>>
                        </a>
                <?php }
                }
            ?>
        </div>
    </div>
<?}?>