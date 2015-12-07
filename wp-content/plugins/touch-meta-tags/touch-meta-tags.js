
jQuery(document).ready(function() {


    if (jQuery("#touch_seo_title_block").length) {
        count(60, jQuery("#touch_seo_title_block"));
        jQuery("#touch_seo_title_block").keyup(function() {
            count(60, jQuery(this));
        });
    }
      
    if (jQuery("#touch_seo_meta_description_block").length) {
        count(160, jQuery("#touch_seo_meta_description_block"));
        jQuery("#touch_seo_meta_description_block").keyup(function() {
            count(160, jQuery(this));
        });
    }
    
    if(jQuery("#touch_seo_delete_facebbok_image_link").length){
        jQuery("#touch_seo_delete_facebbok_image_link").click(function(event){
           event.preventDefault(); 
           if(jQuery("#touch_seo_facebook_image_block .image_block").length){
               jQuery("#touch_seo_facebook_image_block .image_block").addClass("hide");
           }
           if(jQuery("#touch_seo_facebook_image").length){
               jQuery("#touch_seo_facebook_image").removeClass("hide");
           }
           if(jQuery("#touch_seo_facebook_image_url").length){
               jQuery("#touch_seo_facebook_image_url").val("");
           }
        });
    }

    function count($limit, object) {
        var $limit = $limit;
        //Récupérer le nombre de caractères dans la zone de texte
        var currlength = object.find("input, textarea").val().length;
        //Afficher un texte de légende en fonction du nombre de caractères
        if (currlength !== 0 & currlength <= $limit) {
            var $span = "<span class='info-characters'>" + currlength + " characters" + "</span>";
            object.find(".info-characters").remove();
            object.find("label").after($span);
            object.find("input, textarea").css({'background-color': '#fff'});
        } else if (currlength !== 0) {
            var $span = "<span class='info-characters'>" + currlength + " characters" + "</span>";
            object.find(".info-characters").remove();
            object.find("label").after($span);
            object.find("input, textarea").css({'background-color': 'pink'});
        } else {
            object.find(".info-characters").remove();
        }
    }
});
