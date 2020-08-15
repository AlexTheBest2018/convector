<?php
/*
Plugin Name: Currency exchange
Plugin URI: http://wordpress.org/plugins/convector/
Description: Cryptocurrencies exchange.
Author: Alex
Version: 1.0
*/
/**
 * @return mixed
 */


include_once('convector_db.php');
include_once('convector_config.php');

register_activation_hook( __FILE__, 'create_convector_db' );

function convector_get_currency_from_api(){
    $result = wp_remote_get(URL_API.'/listings/latest?start=1&limit=5000&convert=BTC', array( 'timeout' => 10,
        'headers' => array( 'Accepts' => 'application/json',
            'X-CMC_PRO_API_KEY'=> API_KEY )
    ));
    $result = json_decode($result['body']);
    return $result;
}

function convector_get_rate_from_api($currency_from, $currency_to){
    $result = wp_remote_get(URL_API.'/quotes/latest?symbol='.$currency_from.'&convert='.$currency_to, array( 'timeout' => 10,
        'headers' => array( 'Accepts' => 'application/json',
            'X-CMC_PRO_API_KEY'=> API_KEY )
    ));
    $result = json_decode($result['body']);
    return $result;
}

function convector_set_options($result){
    $text = "";
    foreach($result->data as $data)
    {
        $text .= '<option value="'.$data->symbol.'">'.$data->symbol.'</option>';
    }
    return $text;
}

function convector_form_exchange_currency(){
    $currency = convector_get_currency_from_api();
    $select_currency = convector_set_options($currency);
    include ('convector_form.php');
}

function convertor_get_data_from_db(){
    global $wpdb;
    return $wpdb->get_results( "SELECT * FROM wp_convector ORDER BY created_at DESC LIMIT 0, 10");
}

add_action('wp_ajax_get_exchanges', 'convector_get_exchanges');
add_action('wp_ajax_nopriv_get_exchanges', 'convector_get_exchanges');

function convector_get_exchanges(){
    $result = convertor_get_data_from_db();
    convector_table_last_exchanges($result);
    wp_die();
}

function convector_table_last_exchanges($result = []){
    $result = convertor_get_data_from_db();
    include ('convector_exchanges_table.php');
}


add_action('wp_ajax_convector', 'convector_submit_form');
add_action('wp_ajax_nopriv_convector', 'convector_submit_form');

function convector_submit_form(){
    $value = floatval($_GET['amount']);
    $currency_from = $_GET['select_currency_from'];
    $currency_to = $_GET['select_currency_to'];
    $currency_api = convector_get_rate_from_api($currency_from, $currency_to);
    $rate = $currency_api->data->$currency_from->quote->$currency_to->price;
    convector_set_data_to_db($value, $currency_from, $currency_to);
    $result = $value*$rate;
    echo $result;
    wp_die();
}

function convector_set_data_to_db($value, $currency_from, $currency_to) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'convector';

    $wpdb->insert(
        $table_name,
        array(
            'created_at' => current_time( 'mysql' ),
            'convector_amount' => $value,
            'convector_currency_from' => $currency_from,
            'convector_currency_to' => $currency_to,
        )
    );
}

add_action( 'wp_enqueue_scripts', 'script_assets' );
function script_assets(){
    wp_enqueue_script('custom', plugins_url('script.js', __FILE__), array('jquery'));
    wp_localize_script('custom', 'myPlugin', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}

wp_enqueue_style('custom', plugins_url('style.css', __FILE__));
add_shortcode('test', 'convector_form_exchange_currency');
