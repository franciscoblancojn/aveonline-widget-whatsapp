<?php

function AVWW_Component_Widget($settings)  {
    ob_start();
    ?>
    <div class="AVWW_Component_Widget">
        <?=AVWW_Component_Messages($settings)?>
        <?=AVWW_Component_Form($settings)?>
    </div>
    <style>
        

    </style>
    <?php
    return ob_get_clean();
}