<?php

function AVWW_Component_Widget($settings)
{
    ob_start();
?>
    <div class="AVWW_Component_Widget">
        <div class="AVWW_Component_Widget_content">
            <div class="AVWW_Component_Widget_content_inter">
                <?= AVWW_Component_Top($settings) ?>
                <?= AVWW_Component_Messages($settings) ?>
                <?= AVWW_Component_Form($settings) ?>
            </div>
        </div>
        <label class="AVWW_Component_Widget_btn">
            <svg width="59" height="59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="29.5" cy="29.5" r="29.5" fill="#4AEB67" />
                <path fill-rule="evenodd" clip-rule="evenodd" d="M37.4037 32.9906C36.9755 32.7763 34.8699 31.7405 34.4773 31.5977C34.0848 31.4548 33.7993 31.3834 33.5138 31.812C33.2283 32.2406 32.4076 33.2049 32.1577 33.4906C31.9079 33.7763 31.6581 33.8121 31.2299 33.5977C30.8016 33.3835 29.4217 32.9314 27.7859 31.4728C26.5128 30.3376 25.6533 28.9355 25.4034 28.5069C25.1537 28.0783 25.3768 27.8466 25.5913 27.6332C25.7839 27.4414 26.0195 27.1332 26.2336 26.8832C26.4478 26.6331 26.5191 26.4545 26.6619 26.1689C26.8047 25.8831 26.7333 25.6331 26.6262 25.4188C26.5191 25.2045 25.6627 23.0972 25.3058 22.24C24.9582 21.4052 24.6051 21.5182 24.3422 21.5051C24.0927 21.4926 23.8069 21.49 23.5214 21.49C23.2359 21.49 22.772 21.5972 22.3794 22.0257C21.9868 22.4543 20.8805 23.4902 20.8805 25.5974C20.8805 27.7046 22.4151 29.7404 22.6292 30.0262C22.8434 30.3119 25.6491 34.6363 29.9451 36.4908C30.9669 36.9319 31.7647 37.1952 32.3866 37.3927C33.4126 37.7184 34.3462 37.6725 35.0841 37.5623C35.9068 37.4393 37.6179 36.5265 37.9748 35.5264C38.3316 34.5264 38.3316 33.6692 38.2245 33.4906C38.1175 33.3121 37.832 33.2049 37.4037 32.9906ZM29.5896 43.6565H29.5838C27.0275 43.6555 24.5203 42.9691 22.3331 41.6715C22.0002 41.474 21.6021 41.418 21.2277 41.5162L20.6246 41.6744C18.7566 42.1643 17.0588 40.4481 17.5689 38.5855L17.6922 38.1353C17.7993 37.7442 17.7374 37.3261 17.5215 36.9827C16.0955 34.7153 15.3424 32.0945 15.3434 29.4036C15.3466 21.5515 21.7373 15.1633 29.5952 15.1633C33.4003 15.1647 36.9771 16.648 39.6668 19.3398C42.3564 22.0317 43.8368 25.6099 43.8354 29.4152C43.8321 37.2678 37.4415 43.6565 29.5896 43.6565ZM41.7138 17.2944C38.4778 14.0557 34.1744 12.2712 29.5894 12.2694C20.1422 12.2694 12.4534 19.9553 12.4496 29.4026C12.4486 31.8209 12.9545 34.1931 13.9264 36.3766C14.3915 37.4215 14.5674 38.5893 14.2653 39.6925C13.3945 42.8722 16.2927 45.802 19.4817 44.9658L19.6983 44.909C20.7861 44.6237 21.9321 44.7884 22.9695 45.2226C25.0535 46.0947 27.3022 46.5494 29.5826 46.5504H29.5895H29.5896C39.0359 46.5504 46.7254 38.8635 46.7293 29.4162C46.731 24.838 44.9499 20.533 41.7138 17.2944Z" fill="white" />
            </svg>
            <input type="checkbox" class="AVWW_Component_Widget_btn_checkbox" id="AVWW_Component_Widget_btn_checkbox" name="AVWW_Component_Widget_btn_checkbox"/>
        </label>
    </div>
    <style>
        .AVWW_Component_Widget_content{
            position: absolute;
            bottom: 0;
            right: 0;
            width: max-content;
            max-width: min(95dvw, 320px);
            transition: .5s;
            transform: translateX(100dvw);
            max-height: calc(100dvh - 2rem);
            overflow: auto;
        }
        .AVWW_Component_Widget_content_inter{
            display: grid;
            gap: 1rem;
        }
        .AVWW_Component_Widget:has(.AVWW_Component_Widget_btn_checkbox:checked){
            .AVWW_Component_Widget_content{
                transform:none;
            }
            .AVWW_Component_Widget_btn{
                opacity: 0;
            }
        }
        .AVWW_Component_Widget{
            position: fixed;
            z-index: 99999;
            right: 1rem;
            bottom: 1rem;
        }
        .AVWW_Component_Widget_btn{
            cursor: pointer;
        }
        .AVWW_Component_Widget_btn_checkbox{
            position: absolute !important;
            scale: 0;
        }
    </style>
<?php
    return ob_get_clean();
}
