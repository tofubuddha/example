<?php

/**
 * Plugin Name: Touch Marketing - Add the touchmk widget area
 * Plugin URI: http://www.touchmarketing.co.nz/
 * Description: A Plugin which add a widget area.
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
add_action('admin_menu', 'touchmk_register_my_widget_area_in_menu');

function touchmk_register_my_widget_area_in_menu() {
    add_menu_page('My Widget Area Configuration', 'Widget Area', 'edit_others_posts', 'acf-options-widget-area', 'touchmk_widget_area_function', 'dashicons-welcome-widgets-menus', 45);
}

if (function_exists('acf_add_options_sub_page')) {
    acf_add_options_sub_page(array(
        'title' => 'Widget Area',
        'parent' => 'touchmk_widget_area',
        'capability' => 'edit_others_posts'
    ));
}

if (function_exists("register_field_group")) {
    register_field_group(array(
        'id' => 'acf_widget-area-config',
        'title' => 'Widget Area Config',
        'fields' => array(
            array(
                'key' => 'field_53b5e01e3620b',
                'label' => 'Widgets',
                'name' => 'widget_area_widgets',
                'type' => 'repeater',
                'sub_fields' => array(
                    array(
                        'key' => 'field_53b5e0453620c',
                        'label' => 'Widget',
                        'name' => 'widget_area_widget',
                        'type' => 'wysiwyg',
                        'required' => 1,
                        'column_width' => '',
                        'default_value' => '',
                        'placeholder' => '',
                        'prepend' => '',
                        'append' => '',
                        'formatting' => 'html',
                        'maxlength' => '',
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
                    'value' => 'acf-options-widget-area',
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

function touchmk_widget_area_function(){
     if (!current_user_can('edit_others_posts')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    include('touchmk-widget-area-page.php');
}