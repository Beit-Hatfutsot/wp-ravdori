<?php
/**
 * The template's main functions file
 *
 * Contains includes for all the needed modules
 *
 * ORDER OF require_once is matter, SHOULD NOT CHANGE IT
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Theme params - defines global paths (Css dir ,js dir , theme ver etc.)
require_once('functions/config.php');

// Theme support
require_once('functions/theme.php');

// Security
require_once('functions/security.php');

// Multilanguage support
require_once('functions/languages.php');

// ACF plugin functions
require_once('functions/acf.php');

// Wizard related functions
require_once('functions/wizard/consts.php');

// Taxonomies
require_once('functions/taxonomies.php');

// Wizard steps related functions
require_once('functions/wizard/steps.php');

// Wizard's front end editor
require_once('functions/wizard/editor.php');

// Scripts & styles registration
require_once('functions/scripts-n-styles.php');

// Post types
require_once('functions/post-types.php');

// Menus
require_once('functions/menus.php');

// Transients
require_once('functions/transients.php');

// Pagination
require_once('functions/pagination.php');

// Database
require_once('functions/database/database.php');

// Story
require_once('functions/story.php');

// Backend related functions
require_once('functions/backend/backend.php');

// Session init
require_once('functions/session.php');

// Search
require_once('functions/search.php');

// Utils
require_once('functions/utils.php');

// oEmbeds support
require_once('functions/embeds.php');

// Disable comments
require_once('functions/comments.php');

// Shortcodes
require_once('functions/shortcodes.php');