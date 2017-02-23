<?php
/**
 * Adding support in the backend enabling it to interact with the database
 *
 *
 * @package    functions/backend/backend.php
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// Adding DB CRUD support for the dictionary & quotes in the story custom post type
require_once('backend-story-cpt.php');


// Creating 2 Admin pages (appear in WP main menu), enabling
// CRUD support for dictionary & quotes
require_once('backend-admin-page.php');

// Adjust the story CPT's SCHOOLS taxonomy backend (adding filterable parent selectbox etc.) 
require_once('backend-story-cpt-category.php');

/**
 * Outputs a repeater in the backend with CRUD capabilities
 *
 * @param String $repeater_name - The name of the repeater (will be used in JS to identified the repeater element)
 *
 * @param Array $titles - The repeater's table's titles to show
 *
 * @param Array $terms -  The repeater's terms
 *
 */
function BH_build_repeater( $repeater_name , $titles , $terms , $show_add_new_button = true , $show_post_permalink = false , $send_post_ids = false)
{

    BH_build_repeater_head( $repeater_name , $send_post_ids );
    BH_build_repeater_header_titles( $titles );

    switch ( $repeater_name )
    {
        case "dictionary":
            BH_build_repeater_dictionary_body( $terms , $show_post_permalink  );
            break;

        case "quotes":
            BH_build_repeater_quotes_body( $terms , $show_post_permalink );
            break;
    };

    BH_build_repeater_footer( $show_add_new_button );
}



/**
 * Outputs the repeater's head's markup
 *
 * @param String $repeater_name - The name of the repeater (will be used in JS to identified the repeater element)
 *
 */
function BH_build_repeater_head( $repeater_name , $send_post_ids = false) {
    ?>

    <div id="<?php echo $repeater_name;?>-repeater" class="repeater <?php echo ( $send_post_ids ? ' send-post-id ' : ''); ?>" data-min_rows="0" data-max_rows="999" data-prefix="<?php echo $repeater_name;?>">
    <table class="widefat acf-input-table">

<?php

}



/**
 * Outputs the repeater's table columns titles
 *
 * @param Array $titles - Array of strings containing the
 *                        the repeater's table titles
 */
function BH_build_repeater_header_titles( $titles )
{
    ?>

    <thead>
    <tr>
        <th class="order"></th>

        <?php foreach( $titles as $title  ): ?>
            <th class="acf-th">
                <span><?php echo $title; ?></span>
            </th>
        <?php endforeach; ?>
        <th class="remove"></th>
    </tr>
    </thead>

<?php
}



/**
 * Outputs The dictionary terms ( the body of the repeater)
 *
 * @param Array $terms - Array of dictionary terms from the DB
 *
 */
function BH_build_repeater_dictionary_body( $terms , $show_post_permalink = false )
{
    ?>
	
	<?php 
	
			if( filter_input(INPUT_GET, 'paged') ) 
			{
				$page = filter_input(INPUT_GET, 'paged');
			} 
			else 
			{
				$page = 1;
			}
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$page = 1;
			}

			$terms_per_page   = 19; // How many terms to display on each page
			$total            = count( $terms );
			$pages            = ceil( $total / $terms_per_page );
			$min              = ( ( $page * $terms_per_page ) - $terms_per_page ) + 1;
			$max              = ( $min + $terms_per_page ) - 1;	
	?>
	
    <tbody>
    <?php $row_number = -1; ?>
    <?php if( !empty( $terms ) ): foreach( $terms as $term ): ?>

			<?php 
			
			 $row_number++;
			
			 // Ignore this term if $row_number is lower than $min
			 if( ($row_number + 1) < $min) { continue; }
			 
			 // Stop loop completely if $row_number is higher than $max
			 if( ($row_number + 1) > $max) { break; }
			
			?>
			
        <tr class="row" data-postid="<?php echo $term->post_id; ?>">

            <td class="order"><?php echo $row_number + 1; ?></td>
			
            <td >
                <div class="inner">
                    <div class="acf-input-wrap">
                        <input name="old_fields_dictionary_terms[<?php echo $term->dictionary_term_id; ?>]"
                               type="text"
                               class="text"
                               value="<?php echo  htmlentities( stripslashes( $term->dictionary_term ) ); ?>"
                               data-storyid="<?php echo $term->dictionary_term_id; ?>"
                            />
                    </div>
                </div>
            </td>

            <td >

                <div class="inner">
                    <div class="acf-input-wrap">
                        <textarea name="old_fields_dictionary_values[<?php echo $term->dictionary_term_id; ?>]" class="textarea" rows="8" data-storyid="<?php echo $term->dictionary_term_id; ?>"><?php echo htmlentities( stripslashes( $term->dictionary_value) ); ?></textarea>
                    </div>
                </div>

            </td>

            <td class="remove">
                <a href="#" class="acf-button-remove story-post-edit-remove-row" data-storyid="<?php echo $term->dictionary_term_id; ?>"></a>
            </td>

            <?php if ( $show_post_permalink ): ?>
                <td>
                    <a href="<?php echo get_edit_post_link( $term->post_id ); ?>"><?php echo __( "מתוך: " , "BH" ) . get_the_title( $term->post_id );?></a>
                </td>
            <?php endif;?>
        </tr>
    <?php endforeach; 
	
		  // Pagination
		  echo '<div class="pagination-backend" style="margin: 25px 0;">'; 	
			
			$args = array(
							'base' => '%_%',
							'format' => '?paged=%#%',
							'current' => $page,
							'mid_size' => 10,
							'total' => $pages
						 ); 
			
			if ( isset( $_POST[ 'term' ] ) ) 
			{
				$search_term = strip_tags( trim( $_POST[ 'term' ] ) ); 
				
				$args['add_args'] = array( 'term' => $search_term );
				$args['paged']    = '1';
				
			}	

			echo paginate_links( $args );
		  echo '</div>';
		  
		  
  
	else:
		echo '<h2 style="margin: 15px 0">' .  'לא נמצאו ערכים' . '</h2>';
	endif; ?>
    </tbody>


