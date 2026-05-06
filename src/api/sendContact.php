<?php

if (!defined('ABSPATH')) exit;

/**
 * ============================================================
 * ROUTE
 * ============================================================
 */

add_action('rest_api_init', function () {

    register_rest_route(AVWW_RUTE, '/send-contact', [
        'methods' => 'POST',
        'callback' => 'AVWW_router_send_contact_post',
        'permission_callback' => '__return_true',
    ]);
});

/**
 * ============================================================
 * ENDPOINT
 * ============================================================
 */

function AVWW_router_send_contact_post(WP_REST_Request $request)
{
    try {

        $body = $request->get_json_params();

        $tel = ($body['code'] ?? '') . ($body['phone'] ?? '');

        $data = [
            'id_avechat' => preg_replace('/\D/', '', $tel),
            'name' => trim($body['name'] ?? ''),
            'phone' => preg_replace('/\D/', '', $body['phone'] ?? ''),
            'indicativo_telefono' => $body['code'] ?? '',
            'campana' => $body['campana'] ?? null,
        ];

        /**
         * ============================================================
         * VALIDACIONES
         * ============================================================
         */

        if (empty($data['name'])) {
            throw new Exception('name required');
        }

        if (empty($data['phone'])) {
            throw new Exception('phone required');
        }

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

        /**
         * ============================================================
         * SI FALLÓ
         * ============================================================
         */

        if (empty($userAveChat['create'])) {
            throw new Exception(
                $userAveChat['message'] ?? 'error create user avechat'
            );
        }

        /**
         * ============================================================
         * CREAR LEAD EN AVE
         * ============================================================
         */

        $userAve = AVWW_ave_crear_lead([
            'id_aveChat' => $data['id_avechat'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'indicativo_telefono' => $data['indicativo_telefono'],
            'id_company_hs' => '-1',
            'id_hs' => '-1',
            'id_campana' => $data['campana'],
        ]);

        $id_empresa_ave = $userAve['data']['company']['idempresa'] ?? null;

        $id_user_ave = $userAve['data']['lead']['id'] ?? null;

        $url_ave_pre_register = $userAve['data']['lead']['urlPreRegister'] ?? null;

        if (!$id_user_ave && !$id_empresa_ave) {
            throw new Exception('user ave not created');
        }

        /**
         * ============================================================
         * GUARDAR CUSTOM FIELDS
         * ============================================================
         *
         * 🔥 SOLO 1 PETICIÓN
         *
         */

        $avechatSave = AVWW_avechat_contact_save_custom_field(
            [
                'phone' => $data['phone'],
                'first_name' => $data['name'],
                'last_name' => '',
            ],
            [
                'id_user_ave' => $id_user_ave,
                'id_lead' => $id_user_ave,
                'url_ave_pre_register' => $url_ave_pre_register,
                'id_empresa_ave' => $id_empresa_ave,
                'campana' => $data['campana'],
            ]
        );

        return new WP_REST_Response([
            'success' => true,
            'message' => '✅ Contacto creado correctamente.',
            'data' => [
                'avechat' => $userAveChat,
                'ave' => $userAve,
                'custom_fields' => $avechatSave,
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
 * REQUEST AVECHAT
 * ============================================================
 */

function AVWW_avechat_request(
    $url,
    $method = 'GET',
    $body = null
) {

    $CONFIG = get_option(AVWW_KEY, []);

    $token = $CONFIG['token'] ?? '';

    if (!$token) {
        throw new Exception('Token AveChat no configurado');
    }

    $args = [
        'method' => strtoupper($method),
        'headers' => [
            'Accept' => 'application/json',
            'X-ACCESS-TOKEN' => $token,
        ],
        'timeout' => 60,
    ];

    if ($body !== null) {

        $args['body'] = json_encode($body);

        $args['headers']['Content-Type'] = 'application/json';
    }

    $response = wp_remote_request(
        'https://panel.lucidbot.co/api/' . ltrim($url, '/'),
        $args
    );

    if (is_wp_error($response)) {
        throw new Exception(
            $response->get_error_message()
        );
    }

    $status = wp_remote_retrieve_response_code($response);

    $responseBody = wp_remote_retrieve_body($response);

    $result = json_decode($responseBody, true);

    if ($status >= 400) {

        throw new Exception(
            $result['error']['message']
                ?? $result['message']
                ?? 'Error AveChat API'
        );
    }

    return $result;
}

/**
 * ============================================================
 * GET USER AVECHAT
 * ============================================================
 */

function AVWW_avechat_get_user_by_id($id)
{
    try {

        $result = AVWW_avechat_request(
            "users/{$id}"
        );

        if (empty($result['first_name'])) {
            return null;
        }

        return $result;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * ============================================================
 * CREATE USER AVECHAT
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
 * CREATE USER IF NOT EXIST
 * ============================================================
 */

function AVWW_avechat_create_user_if_not_exist($data)
{
    try {

        $user = AVWW_avechat_get_user_by_id(
            $data['id_avechat']
        );

        /**
         * ============================================================
         * YA EXISTE
         * ============================================================
         */

        if ($user) {

            return [
                'isNew' => false,
                'create' => true,
                'user' => $user,
            ];
        }

        /**
         * ============================================================
         * CREAR
         * ============================================================
         */

        $create = AVWW_avechat_create_user($data);

        return [
            'isNew' => true,
            'create' => true,
            'result' => $create,
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
 * GUARDAR MUCHOS CUSTOM FIELDS EN 1 PETICIÓN
 * ============================================================
 */

function AVWW_avechat_contact_save_custom_field(
    $user,
    $fields = [],
    $flows = []
) {

    $actions = [];

    /**
     * ============================================================
     * CUSTOM FIELDS
     * ============================================================
     */

    foreach ($fields as $key => $value) {

        if (
            $value === null ||
            $value === '' ||
            $value === 'undefined'
        ) {
            continue;
        }

        $actions[] = [
            'action' => 'set_field_value',
            'field_name' => $key,
            'value' => (string) $value,
        ];
    }

    /**
     * ============================================================
     * FLOWS
     * ============================================================
     */

    foreach ($flows as $flow_id) {

        $actions[] = [
            'action' => 'send_flow',
            'flow_id' => $flow_id,
        ];
    }

    /**
     * ============================================================
     * PAYLOAD
     * ============================================================
     */

    $payload = array_merge($user, [
        'actions' => $actions,
    ]);

    return AVWW_avechat_request(
        'users',
        'POST',
        $payload
    );
}

/**
 * ============================================================
 * CREAR LEAD EN AVE
 * ============================================================
 */

function AVWW_ave_crear_lead($data)
{
    $url = 'https://api.aveonline.co/api-onboarding/public/api/v1/onboarding/createLeadWhatsapp';

    $payload = [
        'name' => $data['name'] ?? '',

        'phone' => substr(
            preg_replace('/\D/', '', $data['phone'] ?? ''),
            0,
            10
        ),

        'id_hs' => $data['id_hs'] ?? '-1',

        'id_company_hs' => $data['id_company_hs'] ?? '-1',

        'id_keybe' => $data['id_aveChat'] ?? null,

        'indicativo_telefono' => $data['indicativo_telefono'] ?? null,

        'id_campana' => $data['id_campana'] ?? null,
    ];

    $response = wp_remote_post($url, [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
        'body' => json_encode($payload),
        'timeout' => 60,
    ]);

    if (is_wp_error($response)) {
        throw new Exception(
            $response->get_error_message()
        );
    }

    $status = wp_remote_retrieve_response_code($response);

    $body = wp_remote_retrieve_body($response);

    $result = json_decode($body, true);

    if ($status >= 400) {

        $error =
            $result['errors'][0]['title']
            ?? $result['message']
            ?? 'Error creando lead en AVE';

        throw new Exception($error);
    }

    return $result;
}