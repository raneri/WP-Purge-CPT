jQuery(document).ready(function(){
    jQuery('#post_type_selector').val('');
});

function raneri_cpt_load_cpt_details( post_type ){
    jQuery('#raneri-purge-cpt-spinner').show();
    jQuery('#summary_placeholder').html('');

    if( post_type!=''){
        jQuery.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                'action': 'raneri_purge_cpt_posts_summary',
                'post_type':   post_type
            },
            cache: false
        }).done( function(response) {
            jQuery('#summary_placeholder').html( response );
        }).fail( function(response){
            alert('ERROR: ' + response);
        }).always( function(){
            jQuery('#raneri-purge-cpt-spinner').hide();
        });
    }
    else{
        jQuery('#raneri-purge-cpt-spinner').hide();
    }
}

function raneri_cpt_purge(post_type, question){
    var choice = confirm(question);
    if(choice) raneri_cpt_dopurge( post_type );
}

function raneri_cpt_dopurge( post_type ){
    jQuery('#raneri-purge-cpt-spinner').show();
    jQuery('#btn_dopurge').hide();

    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            'action': 'raneri_purge_cpt_dopurge',
            'post_type':   post_type
        },
        cache: false
    }).done( function(response) {
        jQuery('#summary_placeholder').html( response );
    }).fail( function(response){
        alert('ERROR: ' + response);
    }).always( function(){
        jQuery('#raneri-purge-cpt-spinner').hide();
        jQuery('#post_type_selector').val('');
    });
}