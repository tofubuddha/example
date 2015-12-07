<?php
/** WordPress Administration Bootstrap */
$pathinfo = pathinfo(dirname(__FILE__));
$base_path = realpath($pathinfo["dirname"] . "/../../");
$admin_file = $base_path . "/wp-admin/admin.php";
$admin_active = in_array($admin_file, get_included_files());

if (!$admin_active) {
    echo 'You do not have sufficient permissions to access this page (Loggin Required).<br/> This file has to be accessed via the backend';
} else {
    if (!isset($post)) {
        global $post;
    }
    // STYLES AND JS
    wp_enqueue_style('font_awesome', "//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css");
    wp_enqueue_style('touch_meta_tags_styles', plugin_dir_url(__FILE__) . 'touch-meta-tags.css');

    // We'll use this nonce field later on when saving.
    wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');

    $values = get_post_custom($post->ID);

    $title = isset($values['touch_seo_title']) ? esc_attr($values['touch_seo_title'][0]) : "";
    $meta_description = isset($values['touch_seo_meta_description']) ? esc_attr($values['touch_seo_meta_description'][0]) : "";
    $meta_keywords = isset($values['touch_seo_meta_keywords']) ? esc_attr($values['touch_seo_meta_keywords'][0]) : "";
    $facebook_title = isset($values['touch_seo_facebook_title']) ? esc_attr($values['touch_seo_facebook_title'][0]) : "";
    $facebook_type = isset($values['touch_seo_facebook_type']) ? esc_attr($values['touch_seo_facebook_type'][0]) : "";
    $facebook_image = get_post_meta($post->ID, 'touch_seo_facebook_image', true);
    $facebook_description = isset($values['touch_seo_facebook_description']) ? esc_attr($values['touch_seo_facebook_description'][0]) : "";
    $other_meta_tags = isset($values['touch_seo_other_meta_tags']) ? esc_attr($values['touch_seo_other_meta_tags'][0]) : "";
    
    ?>
    <div id="touch_seo_metabox">
        <p id="touch_seo_title_block">
            <label for="touch_seo_title">Title: </label>
            <input type="text" name="touch_seo_title" id="touch_seo_title" value="<?php echo $title; ?>" placeholder="e.g. My title for this page/post"/>
        </p>
        <p id="touch_seo_meta_description_block">
            <label for="touch_seo_meta_description">Meta Description: </label>
            <textarea name="touch_seo_meta_description" id="touch_seo_meta_description" placeholder="Add here a description about your page/post"><?php echo $meta_description; ?></textarea>
        </p>
        <p id="touch_seo_meta_keywords_block">
            <label for="touch_seo_meta_keywords">Keywords: </label>
            <span class="touch-seo-info">Around 5 keywords you would like to focus on, separated by commas.</span>
            <input type="text" name="touch_seo_meta_keywords" id="touch_seo_meta_keywords" value="<?php echo $meta_keywords; ?>" placeholder="e.g. My first keyword, Second one, First" />
        </p>
        <p id="touch_seo_facebook_title_block">
            <label for="touch_seo_facebook_title">Facebook OpenGraph title: </label>
            <span class="touch-seo-info">This is the title which will be display for your post/page when sharing it on a social network</span>
            <input type="text" name="touch_seo_facebook_title" id="touch_seo_facebook_title" value="<?php echo $facebook_title; ?>" placeholder="e.g. My title for social networks"/>
        </p>
        <p id="touch_seo_facebook_type_block">
            <label for="touch_seo_facebook_type">Facebook OpenGraph type: </label>
            <select name="touch_seo_facebook_type" id="touch_seo_facebook_type" >
                <option value="article" <?php selected($facebook_type, 'article'); ?>>Article</option>
                <option value="website" <?php selected($facebook_type, 'website'); ?>>Website</option>
            </select>
        </p>
        <p id="touch_seo_facebook_description_block">
            <label for="touch_seo_facebook_description">Facebook Description: </label>
            <textarea name="touch_seo_facebook_description" id="touch_seo_facebook_description" placeholder="Add here a description about your page/post to be share on social networks"><?php echo $facebook_description; ?></textarea>
        </p>
        <div id="touch_seo_facebook_image_block">
            <label for="touch_seo_facebook_image">Facebook OpenGraph image: </label>
            <span class="touch-seo-info">Upload here the image you would like to display when sharing this page/post.</span>
            <?php if (is_array($facebook_image) && strlen($facebook_image["url"]) > 0) { ?>
                <div class="image_block">
                    <img alt="Facebook OpenGraph Image" src="<?php echo $facebook_image["url"] ?>" />
                    <a href="#" title="Delete Facebook OpenGraph image" id="touch_seo_delete_facebbok_image_link">Delete this image</a>
                </div>
            <?php } ?>
            <input type="file" id="touch_seo_facebook_image" name="touch_seo_facebook_image" size="25" <?php echo (is_array($facebook_image) && strlen($facebook_image["url"]) > 0) ? ' class="hide"' : "" ?>/>
            <input type="hidden" id="touch_seo_facebook_image_url" name="touch_seo_facebook_image_url" value="<?php echo (is_array($facebook_image) && strlen($facebook_image["url"]) > 0) ? $facebook_image["url"] : ""; ?>" />
        </div>
        <p id="touch_seo_other_meta_tags_block">
            <label for="touch_seo_other_meta_tags">Other Meta tags: </label>
            <span class="touch-seo-info">
                Use this field to add the meta tags you need (or leave it empty).<br/>
                For example to you might have to add a link tag with a &quot;rel=canonical&quot; attribute to tell robots that this page/post is available on another URL. 
            </span>
            <textarea name="touch_seo_other_meta_tags" id="touch_seo_other_meta_tags" placeholder="e.g <link rel=&quot;canonical&quot; href=&quot;http://blog.example.com/post/my-farorite-url&quot; /><meta property=&quot;og:url&quot; content=&quot;http://blog.example.com/post/my-farorite-url&quot; />" rows="8"><?php echo $other_meta_tags; ?></textarea>
        </p>
    </div>
    <?php
}    