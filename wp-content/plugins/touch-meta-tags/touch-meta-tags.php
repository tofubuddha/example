<?php

/**
 * Plugin Name: Touch Marketing - Add Meta tags
 * Plugin URI: http://www.touchmarketing.co.nz/
 * Description: A Plugin which add some SEO's functionnalities...and a review interface
 * Version: 2.0
 * Author: Touch Marketing
 * Author URI: www.touchmarketing.co.nz/
 * License: All rights reserved
 */
try {
    $acf_plugin = plugin_dir_path(__FILE__) . "../advanced-custom-fields/acf.php";
    $acf_plugin_option_page = plugin_dir_path(__FILE__) . "../acf-options-page/acf-options-page.php";
    if (file_exists($acf_plugin) && file_exists($acf_plugin_option_page)) {
        require_once($acf_plugin);
        require_once($acf_plugin_option_page);
    } else {
        throw new Exception('You have to install Advanced Custom Field plugin first. (ACF plugin & ACF Option Page Plugin)');
    }
} catch (Exception $ex) {
    wp_die($ex->xdebug_message);
}

add_action("admin_menu", "touch_easy_seo_menu_init");

function touch_easy_seo_menu_init() {
    add_menu_page("Touch Easy SEO", "Touch Easy SEO", "read_touch-easy-seo", "touch_easy_seo", "touch_easy_seo_page", "dashicons-welcome-view-site", 97);

    setup_touch_easy_seo_settings_page();

    setup_read_touch_easy_seo_caps();
}

function touch_easy_seo_page() {
    require_once("touch-meta-tags-page.php");
}

add_action("admin_enqueue_scripts", "touch_meta_tags_enqueue_scripts");

function touch_meta_tags_enqueue_scripts() {
    if (is_admin()) {
        wp_enqueue_script('touch_meta_tags', plugin_dir_url(__FILE__) . 'touch-meta-tags.js');
    }
}

function setup_touch_easy_seo_settings_page() {
    if (function_exists('acf_add_options_sub_page')) {
        acf_add_options_sub_page(array(
            'title' => 'Settings',
            'parent' => 'touch_easy_seo',
            'slug' => "touch-easy-seo-settings",
            'capability' => 'read_touch-easy-seo-settings',
            'menu' => 'Touch Easy SEO Settings'
        ));
    }

    if (function_exists("register_field_group")) {
        $roles = get_editable_roles();
        // Do not allow rights modification of administrator and current role
        if (isset($roles["administrator"])): unset($roles["administrator"]);
        endif;

        $field_value = array();
        foreach ($roles as $role_name => $role_info) {
            $field_value[$role_name] = ucfirst($role_name);
        }

        register_field_group(array(
            'id' => 'acf_touch-easy-seo-settings',
            'title' => 'Touch Easy SEO Settings',
            'fields' => array(
                array(
                    'key' => 'field_5409423015b00',
                    'label' => 'Select who is able to edit the meta tags',
                    'name' => 'allowed_roles_for_touch_seo',
                    'type' => 'checkbox',
                    'instructions' => 'Admins are always allowed, you can not remove their rights.',
                    'choices' => $field_value,
                    'default_value' => '',
                    'layout' => 'horizontal',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'touch-easy-seo-settings',
                        'order_no' => 0,
                        'group_no' => 0,
                    ),
                ),
            ),
            'options' => array(
                'position' => 'normal',
                'layout' => 'default',
                'hide_on_screen' => array(
                ),
            ),
            'menu_order' => 0,
        ));
    }
}

