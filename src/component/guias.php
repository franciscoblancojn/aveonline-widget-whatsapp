<?php
function AVWW_Component_Guias($settings)  {
    ob_start();
    ?>
    <div id="AVWW_Component_Guias" class="AVWW_Component_Guias">
        
    </div>
    <style>
        .AVWW_Component_Guias{
            display:grid;
            gap:1rem;
        }
        .AVWW_Component_Guia{
            display:grid;
            gap:1rem;
        }
    </style>
    <script>
        const AVWW_onGetHtmlGuia = (guia) => {
            if(!guia?.transportadora){
                return `
                    <div class="AVWW_Component_Guia">
                        <div class="AVWW_Component_Guia_numeroguia">
                            <?=$settings["guia_numeroguia"]?>: 
                            <strong>${guia?.numeroguia}</strong>
                        </div>
                        <div class="AVWW_Component_Guia_status">
                            Estado: Guía no Encontrada
                        </div>
                    </div>
                `
            }
            return `
                <div class="AVWW_Component_Guia">
                    <div class="AVWW_Component_Guia_numeroguia">
                        <?=$settings["guia_numeroguia"]?>: 
                        <strong>${guia?.numeroguia}</strong>
                    </div>
                    <?php
                        foreach ($settings['guia_items'] as $k => $value) {
                            $key = $value["key"];
                            $label = $value["label"];
                            $class = $value["class"];
                            ?>
                                <div class="AVWW_Component_Guia_item AVWW_Component_Guia_item_<?=$key?> <?=$class?>">
                                    <?=$label?>:
                                    <strong>${guia?.<?=$key?>}</strong>
                                </div>
                            <?php
                        }
                    ?>
                    <div class="AVWW_Component_Guia_status">
                        <?=$settings["guia_nombreEstadoAve"]?>: 
                        <strong>${guia?.nombreEstadoAve}</strong>
                    </div>

                </div>
            `
        }
        const AVWW_onGetGuias_callback = (guias)=>{
            const content = document.getElementById("AVWW_Component_Guias")
            const html = guias.map(AVWW_onGetHtmlGuia).join("")
            content.innerHTML = html
        }
    </script>
    <?php
    return ob_get_clean();
}