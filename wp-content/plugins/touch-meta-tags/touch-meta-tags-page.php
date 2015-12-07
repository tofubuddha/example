<?php
/** WordPress Administration Bootstrap */
$pathinfo = pathinfo(dirname(__FILE__));
$base_path = realpath($pathinfo["dirname"] . "/../../");
$admin_file = $base_path . "/wp-admin/admin.php";
$admin_active = in_array($admin_file, get_included_files());

if (!$admin_active) {
    echo 'You do not have sufficient permissions to access this page (Loggin Required).<br/> This file has to be accessed via the backend';
} else {
// FOUNDATION STYLES AND JS
    wp_enqueue_style('foundation_styles', plugin_dir_url(__FILE__) . 'foundation/css/foundation.css');
    wp_enqueue_style('normalize_styles', plugin_dir_url(__FILE__) . 'foundation/css/normalize.css');
    wp_enqueue_style('font_awesome', "//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css");
    wp_enqueue_script('foundation_js', plugin_dir_url(__FILE__) . 'foundation/js/foundation.min.js', array(), null, true);
    wp_enqueue_script('modernizr_js', plugin_dir_url(__FILE__) . 'foundation/js/vendor/modernizr.js', array(), null, true);

// TOUCH SEO STYLES AND JS
    wp_enqueue_style('touch_meta_tags_styles', plugin_dir_url(__FILE__) . 'touch-meta-tags.css');
    ?>


    <div class="wrap" id="touch-meta-tags-page">
        <h2>Touch Easy SEO</h2>
        <hr/>
        <h2>Review all</h2>
        <dl class="tabs" data-tab>
            <dd class="active"><a href="#panel1">Pages</a></dd>
            <dd><a href="#panel2">Posts</a></dd>
        </dl>
        <div class="tabs-content">
            <div class="content active" id="panel1">
                <div style="overflow: auto;">
                    <?php get_seotable_from_post_type(get_pages()); ?>
                </div>
            </div>
            <div class="content" id="panel2" style="overflow: auto;">
                <div style="overflow: auto;">
                    <?php get_seotable_from_post_type(get_posts()); ?>
                </div>
            </div>
        </div>
    </div>


    <script>
        jQuery(document).ready(function () {
            jQuery(document).foundation();
        });
    </script>


    <?php
}

function get_seotable_from_post_type($post_type) {
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo '<th style="width: 50px;">ID</th>';
    echo '<th style="width: 200px;">Post Title</th>';
    echo '<th style="width: 200px;">Custom Title</th>';
    echo '<th style="width: 300px;">Description</th>';
    echo '<th style="width: 200px;">Keywords</th>';
    echo '<th style="width: 200px;">Facebook title</th>';
    echo '<th style="width: 200px;">Facebook type</th>';
    echo '<th style="width: 200px;">Facebook image</th>';
    echo '<th style="width: 300px;">Facebook description</th>';
    echo '<th style="width: 300px;">Other Meta Tags</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($post_type as $single_post) {
        // Get the values for the post/page
        $values = get_post_custom($single_post->ID);

        // This values are the same than in touch-meta-tags-metaboxes.php
        $title = isset($values['touch_seo_title']) ? esc_attr($values['touch_seo_title'][0]) : "";
        $meta_description = isset($values['touch_seo_meta_description']) ? esc_attr($values['touch_seo_meta_description'][0]) : "";
        $meta_keywords_string = isset($values['touch_seo_meta_keywords']) ? esc_attr($values['touch_seo_meta_keywords'][0]) : "";
        $facebook_title = isset($values['touch_seo_facebook_title']) ? esc_attr($values['touch_seo_facebook_title'][0]) : "";
        $facebook_type = isset($values['touch_seo_facebook_type']) ? esc_attr($values['touch_seo_facebook_type'][0]) : "";
        $facebook_image = get_post_meta($single_post->ID, 'touch_seo_facebook_image', true);
        $facebook_description = isset($values['touch_seo_facebook_description']) ? esc_attr($values['touch_seo_facebook_description'][0]) : "";
        $other_meta_tags = isset($values['touch_seo_other_meta_tags']) ? esc_attr($values['touch_seo_other_meta_tags'][0]) : "";
        
        // Modification of some param for usability.
        $meta_keywords_tab = ($meta_keywords_string != "") ? explode(",", $meta_keywords_string) : array();
        $facebook_image_url = (is_array($facebook_image) && strlen($facebook_image["url"]) > 0) ? $facebook_image["url"] : null;
        // function trim_value is defined in touch-meta-tags.php
        array_walk($meta_keywords_tab, 'trim_value');
        echo '<tr>';
        echo "<td>" . $single_post->ID . "</td>";
        echo "<td>" . $single_post->post_title . "</td>";
        echo "<td>" . $title . "</td>";
        echo "<td>" . $meta_description . "</td>";
        echo "<td>";
        foreach ($meta_keywords_tab as $key => $one_keyword) {
            echo ($key + 1) . " - $one_keyword<br/>";
        }
        echo "</td>";
        echo "<td>" . $facebook_title . "</td>";
        echo "<td>" . ucfirst($facebook_type) . "</td>";
        echo "<td>";
        if (!is_null($facebook_image_url)){
           echo '<img alt="facebook image" src="'.$facebook_image_url.'" />';
        } elseif (has_post_thumbnail( $single_post->ID )) {
           $facebook_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $single_post->ID ), 'single-post-thumbnail' );
           echo '<img alt="facebook image" src="'.$facebook_image_url[0].'" />';
        } else {
            echo "";
        } 
        echo "</td>";
        echo "<td>" . $facebook_description . "</td>";
        echo "<td>" . $other_meta_tags . "</td>";
        echo '</tr>';
    }
    echo "</tbody>";
    echo "</table>";
}
