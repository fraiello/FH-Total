<?php
/*
Plugin Name: FH-Total
Description: Plugin para guardar la fecha y hora, útil para contabilizar los dias que has trabajado.
Version: 1.0
Author: Franco Aiello
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (! defined( 'WPINC')){
    die;
}
require ('include/fh-functions.php');

// Variables del plugin

$plugin_url = WP_PLUGIN_URL. '/fh_total';

/*
*Funciones
*/

//Generar una tabla para el plugin.

function fh_total_create_database_table(){
    global  $wpdb;
    // Crear el nombre de la tabla, con el mismo prefijo que ya tienen las otras tablas creadas en wp_form.
    $tblname = $wpdb->prefix . "fh_total";
    $tbuser = $wpdb->prefix . "users";
    $charset_collate = $wpdb->get_charset_collate();
    // Declarar la tabla del plugin.
    $sql = /** @lang MySQL */
        "CREATE TABLE  {$tblname}
          ( id  int(11)   NOT NULL auto_increment,
            user_id  int(128)   NOT NULL,
            date date,
            hour_start  time,
            hour_finish time DEFAULT '00:00:00',
            hours_total time DEFAULT '00:00:00',
            CONSTRAINT PK_fh_total PRIMARY KEY (id),
            CONSTRAINT FK_fh_total_users FOREIGN KEY (user_id)
                    REFERENCES {$tbuser} (ID)
                    ) CHARSET={$charset_collate}";

    // Upgrade contiene la función dbDelta que comprueba si existe la tabla.
	
    require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );

    // Ejecutar la sentencia sql para crear la tabla.
	
    dbDelta($sql);
}

// Generar una página de configuración.

function fh_total_menu() {
    add_options_page(
        'FH-Total Plugin',
        'FH-Total',
        'manage_options',
        'fh-total',
        'fh_total_options_page'
    );
}

// Añadir a la pagina de configuración en el menu del aministrador.

function fh_total_options_page() {
    if ( !current_user_can( 'manage_options' ) ){
        wp_die( 'Usted no dispone de los permisos para acceder a esta pagina' );
    }

    global $plugin_url;

    $user_id = get_current_user_id();
    $fh_total_date_now = (new DateTime())->format('Y-m-d');

    if (isset($_POST['fh_total_form_submitted'])) {
        $hidden_field = esc_html($_POST['fh_total_form_submitted']);

        if ($hidden_field == 'Y') {

            if (isset($_POST["start"])) {
                $fh_total_hour_start = (new DateTime())->format('H:i');
                fh_total_insert_data($user_id, $fh_total_hour_start, $fh_total_date_now);
                $fh_total_options ['fh_total_state'] = 'Start';
                $fh_options ['fh_total_last_update'] = time();
                update_user_meta($user_id, 'fh_total', $fh_total_options);
            }

            if (isset($_POST["stop"])) {
                $fh_total_hour_stop = (new DateTime())->format('H:i');
                fh_total_update_data($user_id, $fh_total_hour_stop, $fh_total_date_now);
                $fh_total_options ["fh_total_state"] = 'Stop';
                $fh_total_options ['fh_total_last_update'] = time();
                update_user_meta($user_id, 'fh_total', $fh_total_options);
            }
        }
    }

    $fh_total_data = fh_total_get_data($user_id);

    $fh_total_hours_minuts = explode(':', strval($fh_total_data));

    $fh_total_options = get_user_meta($user_id, 'fh_total', 'true');

    if($fh_total_options != ''){

        $fh_total_state = $fh_total_options ['fh_total_state'];
    }
    require( 'include/options-page-wrapper.php');
}

// Crear una clase para el Widget del plugin FH-Total.

class fh_total_Widget extends WP_Widget {

    function __construct() {
        // Instantiate the parent object.
        parent::__construct( false, 'FH-Total' );
    }

    function widget( $args, $instance ) {

        extract ($args);
        $title = apply_filters( 'widget_title', $instance['title'] );

        $user_id = get_current_user_id();

        $fh_total_options = get_user_meta($user_id, 'fh_total', 'true');

        if($fh_total_options != ''){

            $fh_total_state = $fh_total_options ['fh_total_state'];
        }

        $fh_total_data = fh_total_get_data($user_id);

        $fh_total_hours_minuts = explode(':', strval($fh_total_data));

        require('include/front-end.php');
    }

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title']);

        return $instance;
    }

    function form( $instance ) {

        $title = esc_attr($instance['title']);

        require('include/widget-fields.php');
    }
}

// Registra el widget.

function fh_total_register_widgets() {
    register_widget( 'fh_total_Widget' );
}

/*
* Acciones
*/ 

add_action('admin_menu', 'fh_total_menu');
add_action( 'widgets_init', 'fh_total_register_widgets');

/*
 * Hooks
 */

register_activation_hook( __FILE__, 'fh_total_create_database_table' );

?>