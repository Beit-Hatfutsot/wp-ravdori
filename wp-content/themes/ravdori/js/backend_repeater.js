/**
 * This file handles all the logic of the repeater fields (Dictionary & quotes)
 * in the story CPT
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

var $ = jQuery;


(function($) {

    /* Our main object defined in OOP style */
    var BH_BackendRepeater = function( element )
    {
        /**************/
        /* Properties */
        /*************/

        // Store the repeater DOM element, the plugin init on
        var repeater_element = $(element);

        // How many rows we have in the repeater
        var row_count	     = 0;

        // Used to distinguish between the different repeaters in the page
        // Currently can be "dictionary" or "quotes"
        var repeater_prefix = $(element).data("prefix");

        // Saving the repeater the user interacts with as a string for convenience
        var repeater_elemt_id = "#" + repeater_prefix + "-repeater ";


        // Create an object with no properties, which will function as a Set data type.
        // While the user changes some field, this field primary key will be saved to the set
        // (This is the reason for using the set - user can reedit the same field).
        // After that the set pk's will be written as an input array.
        var pk_to_update_set = Object.create(null);


        /************/
        /*  Methods */
        /************/


        // Assign event handlers

        // Click handler for the remove row button
        $( repeater_elemt_id + ' a.story-post-edit-remove-row' ).live( 'click' ,
        (
            function( e )
            {
                e.preventDefault();

                remove(  $(this).closest( "tr" )  );
                $("#pk-container").after("<input type='hidden' name=stories_" + repeater_prefix + "_pk_del[] value=" + $(this).data("storyid")  + ">");
            }
        ));


        // Click handler for the add row button
        $( repeater_elemt_id + 'a.story-post-edit-add-row' ).live( 'click' ,
        (
            function( e )
            {
                e.preventDefault();
                add();
            }
        ));

        // Change handler for inputs and textareas - used to know which fields needs to be updated
        $( repeater_elemt_id ).find( "input , textarea" ).change(function() {

            var postid = $(this).closest('tr.row').data("postid");
            var save_post_ids = $( repeater_elemt_id).hasClass("send-post-id");

            // If the value is different from the original value
            if ( $( this ).val() !=  $( this )[0].defaultValue ) {

                var story_id = $(this).data("storyid");

                if ( !( story_id in pk_to_update_set ) ) {
                    pk_to_update_set[ story_id ] = true;
                }

                var update_pk_inputs = '';
                for ( var pk in pk_to_update_set ) {
                    update_pk_inputs += "<input type='hidden' name=stories_" + repeater_prefix + "_pk_update[] value=" + ( save_post_ids ? postid + "," : "" ) + pk + ">";
                }

                $("#pk-update").html( update_pk_inputs );
            }

        });


   /**
    * Updates the repeater
    *
    * Currently only updating the rows nubmers
    *
   */
     var render = function() {

            // update row_count
            row_count = repeater_element.find('> table > tbody > tr.row').length;


            // update order numbers
            repeater_element.find('> table > tbody > tr.row').each(function(i){

                $(this).children('td.order').html( i+1 );

            });


            // empty?
            if( row_count == 0 )
            {
                repeater_element.addClass('empty');
            }
            else
            {
                repeater_element.removeClass('empty');
            }

        }; // render


    /**
     * Removes a given row ( HTML <tr> tag) from the repeater with fade animation
     *
     * @param {object} <tr> DOM object
     *
     * */
     var remove = function( $tr ){

        // animate out tr
        $tr.addClass('acf-remove-item');

        setTimeout( function()
        {

            $tr.remove();

            render();

        }, 400);

    };



    /**
     * Add a new row to the end of the repeater's table
     *
     * */
     var  add = function() {

        var new_row_markup  = '<tr class="row">';
        new_row_markup      +=   '<td class="order"></td>';


        if ( repeater_prefix == "dictionary" ) {

            new_row_markup += '<td>';
            new_row_markup += '<div class="inner">';
            new_row_markup += '<div class="acf-input-wrap">';
            new_row_markup += '<input name="new_fields_' + repeater_prefix + '_terms[]" type="text" class="text" value="" />';
            new_row_markup += '</div>';
            new_row_markup += '</div>';
            new_row_markup += '</td>'


            new_row_markup += '<td>';
            new_row_markup += '<div class="inner">';
            new_row_markup += '<div class="acf-input-wrap">'
            new_row_markup += '<textarea name="new_fields_' + repeater_prefix + '_values[]" class="textarea" rows="8"></textarea>';
            new_row_markup += '</div>';
            new_row_markup += '</div>';
            new_row_markup += '</td>';

        }
        else if ( repeater_prefix   == "quotes" ) {
            new_row_markup += '<td>';
            new_row_markup += '<div class="inner">';
            new_row_markup += '<div class="acf-input-wrap">';
            new_row_markup += '<input name="new_fields_' + repeater_prefix + '_terms[]" type="text" class="text" value="" />';
            new_row_markup += '</div>';
            new_row_markup += '</div>';
            new_row_markup += '</td>'
        }
        new_row_markup      += '<td class="remove">'
        new_row_markup      +=      '<a href="#" class="acf-button-remove story-post-edit-remove-row" data-storyid=""></a>';
        new_row_markup      += '</td>'

        new_row_markup      += '</tr>';



        if ( repeater_element.hasClass("empty") ) {
            repeater_element.find('> table > tbody').append(new_row_markup);
            repeater_element.removeClass("empty");
        }
         else {
            repeater_element.find('> table > tbody > tr.row:last').after(new_row_markup);
        }

        render();
    }


    }; // BH_BackendRepeater


    /**
     *
     * Wrapper function to create a Jquery plugin
     *
     * */
    $.fn.BH_BackendRepeater = function()
    {

        return this.each(function()
        {
            var element = $(this);

            // Return early if this element already has a plugin instance
            if (element.data('bh_repeater')) return;

            var bh_repeater = new BH_BackendRepeater(this);

            // Store plugin object in this element's data
            element.data('bh_repeater', bh_repeater);
        });
    };


})(jQuery);




$(window).load(function(){


    $("#dictionary-repeater").BH_BackendRepeater();
    $("#quotes-repeater").BH_BackendRepeater();

});