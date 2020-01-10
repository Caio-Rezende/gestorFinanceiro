<?php
global $resultados;

use models\mdGrupo;
use views\View;
?>
<div class="grid_5 prefix_3 suffix_4" id="searchResult">
    <h2>Termo pesquisado: '<i><?=$_POST['pesquisa']?></i>'</h2>
    <br/>
    <br/>
    <?php
    if (count($resultados['grupos']) == 0 && count($resultados['contas']) == 0) { ?>
        Nenhum resultado encontrado... tente outra vez!
    <?php } else {

        if (count($resultados['grupos']) > 0) { ?>
            <h3>Grupo(s) - <?=count($resultados['grupos'])?> resultado(s)</h3>
            <ul>
        <?php
            foreach ($resultados['grupos'] as $grupo) {
                echo '<li onclick="document.location.href=\'index.php?control=ctGrupo&id=' . $grupo['id'] . '\'"
                          class="grid_4 '.($grupo['strTipo']==mdGrupo::TIPO_ENTRADA?'entrada':'saida').'">'
                        . preg_replace('/('.$_POST['pesquisa'].')/mi', '<i>$1</i>', $grupo['strNome'])
                    . '</li>';
            }?>
            </ul>
            <br class="clear"/>
            <br class="clear"/>
        <?php
        }

        if (count($resultados['contas']) > 0) { ?>
            <h3>Conta(s) - <?=count($resultados['contas'])?> resultado(s)</h3>
            <ul>
        <?php
            foreach ($resultados['contas'] as $conta) {
                $date = strtotime($conta['dteInicial']);
                $date = date('d-', $date)
                    . View::mesDoAno(date('n', $date))
                    . date('-Y', $date);

                echo '<li onclick="document.location.href=\'index.php?control=ctConta&id=' . $conta['id'] . '\'"
                          class="grid_4 '.($conta['strTipo']==mdGrupo::TIPO_ENTRADA?'entrada':'saida').'">'
                        . preg_replace('/('.$_POST['pesquisa'].')/mi', '<i>$1</i>', $conta['strNome'])
                        .($conta['intParcelas'] > 1
                            ? ' ' . $conta['intParcelaAtual'] 
                                . '/' . $conta['intParcelas'] 
                            : '')
                        . " ({$date}) : {$conta['numValor']}"
                    . '</li>';
            }?>
            </ul>
        <?php
        }
    }
?>
</div>