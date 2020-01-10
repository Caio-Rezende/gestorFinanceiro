<?php
use views\Button;

global $usuario, $usuarios;
?>
<div class="grid_3">&nbsp;</div>
<div class="grid_5">
    <h2><?=($usuario->getId() != '')?'Edite este':'Adicione um'?> Usu&aacute;rio</h2>
    <form name='usuario' action="index.php?control=ctUsuario" method="POST">
        <input type='hidden' name='usuario[id]' value='<?=$usuario->getId()?>'>
        <div class="line">
            <label for="usuario.strNome">Nome</label>
            <br>
            <input type="text" name="usuario[strNome]" id="usuario.strNome"
                   value="<?=htmlentities($usuario->getStrNome(), ENT_COMPAT, 'UTF-8')?>"
                   title="Seu nome"
                   required>
        </div>
        <div class="line">
            <label for="usuario.strLogin">Usu&aacute;rio</label>
            <br>
            <input type="text" name="usuario[strLogin]" id="usuario.strLogin"
                   value="<?=$usuario->getStrLogin()?>"
                   title='Nome para acesso no sistema, digite de 3 a 12 caracteres alfabeticos sem caracteres especiais'
                   required pattern="[A-Za-z]{3,12}">
        </div>
        <div class="line">
            <label for="usuario.pasSenha">Senha</label>
            <br>
            <input type="password" name="usuario[pasSenha]" id="usuario.pasSenha"
                   value=""
                <?if($usuario->getId()==""){?>
                   required
                   pattern="[A-Za-z0-9]{6,10}"
                   title='Senha para acesso ao sistema, digite de 6 a 10 caracteres alpha num&eacute;ricos sem caracteres especiais'
                <?}else{?>
                   pattern="|[A-Za-z0-9]{6,10}"
                   title='Senha para acesso ao sistema, deixe em branco caso queira manter a mesma ou digite de 6 a 10 caracteres alpha num&eacute;ricos sem caracteres especiais para troc&aacute;-la'
                <?}?>
                >
        </div>
        <br class="clear">
        <div class="btnLine">
            <?php
            $btnSalvar = Button::addSave(array('form' => 'usuario'));
            if($usuario->getId() != '') {
                $btnNovo = Button::addNew(array('form' => 'usuario'));
                $btnExcluir = Button::addDelete(array('form' => 'usuario'));
            ?>
                {{views\Button|<?=$btnNovo?>}}
                {{views\Button|<?=$btnSalvar?>}}
                {{views\Button|<?=$btnExcluir?>}}
            <?php } else { ?>
                {{views\Button|<?=$btnSalvar?>}}
            <?php }
            if ($_SESSION['USER']['id'] == 1) {?>
                <button type="submit" onclick="document.forms['usuario'].action = document.forms['usuario'].action + '&method=salvarSemVinculo'">
                    Salvar sem vínculo
                </button>
            <?php } ?>
        </div>
    </form>
</div>
<div class="grid_4">
    <h1>Usu&aacute;rios cadastrados</h1>
    <div class="relatorio" style="height: 300px">
        <?php
            foreach ($usuarios as $usuarioR) {?>
            <a href="index.php?control=ctUsuario&id=<?=$usuarioR['id']?>"
               title="Editar cadastro"<?=($usuario->getId() == $usuarioR['id'])?' class="selected"':''?>>
                <?=$usuarioR['strNome'].' ('.$usuarioR['strLogin'].')'?>
            </a>
        <?php }
        ?>
    </div>
</div>