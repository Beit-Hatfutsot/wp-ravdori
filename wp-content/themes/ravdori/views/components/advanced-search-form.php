<?php
/**
 * The advanced search form
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


$advanced_search_string 		= __('חיפוש מתקדם', 'BH');
$close_menu_string				= __('סגור תפריט', 'BH');
$story_name_menu_string			= __('שם הסיפור / Story’s Title', 'BH');
$story_adult_menu_string		= __('שם המבוגר / Name of Interviewee', 'BH');
$story_student_menu_string		= __('שם תלמיד / Student’s Name', 'BH');
$story_teacher_menu_string		= __('שם מורה / Teacher’s Name', 'BH');
$story_word_menu_string			= __('לפי מילה / Key Word/Phrase', 'BH');
$story_quote_menu_string		= __('לפי ציטוט / By Quote', 'BH');
$story_country_menu_string		= __('חיפוש לפי ארץ מוצא', 'BH');
$story_default_country_menu_string = __('בחרו מדינה / Select Country of Origin', 'BH');
$clear_search_fields = __('ניקוי כל השדות', 'BH');
$exact_search = __('חיפוש מדויק', 'BH');

$advnaced_search_page = get_field('acf-options-advanced-search','options');


$countries = BH_get_cached_location( SCHOOLS_TAXONOMY );

$countryToShow = get_field( 'acf-options-wizard-step1-country' , 'options' ); 

// After search

$query_story_name = '';
$query_adult_name = '';
$query_student_name = '';
$query_teacher_name = '';
$query_country_id = '';
$query_word_name = '';
$query_quote_name = '';


if ( isset($_GET['advanced_search__story_name'])):
	$query_story_name = sanitize_text_field($_GET['advanced_search__story_name']);
endif;

if ( isset($_GET['advanced_search__adult_name']) ):
	$query_adult_name = sanitize_text_field($_GET['advanced_search__adult_name']);
endif;


if ( isset($_GET['advanced_search__student_name']) ):
	$query_student_name = sanitize_text_field($_GET['advanced_search__student_name']);
endif;


if ( isset($_GET['advanced_search__teacher_name']) ):
	$query_teacher_name = sanitize_text_field($_GET['advanced_search__teacher_name']);
endif;


if ( isset($_GET['advanced_search__country']) ):
	$query_country_id = sanitize_text_field($_GET['advanced_search__country']);
endif;

if ( isset($_GET['advanced_search__word_name']) ):
	$query_word_name = sanitize_text_field($_GET['advanced_search__word_name']);
endif;

if ( isset($_GET['advanced_search__quote_name']) ):
	$query_quote_name = sanitize_text_field($_GET['advanced_search__quote_name']);
endif;


// Checkbox state

$story_checkbox_is_on 	= (isset($_GET['story-name-exact']) 	 AND $_GET['story-name-exact']	 == 'on') ? ' checked ' : '';
$adult_checkbox_is_on 	= (isset($_GET['adult-name-exact']) 	 AND $_GET['adult-name-exact'] 	 == 'on') ? ' checked ' : '';
$student_checkbox_is_on = (isset($_GET['student-name-exact'])	 AND $_GET['student-name-exact'] == 'on') ? ' checked ' : '';
$teacher_checkbox_is_on = (isset($_GET['teacher-name-exact'])	 AND $_GET['teacher-name-exact'] == 'on') ? ' checked ' : '';
$word_checkbox_is_on 	= (isset($_GET['word-name-exact']) 	 	 AND $_GET['word-name-exact'] 	 == 'on') ? ' checked ' : '';
$quote_checkbox_is_on 	= (isset($_GET['quote-name-exact']) 	 AND $_GET['quote-name-exact'] 	 == 'on') ? ' checked ' : '';

?>
<div class="nav navbar-nav navbar-right advanced-search-container">
		<div class="dropdown advanced-search-dropdown">
			  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="bh-translate"><?php echo $advanced_search_string; ?></span> <span class="caret"></span></a>
				  <div class="dropdown-menu">
				  
					<form action="<?php echo $advnaced_search_page; ?>" role="search" method="get" id="advanced-search-form" class="wizard-form">
						
						<label for="advanced_search__story_name" class="visually-hidden">שם הסיפור</label>
						<input class="large advanced-search-form-input" type="text" id="advanced_search__story_name"   placeholder="<?php echo $story_name_menu_string;  ?>" name="advanced_search__story_name" value="<?php echo $query_story_name; ?>">
						
						<div class="checkbox-container">
							<label for="story-name-exact"><span class="bh-translate"><?php echo $exact_search;?></span></label>
							<input type="checkbox" id="story-name-exact" name="story-name-exact"  <?php echo $story_checkbox_is_on;?>>
						</div>
						
						<label for="advanced_search__adult_name" class="visually-hidden">שם המבוגר</label>
						<input class="large advanced-search-form-input" type="text" id="advanced_search__adult_name"   placeholder="<?php echo $story_adult_menu_string; ?>" name="advanced_search__adult_name" value="<?php echo $query_adult_name; ?>">
						
						<div class="checkbox-container">
							<label for="adult-name-exact"><span class="bh-translate"><?php echo $exact_search;?></span></label>
							<input type="checkbox" id="adult-name-exact" name="adult-name-exact" <?php echo $adult_checkbox_is_on;?>>
						</div>
						
						<label for="advanced_search__student_name" class="visually-hidden">שם התלמיד</label>
						<input class="large advanced-search-form-input" type="text" id="advanced_search__student_name" placeholder="<?php echo $story_student_menu_string; ?>" name="advanced_search__student_name" value="<?php echo $query_student_name; ?>">
						
						<div class="checkbox-container">
							<label for="student-name-exact"><span class="bh-translate"><?php echo $exact_search;?></span></label>
							<input type="checkbox" id="student-name-exact" name="student-name-exact" <?php echo $student_checkbox_is_on;?>>
						</div>
						
						<label for="advanced_search__teacher_name" class="visually-hidden">שם המורה</label>
						<input class="large advanced-search-form-input" type="text" id="advanced_search__teacher_name"  placeholder="<?php echo $story_teacher_menu_string; ?>" name="advanced_search__teacher_name"  value="<?php echo $query_teacher_name; ?>"  >
						
						<div class="checkbox-container">
							<label for="teacher-name-exact"><span class="bh-translate"><?php echo $exact_search;?></span></label>
							<input type="checkbox" id="teacher-name-exact" name="teacher-name-exact" <?php echo $teacher_checkbox_is_on;?>>
						</div>
						
						<div class="clearfix"></div>
						
						<!-- Country -->
						<div id="country-field" class="element-select" title="<?php echo $story_country_menu_string; ?>">
					
							<div class="large">
								<span class="wizard-select-theme">
									<label for="advanced_search__country" class="visually-hidden">חיפוש לפי ארץ</label>
									<select id="advanced_search__country" name="advanced_search__country"  class="chosen-rtl">
										<option value=""><?php echo $story_default_country_menu_string; ?></option>
										<?php foreach ($countries as $country): ?>
											 <option <?php echo($country->term_id == $query_country_id ? 'selected' : ''); // If israel );?> value="<?php echo $country->term_id; ?>" class="<?php echo 'country-' . $country->term_id; ?>">
												<?php echo $country->name; ?>
											</option>
											<br/>
										<?php endforeach ?>
									</select>
								</span>
							</div>
						</div>
						
						<label for="advanced_search__word_name" class="visually-hidden">ביטוי לחיפוש</label>
						<input class="large advanced-search-form-input" type="text" id="advanced_search__word_name"    placeholder="<?php echo $story_word_menu_string;  ?>" name="advanced_search__word_name" value="<?php echo $query_word_name; ?>">
						
						<div class="checkbox-container">
							<label for="word-name-exact"><span class="bh-translate"><?php echo $exact_search;?></span></label>
							<input type="checkbox" id="word-name-exact" name="word-name-exact" <?php echo $word_checkbox_is_on;?>>
						</div>
						
						<label for="advanced_search__quote_name" class="visually-hidden">שם המבוגר</label>
						<input class="large advanced-search-form-input" type="text" id="advanced_search__quote_name"   placeholder="<?php echo $story_quote_menu_string; ?>" name="advanced_search__quote_name" value="<?php echo $query_quote_name; ?>">
						
						<div class="checkbox-container">
							<label for="quote-name-exact"><span class="bh-translate"><?php echo $exact_search;?></span></label>
							<input type="checkbox" id="quote-name-exact" name="quote-name-exact" <?php echo $quote_checkbox_is_on;?>>
						</div>
						
						<input type="submit" name="submit" value="חפש / Search" class="advanced-search-form-submit" style="margin-right: 0;">
						
						<div role="separator" class="divider"></div>
						
						<div class="advanced-search-form-additonal-buttons-container">
							<a class="clean-adv-form-btn"><span class="bh-translate"><?php echo $clear_search_fields; ?></span></a>
						</div>
						
					</form>

					
				  </div>
		</div>
</div>
