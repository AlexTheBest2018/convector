<?php
function create_convector_db(){
    global $wpdb;
    $table_name = $wpdb->prefix . "convector";

    $sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        convector_amount FLOAT DEFAULT 0 NOT NULL,
        convector_currency_from VARCHAR (5) DEFAULT '' NOT NULL,
        convector_currency_to VARCHAR (5) DEFAULT '' NOT NULL,
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    )";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
