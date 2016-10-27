<?php
/**
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 **/
class BH_LoginModel {

    public function getLoginInfo() {

        // The returning array which will hold all the data from the DB
        $loginInfoArray  = array ();

        // Save them to the returned array
        $loginInfoArray['countries'] = BH_get_cached_location( SCHOOLS_TAXONOMY );


        return ( $loginInfoArray );
    }


} // EOC