<?php



}



/**
 * Outputs The quotes from the DB ( the body of the repeater )
 *
 * @param Array $terms - Array of quotes from the DB
 *
 */

function BH_build_repeater_quotes_body( $terms , $show_post_permalink = false )
{
?>

<?php 
	
			if( filter_input(INPUT_GET, 'paged') ) 
			{
				$page = filter_input(INPUT_GET, 'paged');
			} 
			else 
			{
				$page = 1;
			}
			
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$page = 1;
			}

			$terms_per_page   = 19; // How many terms to display on each page
			$total            = count( $terms );
			$pages            = ceil( $total / $terms_per_page );
			$min              = ( ( $page * $terms_per_page ) - $terms_per_page ) + 1;
			$max              = ( $min + $terms_per_page ) - 1;	
?>

    <tbody>
    <?php $row_number = -1; ?>
    <?php if( !empty( $terms ) ): foreach( $terms as $term ): ?>

		<?php 
			
			 $row_number++;
			
			 // Ignore this term if $row_number is lower than $min
			 if( ($row_number + 1) < $min) { continue; }
			 
			 // Stop loop completely if $row_number is higher than $max
			 if( ($row_number + 1) > $max) { break; }
			
		?>
	
        <tr class="row" data-postid="<?php echo $term->post_id; ?>">

            <td class="order"><?php echo $row_number + 1; ?></td>

            <td >
                <div class="inner">
                    <div class="acf-input-wrap">
                        <input  name="old_fields_quotes_terms[<?php echo $term->quote_id; ?>]"
                                type="text"
                                class="text"
                                value="<?php echo htmlentities( stripslashes( $term->quote_value ) );?>"
                                data-storyid="<?php echo $term->quote_id; ?>"
                            />
                    </div>
                </div>
            </td>

            <input name="old_fields_quotes_pk[]" type="hidden" class="hidden" value="<?php echo $term->quote_id; ?>" />

            <td class="remove">
                <a href="#" class="acf-button-remove story-post-edit-remove-row" data-storyid="<?php echo $term->quote_id; ?>"></a>
            </td>

            <?php if ( $show_post_permalink ): ?>
                <td>
                    <a href="<?php echo get_edit_post_link( $term->post_id ); ?>"><?php echo __( "מתוך: " , "BH" ) . get_the_title( $term->post_id );?></a>
                </td>
            <?php endif;?>

        </tr>
    <?php endforeach; 
	
		  // Pagination
		  echo '<div class="pagination-backend" style="margin: 25px 0;">'; 	
			
			$args = array(
							'base' => '%_%',
							'format' => '?paged=%#%',
							'current' => $page,
							'mid_size' => 10,
							'total' => $pages
						 ); 
			
			if ( isset( $_POST[ 'term' ] ) ) 
			{
				$search_term = strip_tags( trim( $_POST[ 'term' ] ) ); 
				
				$args['add_args'] = array( 'term' => $search_term );
				$args['paged']    = '1';
				
			}	

			echo paginate_links( $args );
		  echo '</div>';
		  
		  
  
	else:
		echo '<h2 style="margin: 15px 0">' .  'לא נמצאו ערכים' . '</h2>';
	
	endif; ?>
    </tbody>


<?php


}



/**
 * Outputs the repeater's footer markup
 */
function BH_build_repeater_footer( $show_add_new_button = true )
{
    ?>
    </table>

    <ul class="hl clearfix repeater-footer">
        <li class="right">
            <?php if ( $show_add_new_button ): ?>
            <a href="#" class="add-row-endX acf-buttonX story-post-edit-add-row"><?php _e( 'הוספת ערך חדש' , 'BH' ); ?></a>
            <?php endif; ?>
        </li>
    </ul>
    </div>

<?php

}