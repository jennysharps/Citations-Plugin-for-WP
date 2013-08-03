jQuery(document).ready( function($) {

    var citationSelect = $('#citation_type'),
        citationDataWrapper = $('#citation_data'),
        postId = $('input[name=post_ID]').val(),
        addItemButton = $('a.button.add_item'),
        removeItemButton = $('a.remove_item');

    citationSelect.change( function() {
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
                citationSelect.after('<div class="ajax-loader"></div>');
            },
            success: function(response){
                var respObj = $.parseJSON(response);

                if( respObj.markup.length > 0 ) {
                    citationDataWrapper.html( respObj.markup );
                } else {
                    citationDataWrapper.html('No fields retrieved.');
                }
            },
            complete: function(){
                $('.ajax-loader').remove();
            },
            error: function(response) {
                alert('An error occured while trying to retrieve citation fields.');
            }
        })

    });

    addItemButton.live( 'click', function() {
        var clickedButton = $(this),
            itemGroup = clickedButton.prev(),
            fieldId = itemGroup.data('fieldid'),
            itemNumber = itemGroup.parent().find('div').last().data('itemnumber');


        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                "action":       "get_repeater_field",
                'field_id' :    fieldId,
                'item_number':  itemNumber + 1,
                'post_id':      postId
            },
            beforeSend: function(){
                citationSelect.after('<div class="ajax-loader"></div>');
            },
            success: function(response){
                var respObj = $.parseJSON(response);

                if( respObj.markup.length > 0 ) {
                    $(respObj.markup).insertBefore(clickedButton);
                }
            },
            complete: function(){
                $('.ajax-loader').remove();
            },
            error: function(response) {
                alert('An error occured while trying to add an item.');
            }
        })

    });

    removeItemButton.live( 'click', function(){
        $(this).parent().remove();
    });

    var electronicRefSelect = $('#citation\\[select_electronic_ref_type\\]'),
        electronicRefTextFields = $('#electronic_ref_type_wrap .field_wrap');

    electronicRefSelect.change( function() {
        var selectedType = $(this).val();

        electronicRefTextFields.addClass('hidden').parent().find('.field_wrap.' + selectedType + '_field').removeClass('hidden');

    });

});