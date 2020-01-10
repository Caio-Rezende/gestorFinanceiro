<?php
namespace views;

use controllers\Login;

/**
 * Description of Search
 *
 * @author caiorezende
 */
class Search extends Component {

    public function render($params = array()) {
        if (Login::isLogged()) {?>
        <form id="searchSpace" method="POST" action="index.php?control=Search" class="grid_4">
            <div style="width:70%; display:inline-block;">
                <input type="text" name="pesquisa" placeholder="Pesquise aqui" required >
            </div>
            <button type="submit">ok</button>
        </form>
        <?php
        }
    }

}