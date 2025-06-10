<?php
function AVWW_Component_Messages($settings)  {
    ob_start();
    ?>
    <div id="AVWW_Component_Messages" class="AVWW_Component_Messages">
        <?php
            foreach ($settings['messages_items'] as $k => $value) {
            $text = $value["text"];
            $class = $value["class"];
            ?>
                <div class="AVWW_Component_Message_item <?=$class?>">
                    <?=$text?>
                </div>
            <?php
        }
        ?>
    </div>
    <style>
        .AVWW_Component_Messages{
            display:grid;
            gap:1rem;
            p:last-of-type{
                margin-bottom: 0;
            }
        }
        .AVWW_Component_Message_item{
            max-width: 90%;
            margin-left: auto;
        }
    </style>
    <?php
    return ob_get_clean();
}