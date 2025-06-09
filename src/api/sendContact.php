<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function AVWW_router_send_contact_post(WP_REST_Request $request) {
    try {
        header('Content-Type: application/json; charset=utf-8');
        $data = json_decode($request->get_body(),true);

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => $data['url'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response);

        echo wp_json_encode(array(
            "status" => 200,
            "data"=>$data ,
            "response"=>$response ,
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