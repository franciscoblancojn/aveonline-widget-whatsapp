<?php

namespace Elementor;

if (!defined('ABSPATH')) exit; // Bloquear acceso directo

use Elementor\Group_Control_Dimensions;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

class AVWW_AveFormWhatsapp extends Widget_Base
{

    public function get_name()
    {
        return 'ave_form_guias';
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

    private function _register_controls_before_mesage()
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
            'phone_placeholder',
            [
                'label' => __('Telefono', 'plugin-name'),
                'type' => Controls_Manager::TEXT,
                'default' => __('+57 | Teléfono*', 'plugin-name'),
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
        $this->end_controls_section();
    }
    protected function _register_controls()
    {
        $this->_register_controls_before_mesage();
        $this->_register_controls_formulario();


        $this->addStyleControler('Messages', 'Messages', 'AVWW_Component_Form_messages');
        $this->addStyleControler('Inputs', 'Inputs', 'AVWW_Component_Form_inputs');
        $this->addStyleControler('Boton', 'Boton', 'AVWW_Component_Form_btn');
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        echo AVWW_Component_Widget($settings);
    }
}
