<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function AVWW_router_send_contact_post(WP_REST_Request $request)
{
    try {

        $body = $request->get_json_params();

        /*
        {
            "campana": "123",
            "name": "Francisco",
            "phone": "3103557200",
            "code": "+57"
        }
        */

        $tel = ($body['code'] ?? '') . ($body['phone'] ?? '');

        $data = [
            'id_avechat' => preg_replace('/\D/', '', $tel),
            'name' => $body['name'] ?? '',
            'phone' => preg_replace('/\D/', '', $body['phone'] ?? ''),
            'indicativo_telefono' => $body['code'] ?? '',
            'campana' => $body['campana'] ?? null,
        ];

        /**
         * ============================================================
         * CREAR USUARIO EN AVECHAT SI NO EXISTE
         * ============================================================
         */

        $userAveChat = AVWW_avechat_create_user_if_not_exist([
            'id_avechat' => $data['id_avechat'],
            'first_name' => $data['name'],
            'last_name' => '',
            'phone' => $data['phone'],
        ]);

        if (
            empty($userAveChat['isNew']) ||
            empty($userAveChat['create'])
        ) {
            throw new Exception('user exist');
        }

        /**
         * ============================================================
         * CREAR LEAD EN AVE
         * ============================================================
         */

        $dataCrearLead = [
            'id_aveChat' => $data['id_avechat'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'indicativo_telefono' => $data['indicativo_telefono'],
            'id_company_hs' => null,
            'id_campana' => $data['campana'],
        ];

        $userAve = AVWW_ave_crear_lead($dataCrearLead);

        $id_empresa_ave = $userAve['data']['company']['idempresa'] ?? null;
        $id_user_ave = $userAve['data']['lead']['id'] ?? null;
        $url_ave_pre_register = $userAve['data']['lead']['urlPreRegister'] ?? null;

        if (!$id_user_ave && !$id_empresa_ave) {
            throw new Exception('user ave not created');
        }

        /**
         * ============================================================
         * GUARDAR CUSTOM FIELDS EN AVECHAT
         * ============================================================
         */

        $dataSaveCustomFields = [
            'user_id' => $data['id_avechat'],
            'obj' => [
                'id_user_ave' => $id_user_ave,
                'id_lead' => $id_user_ave,
                'url_ave_pre_register' => $url_ave_pre_register,
                'id_empresa_ave' => $id_empresa_ave,
                'campana' => $data['campana'],
            ],
        ];

        $resultAveChatSaveFields = AVWW_avechat_save_custom_fields(
            $dataSaveCustomFields
        );

        return new WP_REST_Response([
            'success' => true,
            'message' => '✅ Contacto creado correctamente.',
            'data' => [
                'userAve' => $userAve,
                'avechat' => $resultAveChatSaveFields,
            ],
        ], 200);
    } catch (Exception $error) {

        return new WP_REST_Response([
            'success' => false,
            'message' => '❌ Error al crear el contacto.',
            'error' => $error->getMessage(),
        ], 500);
    }
}

/**
 * ============================================================
 * AVECHAT CONFIG
 * ============================================================
 */

function AVWW_avechat_request($url, $method = 'GET', $body = null)
{
    $token = 'TOKEN_AVECHAT';

    $args = [
        'method' => $method,
        'headers' => [
            'Accept' => 'application/json',
            'X-ACCESS-TOKEN' => $token,
        ],
        'timeout' => 60,
    ];

    if ($body) {
        $args['body'] = is_array($body)
            ? json_encode($body)
            : $body;

        $args['headers']['Content-Type'] = 'application/json';
    }

    $response = wp_remote_request(
        'https://panel.lucidbot.co/api/' . $url,
        $args
    );

    if (is_wp_error($response)) {
        throw new Exception($response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);

    return json_decode($body, true);
}

/**
 * ============================================================
 * OBTENER USUARIO AVECHAT
 * ============================================================
 */

function AVWW_avechat_get_user_by_id($id)
{
    $result = AVWW_avechat_request("users/{$id}");

    if (empty($result['first_name'])) {
        return null;
    }

    return $result;
}

/**
 * ============================================================
 * CREAR USUARIO AVECHAT
 * ============================================================
 */

function AVWW_avechat_create_user($data)
{
    return AVWW_avechat_request(
        'users',
        'POST',
        [
            'phone' => $data['phone'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? '',
        ]
    );
}

/**
 * ============================================================
 * CREAR USUARIO SI NO EXISTE
 * ============================================================
 */

function AVWW_avechat_create_user_if_not_exist($data)
{
    try {

        $user = AVWW_avechat_get_user_by_id(
            $data['id_avechat']
        );

        if (!$user) {

            $create = AVWW_avechat_create_user($data);

            return [
                'isNew' => true,
                'create' => true,
                'result' => $create,
            ];
        }

        return [
            'isNew' => false,
            'create' => true,
        ];
    } catch (Exception $e) {

        return [
            'create' => false,
            'message' => $e->getMessage(),
        ];
    }
}

/**
 * ============================================================
 * CUSTOM FIELDS IDS
 * ============================================================
 */

function AVWW_avechat_custom_fields()
{
    return [
        'id_user_ave' => 123,
        'id_lead' => 124,
        'url_ave_pre_register' => 125,
        'id_empresa_ave' => 126,
        'campana' => 127,
    ];
}

/**
 * ============================================================
 * GUARDAR CUSTOM FIELD
 * ============================================================
 */

function AVWW_avechat_set_custom_field($user_id, $key, $value)
{
    $fields = AVWW_avechat_custom_fields();

    if (!isset($fields[$key])) {
        return null;
    }

    $field_id = $fields[$key];

    return AVWW_avechat_request(
        "users/{$user_id}/custom_fields/{$field_id}",
        'POST',
        [
            'value' => $value,
        ]
    );
}

/**
 * ============================================================
 * GUARDAR MULTIPLES CUSTOM FIELDS
 * ============================================================
 */

function AVWW_avechat_save_custom_fields($data)
{
    $results = [];

    foreach ($data['obj'] as $key => $value) {

        if (
            $value === null ||
            $value === '' ||
            $value === 'undefined'
        ) {
            continue;
        }

        $results[$key] = AVWW_avechat_set_custom_field(
            $data['user_id'],
            $key,
            $value
        );
    }

    return $results;
}

/**
 * ============================================================
 * CREAR LEAD EN AVE
 * ============================================================
 */

function AVWW_ave_crear_lead($data)
{
    $response = wp_remote_post(
        'https://TU_API_AVE.com/api/crear-lead',
        [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'body' => json_encode($data),
            'timeout' => 60,
        ]
    );

    if (is_wp_error($response)) {
        throw new Exception(
            $response->get_error_message()
        );
    }

    return json_decode(
        wp_remote_retrieve_body($response),
        true
    );
}


function AVWW_on_load_router_send_contact()
{
    register_rest_route( AVWW_RUTE, 'send-contact', array(
      'methods' => 'POST',
      'callback' => 'AVWW_router_send_contact_post',
    ) );
}

add_action( 'rest_api_init', 'AVWW_on_load_router_send_contact' );