<?php

function AVWW_Component_Form($settings)  {
    ob_start();
    $defaultGuias = '';
    if($settings['use_get'] == "yes"){
        $defaultGuias = $_GET['guias'] ?? '';
    }
    ?>
    <div class="AVWW_Component_Form">
        <h4 class="AVWW_Component_Form_title">
            <?=($settings["title"] ?? "Rastrea tu guía")?>
        </h4>
        <div class="AVWW_Component_Form_alert">
            <?=($settings["alert"] ?? "Hola, recuerda que puedes rastrear múltiples guías, separándolas por comas.")?>
        </div>
        <div class="AVWW_Component_Form_text">
            <?=($settings["text"] ?? "En caso de salir alguna NOVEDAD, debes comunicarte directamente con la tienda en donde hiciste la compra, pues son ellos quienes deben resolverla, para que tu pedido llegue pronto.")?>
        </div>
        <label>
            <div class="AVWW_Component_Form_label">
                <?=($settings["label"] ?? "Número de guía")?>
            </div>
            <input
                id="AVWW_Component_Form_input"
                type="text"
                placeholder="<?=($settings["placeholder"] ?? "Número de guía")?>"
                class="AVWW_Component_Form_input"
                value="<?=$defaultGuias?>"
            />
        </label>
        <div class="AVWW_Component_Form_content_btn">
            <button id="AVWW_Component_Form_btn" class="AVWW_Component_Form_btn" onclick="AVWW_onGetGuias()">
                <?=($settings["btn"] ?? "Buscar")?>
            </button>
        </div>
    </div>
    <style>
        .AVWW_Component_Form{
            display:grid;
            gap:.5rem;
        }
        .AVWW_Component_Form_title{
            width:100%;
        }
        .AVWW_Component_Form_text{
            width:100%;
        }
        .AVWW_Component_Form_input{
            outline: none;
        }
        .AVWW_Component_Form_content_btn{    
            display: flex;
        }
        .AVWW_Component_Form_btn{
            margin-left:auto;
            position: relative;
        }
        .AVWW_Component_Form_btn.loader{
            color:transparent !important;
        }
        .AVWW_Component_Form_btn.loader:before{
            content:"";
            width: 1.5rem;
            aspect-ratio: 1/1;
            border: 0.3rem solid var(--color);
            border-top-color: transparent;
            border-radius: 100%;
            margin: auto;
            animation: AVWW-to-rotate 1s infinite;
            display:block;
            position: absolute;
            inset:0;
        }
        @keyframes AVWW-to-rotate {
            to {
                transform: rotate(360deg);
            }
        }

    </style>
    <script>
        const AVWW_onGetGuias_Request = async(n) => {
            const numeroguia = `${n}`.replaceAll(" ","")
            try {
                const myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");

                const raw = JSON.stringify({
                    "tipo": "infoGuiaP2PV3",
                    "guia": numeroguia
                });

                const requestOptions = {
                    method: "POST",
                    headers: myHeaders,
                    body: raw,
                    redirect: "follow"
                };

                const response = await fetch("https://app.aveonline.co/api/comunes/v2.0/guiasNacionalP2P.php", requestOptions)
                const result = await response.json()
                return {
                    ...result?.data?.[0],
                    numeroguia
                }
            } catch (e){
                return {
                    numeroguia
                };
            }
        }
        const AVWW_onGetGuias = async ()=>{
            const guias = `${document.getElementById("AVWW_Component_Form_input")?.value ?? ''}`.split(',')
            if(guias && guias.length > 0 && guias[0]!=''){
                const btn = document.getElementById("AVWW_Component_Form_btn")
                btn.classList.add("loader")
                const guiasResult = await Promise.all(guias.map(guia => AVWW_onGetGuias_Request(guia)));
                btn.classList.remove("loader")
                if(typeof AVWW_onGetGuias_callback == 'function' ){
                    AVWW_onGetGuias_callback(guiasResult)
                }
            }
        }
        AVWW_onGetGuias()
    </script>
    <?php
    return ob_get_clean();
}