function setup_read_touch_easy_seo_caps() {
    $roles = touch_easy_seo_touch_get_lower_roles();
    $allowed_roles = get_field('allowed_roles_for_touch_seo', 'options');

    // Do not allow rights modification of administrator and current role
    if (isset($roles["administrator"])): unset($roles["administrator"]);
    endif;

    // ALWAYS ALLOW ADMINISTRATORS TO VIEW THE PLUGIN
    $adminRole = get_role("administrator");
    if (current_user_can("administrator") && !current_user_can("read_touch-easy-seo")) {
        $adminRole->add_cap("read_touch-easy-seo");
    }
    if (current_user_can("administrator") && !current_user_can("read_touch-easy-seo-settings")) {
        $adminRole->add_cap("read_touch-easy-seo-settings");
    }

    foreach ($roles as $role_name => $role_info) {
        $role = get_role($role_name);
        if ($allowed_roles != '' && in_array($role_name, $allowed_roles) && !$role->has_cap("read_touch-easy-seo")) {
            $role->add_cap("read_touch-easy-seo");
        } elseif ($allowed_roles != '' && $role->has_cap("read_touch-easy-seo") && !in_array($role_name, $allowed_roles)) {
            $role->remove_cap("read_touch-easy-seo");
        } elseif ($allowed_roles == '' && $role->has_cap("read_touch-easy-seo")) {
            $role->remove_cap("read_touch-easy-seo");
        }
    }
}

add_action('add_meta_boxes', 'touch_meta_tags__metabox_add');

function touch_meta_tags__metabox_add() {
    add_meta_box("touch_meta_tags_metabox", "Easy SEO - by Touch Marketing", "touch_meta_tags_metabox", "page", "normal", "low");
    add_meta_box("touch_meta_tags_metabox", "Easy SEO - by Touch Marketing", "touch_meta_tags_metabox", "post", "normal", "low");
}

function touch_meta_tags_metabox($post) {
    require_once("touch-meta-tags-metaboxes.php");
}

add_action("save_post", "touch_meta_tags_metabox_save");

function touch_meta_tags_metabox_save($post_id) {
    /* --- security verification --- */
    // Bail if we're doing an auto save
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // if our nonce isn't there, or we can't verify it, bail
    if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'my_meta_box_nonce')) {
        return $post_id;
    }
    // if our current user can't edit this post, bail
    if (!current_user_can('read_touch-easy-seo')) {
        return $post_id;
    }
    /* --- end security verification --- */

    /*
     * 
     * --- SAVING THE DATA --- 
     * 
     * 
     */
    if (isset($_POST['touch_seo_title'])) {
        update_post_meta($post_id, 'touch_seo_title', esc_attr($_POST['touch_seo_title']));
    }
    if (isset($_POST['touch_seo_meta_description'])) {
        update_post_meta($post_id, 'touch_seo_meta_description', esc_attr($_POST['touch_seo_meta_description']));
    }
    if (isset($_POST['touch_seo_meta_keywords'])) {
        update_post_meta($post_id, 'touch_seo_meta_keywords', esc_attr($_POST['touch_seo_meta_keywords']));
    }
    if (isset($_POST['touch_seo_facebook_title'])) {
        update_post_meta($post_id, 'touch_seo_facebook_title', esc_attr($_POST['touch_seo_facebook_title']));
    }
    if (isset($_POST['touch_seo_facebook_type'])) {
        update_post_meta($post_id, 'touch_seo_facebook_type', esc_attr($_POST['touch_seo_facebook_type']));
    }
    if (isset($_POST['touch_seo_facebook_description'])) {
        update_post_meta($post_id, 'touch_seo_facebook_description', esc_attr($_POST['touch_seo_facebook_description']));
    }
    if (isset($_POST['touch_seo_other_meta_tags'])) {
        update_post_meta($post_id, 'touch_seo_other_meta_tags', esc_attr($_POST['touch_seo_other_meta_tags']));
    }

    /*
     *  FACEBOOK IMAGE FIELD
     */
    // Grab a reference to the file associated with this post
    $image = get_post_meta($post_id, 'touch_seo_facebook_image', true);
    // Grab the value for the URL to the file stored in the text element
    $hidden_url_input = esc_attr($_POST['touch_seo_facebook_image_url']);

    // Determine if a file is associated with this post and if the delete flag has been set (by clearing out the input box)
    if (is_array($image) && strlen(trim($image['url'])) > 0 && strlen(trim($hidden_url_input)) == 0) {
        // Attempt to remove the file. If deleting it fails, print a WordPress error.
        if (unlink($image['file'])) {
            // Delete succeeded so reset the WordPress meta data
            update_post_meta($post_id, 'touch_seo_facebook_image', null);
            update_post_meta($post_id, 'touch_seo_facebook_image_url', '');
        } else {
            wp_die('There was an error trying to delete your file.');
        } // end if/el;se
    } // end if
    // Make sure the file array isn't empty
    if (!empty($_FILES['touch_seo_facebook_image']['name'])) {
        // Setup the array of supported file types. In this case, it's just PDF.
        $supported_types = array('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/x-png', 'image/png');
        // Get the file type of the upload
        $arr_file_info = wp_check_filetype(basename($_FILES['touch_seo_facebook_image']['name']));
        $uploaded_type = $arr_file_info['type'];
        // Check if the type is supported. If not, throw an error.
        if (in_array($uploaded_type, $supported_types)) {
            // Use the WordPress API to upload the file
            $upload = wp_upload_bits($_FILES['touch_seo_facebook_image']['name'], null, file_get_contents($_FILES['touch_seo_facebook_image']['tmp_name']));
            if (isset($upload['error']) && $upload['error'] != 0) {
                wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
            } else {
                add_post_meta($post_id, 'touch_seo_facebook_image', $upload);
                update_post_meta($post_id, 'touch_seo_facebook_image', $upload);
            } // end if/else
        } else {
            wp_die("The file type that you've uploaded is not supported (gif/jpeg/png allowed).");
        } // end if/else
    } // end if
    /*
     *  END FACEBOOK IMAGE FIELD
     */

    /*
     * 
     * --- END SAVING THE DATA --- 
     *      
     *       
     */
}

