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
        <label class="AVWW_Component_Form_content_input_phone">
            <select
                id="AVWW_Component_Form_input_code"
                type="tel"
                placeholder="<?= ($settings["code_placeholder"] ?? "Codigo") ?>"
                class="AVWW_Component_Form_input"
                default="+57">
                <option value="+57">+57</option>
                <option value="+93">+93</option>
                <option value="+355">+355</option>
                <option value="+49">+49</option>
                <option value="+213">+213</option>
                <option value="+376">+376</option>
                <option value="+244">+244</option>
                <option value="+1">+1</option>
                <option value="+1">+1</option>
                <option value="+599">+599</option>
                <option value="+966">+966</option>
                <option value="+54">+54</option>
                <option value="+374">+374</option>
                <option value="+297">+297</option>
                <option value="+61">+61</option>
                <option value="+43">+43</option>
                <option value="+994">+994</option>
                <option value="+1">+1</option>
                <option value="+973">+973</option>
                <option value="+880">+880</option>
                <option value="+1">+1</option>
                <option value="+32">+32</option>
                <option value="+501">+501</option>
                <option value="+229">+229</option>
                <option value="+1">+1</option>
                <option value="+375">+375</option>
                <option value="+591">+591</option>
                <option value="+387">+387</option>
                <option value="+267">+267</option>
                <option value="+55">+55</option>
                <option value="+673">+673</option>
                <option value="+359">+359</option>
                <option value="+226">+226</option>
                <option value="+257">+257</option>
                <option value="+975">+975</option>
                <option value="+238">+238</option>
                <option value="+855">+855</option>
                <option value="+237">+237</option>
                <option value="+1">+1</option>
                <option value="+235">+235</option>
                <option value="+56">+56</option>
                <option value="+86">+86</option>
                <option value="+357">+357</option>
                <option value="+269">+269</option>
                <option value="+242">+242</option>
                <option value="+243">+243</option>
                <option value="+682">+682</option>
                <option value="+850">+850</option>
                <option value="+82">+82</option>
                <option value="+225">+225</option>
                <option value="+506">+506</option>
                <option value="+385">+385</option>
                <option value="+53">+53</option>
                <option value="+45">+45</option>
                <option value="+253">+253</option>
                <option value="+593">+593</option>
                <option value="+20">+20</option>
                <option value="+503">+503</option>
                <option value="+971">+971</option>
                <option value="+291">+291</option>
                <option value="+421">+421</option>
                <option value="+386">+386</option>
                <option value="+34">+34</option>
                <option value="+1">+1</option>
                <option value="+372">+372</option>
                <option value="+251">+251</option>
                <option value="+298">+298</option>
                <option value="+63">+63</option>
                <option value="+358">+358</option>
                <option value="+679">+679</option>
                <option value="+33">+33</option>
                <option value="+241">+241</option>
                <option value="+220">+220</option>
                <option value="+995">+995</option>
                <option value="+233">+233</option>
                <option value="+350">+350</option>
                <option value="+1">+1</option>
                <option value="+30">+30</option>
                <option value="+299">+299</option>
                <option value="+590">+590</option>
                <option value="+502">+502</option>
                <option value="+44">+44</option>
                <option value="+224">+224</option>
                <option value="+240">+240</option>
                <option value="+245">+245</option>
                <option value="+592">+592</option>
                <option value="+509">+509</option>
                <option value="+504">+504</option>
                <option value="+852">+852</option>
                <option value="+36">+36</option>
                <option value="+91">+91</option>
                <option value="+62">+62</option>
                <option value="+964">+964</option>
                <option value="+98">+98</option>
                <option value="+353">+353</option>
                <option value="+64">+64</option>
                <option value="+354">+354</option>
                <option value="+677">+677</option>
                <option value="+1">+1</option>
                <option value="+1">+1</option>
                <option value="+972">+972</option>
                <option value="+39">+39</option>
                <option value="+1">+1</option>
                <option value="+81">+81</option>
                <option value="+44">+44</option>
                <option value="+962">+962</option>
                <option value="+7">+7</option>
                <option value="+254">+254</option>
                <option value="+996">+996</option>
                <option value="+686">+686</option>
                <option value="+965">+965</option>
                <option value="+856">+856</option>
                <option value="+266">+266</option>
                <option value="+371">+371</option>
                <option value="+961">+961</option>
                <option value="+231">+231</option>
                <option value="+218">+218</option>
                <option value="+423">+423</option>
                <option value="+370">+370</option>
                <option value="+352">+352</option>
                <option value="+389">+389</option>
                <option value="+261">+261</option>
                <option value="+60">+60</option>
                <option value="+265">+265</option>
                <option value="+960">+960</option>
                <option value="+223">+223</option>
                <option value="+356">+356</option>
                <option value="+44">+44</option>
                <option value="+212">+212</option>
                <option value="+596">+596</option>
                <option value="+230">+230</option>
                <option value="+222">+222</option>
                <option value="+52">+52</option>
                <option value="+373">+373</option>
                <option value="+377">+377</option>
                <option value="+976">+976</option>
                <option value="+258">+258</option>
                <option value="+95">+95</option>
                <option value="+264">+264</option>
                <option value="+674">+674</option>
                <option value="+977">+977</option>
                <option value="+505">+505</option>
                <option value="+227">+227</option>
                <option value="+234">+234</option>
                <option value="+672">+672</option>
                <option value="+47">+47</option>
                <option value="+687">+687</option>
                <option value="+64">+64</option>
                <option value="+968">+968</option>
                <option value="+31">+31</option>
                <option value="+92">+92</option>
                <option value="+507">+507</option>
                <option value="+675">+675</option>
                <option value="+595">+595</option>
                <option value="+51">+51</option>
                <option value="+689">+689</option>
                <option value="+48">+48</option>
                <option value="+351">+351</option>
                <option value="+1">+1</option>
                <option value="+974">+974</option>
                <option value="+44">+44</option>
                <option value="+420">+420</option>
                <option value="+1">+1</option>
                <option value="+262">+262</option>
                <option value="+250">+250</option>
                <option value="+40">+40</option>
                <option value="+7">+7</option>
                <option value="+212">+212</option>
                <option value="+685">+685</option>
                <option value="+1">+1</option>
                <option value="+378">+378</option>
                <option value="+508">+508</option>
                <option value="+239">+239</option>
                <option value="+1">+1</option>
                <option value="+290">+290</option>
                <option value="+1">+1</option>
                <option value="+221">+221</option>
                <option value="+381">+381</option>
                <option value="+248">+248</option>
                <option value="+232">+232</option>
                <option value="+65">+65</option>
                <option value="+963">+963</option>
                <option value="+252">+252</option>
                <option value="+94">+94</option>
                <option value="+27">+27</option>
                <option value="+249">+249</option>
                <option value="+46">+46</option>
                <option value="+41">+41</option>
                <option value="+597">+597</option>
                <option value="+268">+268</option>
                <option value="+992">+992</option>
                <option value="+66">+66</option>
                <option value="+886">+886</option>
                <option value="+255">+255</option>
                <option value="+670">+670</option>
                <option value="+228">+228</option>
                <option value="+690">+690</option>
                <option value="+676">+676</option>
                <option value="+1">+1</option>
                <option value="+216">+216</option>
                <option value="+993">+993</option>
                <option value="+90">+90</option>
                <option value="+688">+688</option>
                <option value="+380">+380</option>
                <option value="+256">+256</option>
                <option value="+598">+598</option>
                <option value="+998">+998</option>
                <option value="+678">+678</option>
                <option value="+58">+58</option>
                <option value="+84">+84</option>
                <option value="+681">+681</option>
                <option value="+967">+967</option>
                <option value="+260">+260</option>
                <option value="+263">+263</option>
                <option value="+599">+599</option>
                <option value="+599">+599</option>
                <option value="+357">+357</option>
            </select>
            <input
                id="AVWW_Component_Form_input_phone"
                type="tel"
                placeholder="<?= ($settings["phone_placeholder"] ?? "+57 | Teléfono*") ?>"
                class="AVWW_Component_Form_input" />
        </label>
        <div class="AVWW_Component_Form_text">
            <?= ($settings["form_text"] ?? "") ?>
        </div>
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

        .AVWW_Component_Form_content_input_phone {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: .5rem;
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
            phone,
            code
        }) => {
            try {
                const myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");

                const listCampanas = [
                    <?php
                    foreach ($settings['campana_items'] as $k => $value) {
                        $text = $value["text"];
                        echo '"' . $text . '",';
                    }
                    ?>
                ];
                let campana = "<?= $_GET["campana"] ?? "" ?>";
                if (!campana || campana == "" || campana == "undefined") {
                    for (let i = 0; i < listCampanas.length; i++) {
                        if (window.location.href.includes(`/${listCampanas[i]}/`)) {
                            campana = listCampanas[i];
                            break;
                        }
                    }
                }


                const url = "<?= $settings["api_url"] ?? "https://avechat-hubspot.api.aveonline.co/api/form-campana/ave-chat/create-contact" ?>";
                const raw = JSON.stringify({
                    url,
                    campana,
                    name,
                    phone,
                    code
                });

                const requestOptions = {
                    method: "POST",
                    headers: myHeaders,
                    body: raw,
                    redirect: "follow"
                };

                const response = await fetch("/wp-json/<?= AVWW_RUTE ?>/send-contact", requestOptions)
                const result = await response.json()

                window.location.href = "<?= $settings["api_redirect"] ?>"
                return response
            } catch (e) {
                throw e
            }
        }
        const AVWW_onSendContact = async () => {
            const name = `${document.getElementById("AVWW_Component_Form_input_name")?.value ?? ''}`;
            const code = `${document.getElementById("AVWW_Component_Form_input_phone_code")?.value ?? '+57'}`;
            const phone = `${document.getElementById("AVWW_Component_Form_input_phone")?.value ?? ''}`;
            if (name && phone) {
                const btn = document.getElementById("AVWW_Component_Form_btn")
                try {
                    btn.classList.add("loader")
                    const result = await AVWW_onSendContact_Request({
                        name,
                        phone,
                        code
                    });
                    if (typeof AVWW_onSendContact_callback == 'function') {
                        AVWW_onSendContact_callback(result)
                    }
                } finally {
                    btn.classList.remove("loader")
                }
            }
        }
    </script>
<?php
    return ob_get_clean();
}
