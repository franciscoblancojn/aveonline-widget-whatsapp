<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function AVWW_router_send_contact_post(WP_REST_Request $request) {
    try {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode($request->get_body(),true);
        echo wp_json_encode(array(
            "status" => 200,
            "data"=>$data 
        ));
    } catch (Exception $e) {
        echo wp_json_encode(array(
            "status" => 400,
            "data" => $e->getMessage().""
        ));
    }
}

function AVWW_on_load_router_send_contact()
{
    register_rest_route( AVWW_RUTE, 'send-contact', array(
      'methods' => 'POST',
      'callback' => 'AVWW_router_send_contact_post',
    ) );
}

add_action( 'rest_api_init', 'AVWW_on_load_router_send_contact' );