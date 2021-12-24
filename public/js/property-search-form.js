var propertyTypesParents = jQuery( '#ea-search-type-parent' ),
    propertyTypesChildren = jQuery( '#ea-search-type-children' ),
    propertyTypesChildrenOpts = propertyTypesChildren.find( 'option' );
    
propertyTypesParents.on('change', function() {
    propertyTypesChildren.html(
        propertyTypesChildrenOpts.filter( '[data-parent="' + jQuery(this).find(":selected").data('parent') + '"]' )
    );

    if (propertyTypesChildren.find('option[selected]').val() !== undefined) {
        propertyTypesChildren.find('option[selected]').attr('selected','selected');
    } else {
        propertyTypesChildren.find('option[data-all]').attr('selected','selected');
    }

    if (propertyTypesParents.val()) {
        jQuery('#ea-search-type-children-col').show();
    } else {
        jQuery('#ea-search-type-children-col').hide();
    }
    

}).trigger( 'change' );

jQuery('a#map-view').on('shown.bs.tab', function (e) {
    initMap();
});

// Pre-select default search selection from options, if set
jQuery(document).ready(function() {
    if (typeof EA_DEFAULT_SEARCH_SELECTION !== undefined) {
        jQuery('div[data-search="' + EA_DEFAULT_SEARCH_SELECTION + '"]').click();
    }
});