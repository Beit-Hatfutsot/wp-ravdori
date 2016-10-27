<?php
/**
 * This view Generates a list of all the Hebrew letters with links adding the
 * selected letter as a _GET parameter.
 *
 * The letter character we are getting from the _GET is saved as a  query_var
 * called "current_letter" in order to use it in other views.
 *
 * @package    views/dictionary
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

$hebrew_letters = array( 'א','ב','ג','ד','ה','ו','ז','ח','ט','י','כ','ל','מ','נ','ס','ע','פ','צ','ק','ר','ש','ת');

$dictionary_page_url = get_field( 'acf-options-dictionary-page-url' , 'options' );

// Get the current letter from the GET param and sanitize the input,
// Put the default value (the first letter) if null
if (  !isset (  $_GET['letter'] ) || $_GET['letter'] == NULL) $_GET['letter'] = $hebrew_letters[0];
$current_letter  = sanitize( $_GET['letter'] );

// Set the current_letter variable as a query variable,
// in order to share it with the calling template (views/dictionary/dictionary.php)
set_query_var( 'current_letter' , $current_letter );
?>
<div class="row">

<?php // Generate list of letters in the front end, with the letter we got from the _GET is marked in the style ?>
    <ul id="letters-list">
        <?php  foreach ( $hebrew_letters as $letter ): ?>
                 <li <?php echo ($current_letter == $letter) ? 'class="active"' : ''; ?>>
                    <a href="<?php echo $dictionary_page_url . '?letter=' . $letter; ?>">
                        <?php echo $letter; ?>
                    </a>
                 </li>
        <?php endforeach; ?>
    </ul>

</div>