// change the form tag to allow files upload
function touch_meta_tags_update_edit_form() {
    echo ' enctype="multipart/form-data"';
}

add_action('post_edit_form_tag', 'touch_meta_tags_update_edit_form');


/*
 * 
 * BEGIN GENERAL SETTINGS PAGE
 * 
 */

// ------------------------------------------------------------------
// Add all sections, fields and settings during admin_init
// ------------------------------------------------------------------
//

function touch_meta_tags_settings_api_init() {
    // Add the section to reading settings so we can add our
    // fields to it
    add_settings_section(
            'touch_meta_tags_setting_section', 'Search Engine Optimisation', 'touch_meta_tags_setting_section_callback_function', 'general'
    );

    // Add the field with the names and function to use for our new
    // settings, put it in our new section
    add_settings_field(
            'touch_meta_tags_google_business_page', 'Google+ Business Page', 'touch_meta_tags_google_business_page_callback_function', 'general', 'touch_meta_tags_setting_section'
    );
    add_settings_field(
            'touch_meta_tags_google_verification_code', 'Google+ verification code', 'touch_meta_tags_google_verification_code_callback_function', 'general', 'touch_meta_tags_setting_section'
    );
    add_settings_field(
            'touch_meta_tags_pinterest_verification_code', 'Pinterest verification code', 'touch_meta_tags_pinterest_verification_code_callback_function', 'general', 'touch_meta_tags_setting_section'
    );

    // Register our setting so that $_POST handling is done for us and
    // our callback function just has to echo the <input>
    register_setting('general', 'touch_meta_tags_google_business_page');
    register_setting('general', 'touch_meta_tags_google_verification_code');
    register_setting('general', 'touch_meta_tags_pinterest_verification_code');
}

// eg_settings_api_init()

add_action('admin_init', 'touch_meta_tags_settings_api_init');

// ------------------------------------------------------------------
// Settings section callback function
// ------------------------------------------------------------------
//
// This function is needed if we added a new section. This function 
// will be run at the start of our section
//

