<?php
function AVWW_Component_Top($settings)  {
    ob_start();
    ?>
    <div id="AVWW_Component_Top" class="AVWW_Component_Top">
        <img src="<?=$settings["avatar_img"]["url"]?>" alt="">
        <div class="AVWW_Component_Top_content_name">
            <div>
                <div class="AVWW_Component_Top_name">
                <?=$settings["avatar_name"]?>
            </div>
            <div class="AVWW_Component_Top_online">
                Online
            </div>
            </div>
        </div>
        <label for="AVWW_Component_Widget_btn_checkbox" class="AVWW_Component_Top_close">
            <svg width="16px" class="fenext_svg " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z" fill="currentColor"></path></svg>
        </label>
    </div>
    <style>
        .AVWW_Component_Top{
            display:grid;
            grid-template-columns: 44px 1fr auto;
            gap:.5rem;
            align-items: center;
        }
        .AVWW_Component_Top_content_name{
            display: flex;
            align-items: center;
        }
        .AVWW_Component_Widget_btn_checkbox{
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .AVWW_Component_Top_online{
            vertical-align: middle;
            display: flex;
            align-items: center;
            gap: .5rem;
            &::before{
                content: "";
                display: inline-block;
                width: .5rem;
                height: .5rem;
                border-radius: 100%;
                background-color: #1DBF72;
            }
        }
    </style>
    <?php
    return ob_get_clean();
}