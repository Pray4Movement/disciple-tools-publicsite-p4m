<?php
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly.

class DT_Publicsite_Endpoints
{
    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    public function __construct() {
        add_filter( 'dt_allow_rest_access', [ $this, '_authorize_url' ], 100, 1 );
        add_action( 'rest_api_init', [ $this, 'add_api_routes' ] );
    }
    public function add_api_routes() {
        $namespace = 'publicsite_p4m/v1';

        register_rest_route(
            $namespace, '/public_endpoint', [
                'methods'  => WP_REST_Server::CREATABLE,
                'callback' => [ $this, 'public_endpoint' ],
                'permission_callback' => '__return_true',
            ]
        );
    }

    public function public_endpoint( WP_REST_Request $request ) {
        $params = $request->get_params();

        dt_write_log( $params );

        // sanitize
        if ( isset( $params['email'] ) && empty( $params['email'] )) {
            return new WP_Error(__METHOD__, 'Failed to find email address' );
        }

        $params = dt_recursive_sanitize_array($params);

        $email = $params['email'];
        $name = $params['name'] ?? $email;
        $phone = $params['phone'] ?? '';

        $fields = [
            "title" => $name,
            'contact_email' => [
                'values' => [
                    [ 'value' => $email ]
                ]
            ],
            'sources' => [
                'values' => [
                    [ 'value' => 'dt_frontpage_p4m']
                ]
            ]
        ];
        if ( ! empty( $phone ) ) {
            $fields['contact_phone'] = [
                'values' => [
                    [ 'value' => $phone ]
                ]
            ];
        }
        $contact_id = DT_Posts::create_post('contacts', $fields, false, false );



        dt_write_log( $contact_id );



        return $contact_id;
    }
    public function _authorize_url( $authorized ){
        if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), 'publicsite_p4m/v1/public_endpoint' ) !== false ) {
            $authorized = true;
        }
        return $authorized;
    }
}
DT_Publicsite_Endpoints::instance();
