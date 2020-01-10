<?php
use models\mdGrupo;
use views\Button;

global $grupo, $grupos;
?>
<script src="<?=URL?>/assets/js/grupos.js" type="text/javascript"></script>
<div class="grid_3">&nbsp;</div>
<div class="grid_5">
    <h2><?=($grupo->getId() != '')?'Edite este':'Adicione um'?> Grupo</h2>
    <form name='grupo' action="index.php?control=ctGrupo" method="POST">
        <input type='hidden' name='grupo[id]' value='<?=$grupo->getId()?>'>
        <div class="line">
            <label for="grupo.strNome">Descritivo</label>
            <br>
            <input type="text" name="grupo[strNome]" id="grupo.strNome"
                value="<?=htmlentities($grupo->getStrNome(), ENT_COMPAT, 'UTF-8')?>"
                title="Um nome gen&eacute;rico para agrupar as contas, ex.: Comida, Roupa"
                required>
        </div>
        <div class="line">
            Tipo
            <br>
            <input type="radio" name="grupo[strTipo]" id="strTipo.<?=mdGrupo::TIPO_ENTRADA?>"
                value="<?=mdGrupo::TIPO_ENTRADA?>"
                title="Marque este caso voc&ecirc; esteja recebendo dinheiro"
                 <?=($grupo->getStrTipo()==mdGrupo::TIPO_ENTRADA)?'checked="checked"':''?>
                required>
            <label for="strTipo.<?=mdGrupo::TIPO_ENTRADA?>" class="entrada"
                title="Marque este campo para indicar que o grupo refere-se &agrave;s entradas financeiras">
                Entrada
            </label>

            <input type="radio" id="strTipo.<?=mdGrupo::TIPO_SAIDA?>" name="grupo[strTipo]"
                value="<?=mdGrupo::TIPO_SAIDA?>"
                style="margin-left: 30px"
                title="Marque este caso voc&ecirc; esteja pagando algo"
                <?=($grupo->getStrTipo()==mdGrupo::TIPO_SAIDA)?'checked="checked"':''?>
                required>
            <label for="strTipo.<?=mdGrupo::TIPO_SAIDA?>" class="saida"
                title="Marque este campo para indicar que o grupo refere-se &agrave;s sa&iacute;das financeiras">
                Sa&iacute;da
            </label>
        </div>
        <br class="clear">
        <div class="btnLine">
            <?php
            $btnSalvar = Button::addSave(array('form' => 'grupo'));
            if($grupo->getId() != '') {
                $btnNovo = Button::addNew(array('form' => 'grupo'));
                $btnExcluir = Button::addDelete(array('form' => 'grupo'));
            ?>
                {{views\Button|<?=$btnNovo?>}}
                {{views\Button|<?=$btnSalvar?>}}
                {{views\Button|<?=$btnExcluir?>}}
                <br/>
                <br/>
                <button id="btnNovaContaGrupo">Adicionar Nova Conta ao Grupo</button>
            <?php } else { ?>
                {{views\Button|<?=$btnSalvar?>}}
            <?php } ?>
        </div>
    </form>
</div>
<div class="grid_4">
    <h1>Grupos cadastrados</h1>
    <div class="relatorio" style="height: 300px">
        <?php
            foreach ($grupos as $grupoR) {?>
            <a href="index.php?control=ctGrupo&id=<?=$grupoR['id']?>"
                class="<?php
                    echo ($grupoR['strTipo']==mdGrupo::TIPO_ENTRADA)?'entrada':'saida';
                    echo ($grupo->getId() == $grupoR['id'])?' selected':''?>"
                title="Editar cadastro">
                <?=$grupoR['strNome']?>
            </a>
        <?php }
        ?>
    </div>
</div>