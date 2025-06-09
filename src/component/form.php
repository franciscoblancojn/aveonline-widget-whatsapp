<?php

function AVWW_Component_Form($settings)
{
    ob_start();
    ?>
    <div class="AVWW_Component_Form">
        <label>
            <input
                id="AVWW_Component_Form_input_name"
                type="text"
                placeholder="<?= ($settings["name_placeholder"] ?? "¿Cómo te llamas?") ?>"
                class="AVWW_Component_Form_input" />
        </label>
        <label>
            <input
                id="AVWW_Component_Form_input_phone"
                type="tel"
                placeholder="<?= ($settings["phone_placeholder"] ?? "+57 | Teléfono*") ?>"
                class="AVWW_Component_Form_input" />
        </label>
        <div class="AVWW_Component_Form_content_btn">
            <button id="AVWW_Component_Form_btn" class="AVWW_Component_Form_btn" onclick="AVWW_onSendContact()">
                <?= ($settings["btn"] ?? "Buscar") ?>
            </button>
        </div>
    </div>
    <style>
        .AVWW_Component_Form {
            display: grid;
            gap: .5rem;
        }

        .AVWW_Component_Form_input {
            outline: none;
        }

        .AVWW_Component_Form_btn.loader {
            color: transparent !important;
        }

        .AVWW_Component_Form_btn.loader:before {
            content: "";
            width: 1.5rem;
            aspect-ratio: 1/1;
            border: 0.3rem solid var(--color);
            border-top-color: transparent;
            border-radius: 100%;
            margin: auto;
            animation: AVWW-to-rotate 1s infinite;
            display: block;
            position: absolute;
            inset: 0;
        }

        @keyframes AVWW-to-rotate {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <script>
        const AVWW_onSendContact_Request = async ({
            name,
            phone
        }) => {
            try {
                const myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");

                const url = "<?=$settings["api_url"] ?? "https://avechat-hubspot.api.aveonline.co/api/form-campana/ave-chat/create-contact"?>";
                const raw = JSON.stringify({
                    url,
                    campana:"<?= $_GET["campana"] ?>",
                    name,
                    phone
                });

                const requestOptions = {
                    method: "POST",
                    headers: myHeaders,
                    body: raw,
                    redirect: "follow"
                };

                const response = await fetch("/wp-json/<?=AVWW_RUTE?>/send-contact", requestOptions)
                const result = await response.json()

                // window.location.href = "<?=$settings["api_redirect"]?>"
                return response
            } catch (e) {
                throw e
            } 
        }
        const AVWW_onSendContact = async () => {
            const name = `${document.getElementById("AVWW_Component_Form_input_name")?.value ?? ''}`;
            const phone = `${document.getElementById("AVWW_Component_Form_input_phone")?.value ?? ''}`;
            if (name && phone) {
                try {
                    const btn = document.getElementById("AVWW_Component_Form_btn")
                    btn.classList.add("loader")
                    const result = await AVWW_onSendContact_Request({
                        name,
                        phone
                    });
                    if (typeof AVWW_onSendContact_callback == 'function') {
                        AVWW_onSendContact_callback(result)
                    }
                } finally{
                    btn.classList.remove("loader")
                }
            }
        }
    </script>
<?php
    return ob_get_clean();
}
