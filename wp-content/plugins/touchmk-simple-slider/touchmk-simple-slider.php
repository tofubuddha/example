<?php

/**
 * Plugin Name: Touch Marketing - Add Simple Slider
 * Plugin URI: http://www.touchmarketing.co.nz/
 * Description: A Plugin which add a simple slider.
 * Version: 1.0
 * Author: Touch Marketing
 * Author URI: www.touchmarketing.co.nz/
 * License: All rights reserved
 */


$acf_plugin = plugin_dir_path(__FILE__) . "../advanced-custom-fields/acf.php";
include_once($acf_plugin);
$acf_plugin_option_page = plugin_dir_path(__FILE__) . "../acf-options-page/acf-options-page.php";
include_once($acf_plugin_option_page);


//add page & render it visible to editors
add_action('admin_menu', 'touchmk_register_my_slider_page_in_menu');

function touchmk_register_my_slider_page_in_menu() {
    add_menu_page('My Slider', 'Slider (Basic)', 'edit_others_posts', 'touchmk_simple_slider', 'touchmk_simple_slider_function', 'dashicons-images-alt2', 21);
}

function touchmk_simple_slider_function() {
    if (!current_user_can('edit_others_posts')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    include('touchmk-simple-slider-render.php');
}

add_shortcode( 'slider', 'render_the_slider_shortcode' );
function render_the_slider_shortcode(){
    ob_start();
    include('touchmk-simple-slider-render.php');
    return ob_get_clean();
}

if (function_exists('acf_add_options_sub_page')) {
    acf_add_options_sub_page(array(
        'title' => 'Slider config',
        'parent' => 'touchmk_simple_slider',
        'capability' => 'edit_others_posts'
    ));
}

if (function_exists("register_field_group")) {
    register_field_group(array(
        'id' => 'acf_slider-config',
        'title' => 'Slider - Config',
        'fields' => array(
            array(
                'key' => 'field_53b4901030931',
                'label' => 'Slides',
                'name' => 'slider_slides',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_53b4a085ed515',
                        'label' => 'Slide title',
                        'name' => 'slider_slide_title',
                        'type' => 'text',
                        'column_width' => '',
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'formatting' => 'html',
                        'maxlength' => '',
                    ),
                    array(
                        'key' => 'field_53b4a0a6ed516',
                        'label' => 'Slide image',
                        'name' => 'slider_slide_image',
                        'type' => 'image',
                        'required' => 1,
                        'column_width' => '',
                        'save_format' => 'object',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                    ),
                ),
                'row_min' => '',
                'row_limit' => '',
                'layout' => 'table',
                'button_label' => 'Add Row',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options-slider-config',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
        ),
        'options' => array(
            'position' => 'normal',
            'layout' => 'no_box',
            'hide_on_screen' => array(
            ),
        ),
        'menu_order' => 0,
    ));
}
