<?php
/**
 * FH-Total Uninstall
 *
 * Uninstalls the plugin deletes user roles, tables, and options.
 *
*/
// if uninstall.php is not called by WordPress, die

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
global $wpdb;

// Borrar options.

$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'fh_total%';" );

// Borrar usermeta.

$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'fh_total%';" );

// Eliminar la tabla del la base de datos.

$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}fh_total");

// Limpiar la cache.

wp_cache_flush();