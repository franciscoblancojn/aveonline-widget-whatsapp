<?php

function AVWW_Component_Widget($settings)  {
    ob_start();
    ?>
    <div class="AVWW_Component_Widget">
        <?=AVWW_Component_Messages($settings)?>
        <?=AVWW_Component_Form($settings)?>
    </div>
    <style>
        .AVWW_Component_Widget{
            display: grid;
            gap: 1rem;
        }

    </style>
    <?php
    return ob_get_clean();
}