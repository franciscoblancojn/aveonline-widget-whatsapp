<?php

function AVWW_Component_Widget($settings)  {
    ob_start();
    ?>
    <div class="AVWW_Component_Widget">
        <?=AVWW_Component_Form($settings)?>
        <?=AVWW_Component_Guias($settings)?>
    </div>
    <style>
        .AVWW_Component_Widget{
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            align-items: flex-start;
        }
        @media (max-width:767px) {
            .AVWW_Component_Widget{
                grid-template-columns: 1fr;
            }
        }

    </style>
    <?php
    return ob_get_clean();
}