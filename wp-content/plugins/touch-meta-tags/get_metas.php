
<!-- ************************************ SEO META TAGS ************************************ -->

<?php
global $post;
$post_custom = get_post_custom($post->ID);
// -----------------  START commons meta tags ------------------->
$description = (isset($post_custom['touch_seo_meta_description'])) ? $post_custom['touch_seo_meta_description'][0] : "";
if ($description != '' && $description != null) {
    echo "<meta name=\"description\" content=\"$description\" />" . "\n";
}

// Start $keywords Formatting 
$keywords_formatting = (isset($post_custom['touch_seo_meta_keywords'])) ? $post_custom['touch_seo_meta_keywords'][0] : '';
$meta_keywords_tab = ($keywords_formatting != "") ? explode(",", $keywords_formatting) : array();
array_walk($meta_keywords_tab, 'trim_value');
$keywords_formatted = "";
foreach ($meta_keywords_tab as $one_meta_keyword) {
    $keywords_formatted .= $one_meta_keyword . ", ";
}
// End $keywords Formatting 
if ($keywords_formatted != "") {
    $keywords = substr($keywords_formatted, 0, -2); // Delete the last ", "
    echo "<meta name=\"keywords\" content=\"$keywords\" />" . "\n";
}
?>
<?php // ----------------- END commons meta tags ----------------->  ?>
<?php // ----------------- START G+ & pinterest verification+linking ----------------->  ?>
<?php
$google_business_page_url = get_option('touch_meta_tags_google_business_page');
$google_plus_verification_code = get_option('touch_meta_tags_google_verification_code');
$pinterest_verification_code = get_option('touch_meta_tags_pinterest_verification_code');

if ($google_business_page_url != '' && $google_business_page_url != null) {
    echo "<link href=\"$google_business_page_url\" rel=\"publisher\"/>" . "\n";
}
if ($google_plus_verification_code != '' && $google_plus_verification_code != null) {
    echo "$google_plus_verification_code" . "\n";
}
if ($pinterest_verification_code != '' && $pinterest_verification_code != null) {
    echo "$pinterest_verification_code" . "\n";
}
?>
<?php // ----------------- END G+ & pinterest verification+linking ----------------->  ?>
<?php
// ----------------- Start Facebook metas ----------------->
$facebook_og_title = (isset($post_custom['touch_seo_facebook_title'])) ? $post_custom['touch_seo_facebook_title'][0] : "";
if ($facebook_og_title != '' && $facebook_og_title != null) {
    echo "<meta property=\"og:title\" content=\"$facebook_og_title\" />" . "\n";
}

$facebook_og_type = (isset($post_custom['touch_seo_facebook_type'])) ? $post_custom['touch_seo_facebook_type'][0] : "";
if ($facebook_og_type != '' && $facebook_og_type != null) {
    echo "<meta property=\"og:type\" content=\"$facebook_og_type\" />" . "\n";
}

$facebook_og_description = (isset($post_custom['touch_seo_facebook_description'])) ? $post_custom['touch_seo_facebook_description'][0] : "";
if ($facebook_og_description != '' && $facebook_og_description != null) {
    echo "<meta property=\"og:description\" content=\"$facebook_og_description\" />" . "\n";
}

$facebook_image_object = get_post_meta($post->ID, 'touch_seo_facebook_image', true);
$facebook_image_url = (is_array($facebook_image_object) && strlen($facebook_image_object["url"]) > 0) ? $facebook_image_object["url"] : null;
if ($facebook_image_url != '' && $facebook_image_url != null) {
    echo "<meta property=\"og:image\" content=\"$facebook_image_url\" />" . "\n";
} elseif (has_post_thumbnail( $post->ID )) {
   $facebook_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
   echo "<meta property=\"og:image\" content=\"{$facebook_image_url[0]}\" />" . "\n";
}

// ----------------- End Facebook metas -----------------> 

// -----------------  Other meta tags -----------------> 
$other_meta_tags = (isset($post_custom['touch_seo_other_meta_tags'])) ? $post_custom['touch_seo_other_meta_tags'][0] : "";
if ($other_meta_tags != '' && $other_meta_tags != null) {
    echo html_entity_decode($other_meta_tags) . "\n";
}
// -----------------  End Other meta tags -----------------> 
?>

<!-- ************************************ END SEO META TAGS ************************************ -->