function touch_meta_tags_setting_section_callback_function() {
    echo '<p>This section has been added by Touch Marketing SEO plugin</p>';
}

// ------------------------------------------------------------------
// Callback function for our example setting
// ------------------------------------------------------------------
//
// creates a checkbox true/false option. Other types are surely possible
//

function touch_meta_tags_google_business_page_callback_function() {
    echo '<input name="touch_meta_tags_google_business_page" id="touch_meta_tags_google_business_page" style="width:100%;" placeholder="https://plus.google.com/+YourPage" value="' . get_option('touch_meta_tags_google_business_page') . '" />';
    echo '<p class="description">Add the URL of you Google+ Business page. BE CAREFUL – it MUST be a "business page" not a "user page".<br/>'
    . 'After that you need to point your Google+ business page at your site to verify the rel=”publisher” mark up. <br/>'
    . 'This can be done easily by going to the Google+ page > Edit > About > Add Your Website. It’s important that you use the canonical version of your site, or the mark up won’t work. e.g. www.yoursite.com</p>';
}

function touch_meta_tags_google_verification_code_callback_function() {
    echo '<input name="touch_meta_tags_google_verification_code" id="touch_meta_tags_google_verification_code" style="width:100%;" placeholder="<meta name=&quot;google-site-verification&quot; content=&quot;ee40_TK5sn6MZn_tjKMZlxxhgWbcZUW8hjpQZVCbn3F&quot; />" value="' . get_option('touch_meta_tags_google_verification_code') . '" />';
    echo '<p class="description">Go to "www.google.com/webmasters/verification/home" and add your site URL. Then choose the "Alternate Method" called "HTML tags", copy the meta tag provided by Google and paste it here.<br />'
    . 'Do not forget to update this form, and then you have to click on the "verify" button in Google Webmaster Tools.';
}

function touch_meta_tags_pinterest_verification_code_callback_function() {
    echo '<input name="touch_meta_tags_pinterest_verification_code" id="touch_meta_tags_pinterest_verification_code" style="width:100%;" placeholder="<meta name=&quot;p:domain_verify&quot; content=&quot;0b851d94c0d75fd39ba7b38e71c2197b&quot;/>" value="' . get_option('touch_meta_tags_pinterest_verification_code') . '" />';
    echo '<p class="description">Click on "Verify Website" in the Settings panel of www.pinterest.com, then choose the "Verify with a meta tag" option.<br/>'
    . 'Copy the meta tag provided by Pinterest and paste it here. Do not forget to update this form, and then you have to verify this site on Pinterest.';
}

/*
 * 
 * END GENERAL SETTINGS PAGE
 * 
 */



/*
 * RENDER THE FIELDS IN <HEAD>
 */

add_action('wp_head', 'touch_meta_tags_render_the_metas_in_head');

function touch_meta_tags_render_the_metas_in_head() {
    global $post;
    require_once('get_metas.php');
}

// change the title if custom one defined.

add_filter('wp_title', 'touch_meta_tags_render_the_page_title_tag', 10, 2);

function touch_meta_tags_render_the_page_title_tag($title, $sep) {
    global $post;
    $post_custom = get_post_custom($post->ID);
    $title_to_return = (isset($post_custom['touch_seo_title']) && $post_custom['touch_seo_title'][0] != "") ? $post_custom['touch_seo_title'][0] . " $sep " : $title;
    return $title_to_return;
}


/*
 * END RENDER IN <HEAD>
 */




// ------ FUNCTIONS USEFUL FOR THIS PLUGIN -------
function touch_easy_seo_touch_get_lower_roles() {
    $all_roles = get_editable_roles();
    $user = wp_get_current_user();
    $next_level = 'level_' . ($user->user_level + 1);

    foreach ($all_roles as $name => $role) {
        if (isset($role['capabilities'][$next_level])) {
            unset($all_roles[$name]);
        }
    }

    return $all_roles;
}

function trim_value(&$value) {
    $value = trim($value);
}
