<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Bloquear acceso directo

use Elementor\Group_Control_Dimensions;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Controls_Manager;

class AVWW_AveFormWhatsapp extends Widget_Base
{

    public function get_name()
    {
        return 'ave_form_whatsapp';
    }

    public function get_title()
    {
        return __('Aveonline Whatsapp', 'plugin-name');
    }

    public function get_icon()
    {
        return 'eicon-star';
    }

    public function get_categories()
    {
        return ['general'];
    }
    private function addStyleControler($key, $name, $class)
    {

        $this->start_controls_section(
            $key . '_style',
            [
                'label' => __($name, 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        // Control de color del texto
        $this->add_control(
            $key . '_color',
            [
                'label' => __('Color', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .' . $class => 'color: {{VALUE}};--color: {{VALUE}};',
                ],
            ]
        );

        // Control de tipografía
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => $key . '_typography',
                'selector' => '{{WRAPPER}} .' . $class,
            ]
        );

        // Control de espaciado (margen y padding)
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => $key . '_box_shadow',
                'selector' => '{{WRAPPER}} .' . $class,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => $key . '_background',
                'label' => __('Fondo', 'plugin-name'),
                'types' => ['classic', 'gradient', 'video'],
                'selector' => '{{WRAPPER}} .' . $class,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => $key . '_border',
                'label' => __('Borde', 'plugin-name'),
                'selector' => '{{WRAPPER}} .' . $class,
            ]
        );
        $this->add_control(
            $key . '_border_radius',
            [
                'label' => __('Radio de borde', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .' . $class => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            $key . '_padding',
            [
                'label' => __('Padding', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .' . $class  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            $key . '_margin',
            [
                'label' => __('Margin', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .' . $class  => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section(); // Cerrar la sección de estilos

    }

    private function _register_controls_before_message()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Mensajes', 'plugin-name'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Inicializar el repeater
        $repeater = new \Elementor\Repeater();

        // Campo Label (Texto)
        $repeater->add_control(
            'text',
            [
                'label' => __('Texto', 'plugin-name'),
                'type' => Controls_Manager::WYSIWYG,
            ]
        );
        // Campo Class (Texto para CSS personalizado)
        $repeater->add_control(
            'class',
            [
                'label' => __('Clase CSS', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'description' => __('Agrega clases CSS personalizadas', 'plugin-name'),
            ]
        );
        // Agregar el repeater a la sección principal
        $this->add_control(
            'messages_items',
            [
                'label' => __('Lista de Mensajes', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->end_controls_section();
    }
    private function _register_controls_formulario()
    {
        $this->start_controls_section(
            'content_section_from',
            [
                'label' => __('Formulario', 'plugin-name'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'name_placeholder',
            [
                'label' => __('Nombre', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __('¿Cómo te llamas?', 'plugin-name'),
            ]
        );
        $this->add_control(
            'code_placeholder',
            [
                'label' => __('Codigo', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Codigo', 'plugin-name'),
            ]
        );
        $this->add_control(
            'phone_placeholder',
            [
                'label' => __('Telefono', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __('+57 | Teléfono*', 'plugin-name'),
            ]
        );
        $this->add_control(
            'form_text',
            [
                'label' => __('Texto', 'plugin-name'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __("Tus datos se usan únicamente para brindarte la asesoría gratuita. Cero spam.", 'plugin-name'),
            ]
        );
        $this->add_control(
            'btn',
            [
                'label' => __('Boton', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Continuar y hablar por WhatsApp 🚀', 'plugin-name'),
            ]
        );

        // Inicializar el repeater
        $repeater = new \Elementor\Repeater();

        // Campo Label (Texto)
        $repeater->add_control(
            'text',
            [
                'label' => __('Nombre', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
            ]
        );
        // Agregar el repeater a la sección principal
        $this->add_control(
            'campana_items',
            [
                'label' => __('Lista de Campañas Permitidas por url', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{ text }}',
                'default' => [
                    [ 'text' => 'envios-nacionales' ],
                    [ 'text' => 'envios-nacionales-ads' ],
                    [ 'text' => 'envios-nacionales-1000' ],
                    [ 'text' => 'envios-nacionales-gads' ],
                    [ 'text' => 'envios-contraentrega-gads' ],
                    [ 'text' => 'envios-internacionales-gads' ],
                    [ 'text' => 'crea-tu-cuenta-billetera-aveonline' ],
                    [ 'text' => 'crea-una-cuenta-gratis-para-tu-ecommerce-360' ],
                    [ 'text' => 'logistica-inteligente-par-tu-tienda-dropshipping' ],
                ],
            ]
        );

        $this->end_controls_section();
    }
    private function _register_controls_api()
    {
        $this->start_controls_section(
            'content_section_api',
            [
                'label' => __('Api', 'plugin-name'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'api_url',
            [
                'label' => __('Url', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __("https://avechat-hubspot.api.aveonline.co/api/form-campana/ave-chat/create-contact", 'plugin-name'),
            ]
        );
        $this->add_control(
            'api_redirect',
            [
                'label' => __('Redirect', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __('https://api.whatsapp.com/send/?phone=573054202125&text=Hola+%2AAveonline%2A.+Necesito+m%C3%A1s+informaci%C3%B3n+sobre+Aveonline+https%3A%2F%2Faveonline.co&type=phone_number&app_absent=0', 'plugin-name'),
            ]
        );
        $this->end_controls_section();
    }
    private function _register_controls_avatar()
    {
        $this->start_controls_section(
            'content_section_avatar',
            [
                'label' => __('Avatar', 'plugin-name'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
            'avatar_name',
            [
                'label' => __('Nombre', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __("Alan", 'plugin-name'),
            ]
        );
        $this->add_control(
            'avatar_img',
            [
                'label' => __('Imagen', 'plugin-name'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => AVWW_URL."src/img/avatar.png"
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function _register_controls()
    {
        $this->_register_controls_avatar();
        $this->_register_controls_before_message();
        $this->_register_controls_formulario();
        $this->_register_controls_api();


        $this->addStyleControler('Content', 'Content', 'AVWW_Component_Widget_content_inter');
        $this->addStyleControler('Avatar_Name', 'Avatar Name', 'AVWW_Component_Top_name');
        $this->addStyleControler('Avatar_Online', 'Avatar Online', 'AVWW_Component_Top_online');
        $this->addStyleControler('Avatar_Close', 'Avatar Close', 'AVWW_Component_Top_close');
        $this->addStyleControler('Messages', 'Messages', 'AVWW_Component_Message_item');
        $this->addStyleControler('Inputs', 'Inputs', 'AVWW_Component_Form_input');
        $this->addStyleControler('Text', 'Text', 'AVWW_Component_Form_text');
        $this->addStyleControler('Boton', 'Boton', 'AVWW_Component_Form_btn');
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo AVWW_Component_Widget($settings);
    }
}
