var j = jQuery.noConflict();

j(document).ready(function (s) {
    var sortlist = j('ul#custom-type-list');
    var animation = j('#loading-animation');
    var pageTitle = j('div h2');
    sortlist.sortable({
        update: function (event, ui) {
            animation.show();

            j.ajax({
                url: ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'save_sort',
                    order: sortlist.sortable('toArray'),
					security: WP_JOB_LISTING.security
                },
                success: function ( response ) {
                	j('#message').remove();
                    animation.hide();
                    if(true == response.success){
                        pageTitle.after( '<div id="message" class="updated"><p>'+ WP_JOB_LISTING.success + '</p></div>' );
					}else{
                        pageTitle.after( '<div id="message" class="error"><p>'+ WP_JOB_LISTING.failure + '</p></div>' );
					}

                },
                error: function( error ) {
                    j('#message').remove();
                    animation.hide();
                    pageTitle.after( '<div id="message" class="error"><p>'+ WP_JOB_LISTING.failure + '</p></div>' );
                }
            });
        }
    });
});