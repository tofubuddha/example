<?php
/**
 * Template Name: Template to display the Slider
 */

if (is_admin()) { ?>
        <link rel="stylesheet" href="<?php bloginfo('template_url') ?>/css/app.css" />
        <script src="<?php bloginfo('template_url') ?>/bower_components/modernizr/modernizr.js"></script>
            <style>
                body {
                    color: #444;
                    font-family: "Open Sans",sans-serif;
                    font-size: 13px;
                    line-height: 1.4em;
                    min-width: 600px;
                }
                
                #adminmenuwrap {
                    min-height: 100%;
                }
                
                #adminmenuwrap ul {
                    margin-left: 0;
                }
                
                #adminmenuwrap ul li ul, 
                #adminmenuwrap ul li ol {
                    margin-left: 0;
                }
        </style>
        <div class="wrap">
            <h1>This is your main slider</h1>
        <p>Call it with shortcode [slider] anywhere on the site</p>
            <div class="row">
                <div class="large-12 columns">
<?php }


if (have_rows('slider_slides', "options") ){ ?>
        
                    <ul data-orbit data-options="animation:slide; pause_on_hover:true; animation_speed:500; navigation_arrows:true; bullets:false; slide_number: false;">
                        <?php while (have_rows('slider_slides', "options")): the_row();
                            // vars
                            $image = get_sub_field('slider_slide_image');
                            $title = get_sub_field('slider_slide_title');
                            ?>
                            <li>
                                <div class="slider-img-wrap" style="background-image: url(<?php echo $image['url']; ?>);background-position: center center; background-repeat: no-repeat;background-size: cover; height: 600px;">
                                    &nbsp;
                                </div>
                                <div class="orbit-caption">
                                    <?php echo $title ?>
                                </div>
                            </li>
                            
                        <?php endwhile; ?>

                    </ul>

<?php } ?>

<?php if (is_admin()) { ?>
                </div>
            </div>
        </div>
    <script src="<?php bloginfo('template_url') ?>/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php bloginfo('template_url') ?>/bower_components/foundation/js/foundation.min.js"></script>
    <script src="<?php bloginfo('template_url') ?>/js/app.js"></script>
<?php } ?>