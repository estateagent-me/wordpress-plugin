jQuery(document).ready(function() {
    
    jQuery('#lightSlider').lightSlider({
        gallery: true,
        item: 1,
        loop: true,
        slideMargin: 0,
        thumbItem: 6
    });

    jQuery(document).on("click", '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        jQuery(this).ekkoLightbox();
    });

});

