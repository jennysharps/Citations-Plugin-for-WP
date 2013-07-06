jQuery(document).ready( function($) {

    var citationInput = $('#citation_type'),
        citationDataWrapper = $('#citation_data'),
        postId = $('input[name=post_ID]').val();

    citationInput.change( function() {
        var selectedType = $(this).val();

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                "action":       "get_citation_fields",
                'chosen_type' : selectedType,
                'post_id' :     postId
            },
            beforeSend: function(){
                citationInput.after('<div class="ajax-loader"></div>');
            },
            success: function(response){
                var respObj = $.parseJSON(response);

                if( respObj.markup.length > 0 ) {
                    citationDataWrapper.html( respObj.markup );
                } else {
                    citationDataWrapper.html('No fields retrieved.');
                }
                $('.ajax-loader').remove();

            },
            error: function(response) {
                alert('An error occured while trying to upload your image asynchronously.');
            }
        })

    });

});