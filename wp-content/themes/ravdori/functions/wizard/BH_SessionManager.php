<?php
/**
 *
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( "SESSION_TIMEOUT"  , 47 * MINUTE_IN_SECONDS ); // Set Session timeout to 47 Minutes:  45 * 60 sec for the time() method

/* Singleton */
class Session
{
    const SESSION_STARTED     = TRUE;
    const SESSION_NOT_STARTED = FALSE;


    // The state of the session
    protected  $sessionState = self::SESSION_NOT_STARTED;

    // THE only instance of the class
    protected  static $instance;


    private function __construct() {}


    /**
     *    Returns THE instance of 'Session'.
     *    The session is automatically initialized if it wasn't.
     *
     *    @return    object
     **/

    public static function getInstance()
    {
        if ( !isset(self::$instance))
        {
            self::$instance = new self;
            self::$instance->startSession();

        }

        return self::$instance;
    }


    /**
     *    (Re)starts the session.
     *
     *    @return    bool    TRUE if the session has been initialized, else FALSE.
     **/

    public function startSession()
    {
        if ( $this->sessionState == self::SESSION_NOT_STARTED )
        {
            if( !session_id() ) {
                $this->sessionState = session_start();
			}

        }

        return $this->sessionState;
    }


    /**
     *    Stores datas in the session.
     *    Example: $instance->foo = 'bar';
     *
     *    @param    String    Name of the datas.
     *    @param    string    Your datas.
     *    @return   void
     **/

    public function setField( $name , $value )
    {
        $_SESSION[$name] = $value;
    }


    /**
     *    Gets datas from the session.
     *    Example: echo $instance->foo;
     *
     *    @param    name    Name of the datas to get.
     *    @return    mixed    Datas stored in session.
     **/

    public function getField( $name )
    {
        if ( isset($_SESSION[$name]))
        {
            return $_SESSION[$name];
        }
    }


    public function __isset( $name )
    {
        return isset($_SESSION[$name]);
    }


    public function __unset( $name )
    {
        unset( $_SESSION[$name] );
    }


    /**
     *    Destroys the current session.
     *
     *    @return    bool    TRUE is session has been deleted, else FALSE.
     **/

    public function destroy()
    {
        if ( $this->sessionState == self::SESSION_STARTED )
        {
            $this->sessionState = !session_destroy();

            unset( $_SESSION );

            return !$this->sessionState;
        }

        return FALSE;
    }
}



/**
* This class is used as a wrapper to the session variable
 */
class BH_SessionManager extends Session
{

    private function __construct() {}

    /**
    *    Returns THE instance of 'Session'.
    *    The session is automatically initialized if it wasn't.
     *
     *    @return    object
     **/

    public static function getInstance()
    {
        if ( !isset(self::$instance))
        {
            self::$instance = new self;

            // Set the current step to the first
            self::$instance->setField ( IWizardSessionFields::CURRENT_STEP  , IWizardStep1Fields::ID );
            $_SESSION[ IWizardSessionFields::STEP_STATUS ] = array();
			
			// Create a field to hold the current langauge, set to Hebrew as default
			self::$instance->setField( IWizardSessionFields::LANGUAGE_LOCALE , ISupportedLanguages::HE['get_param_value'] );		
			
        }

        self::$instance->startSession();

        return ( self::$instance );
    }


    /**
     * This function returns whether the user can go to the step $step
     *
     * $param $stepID - the step id
     *
     * @return Bool - true, if the user can go to this step, false otherwise
     */
     public function isStepAvailable ( $stepID ) {

        return (  ( isset ( $_SESSION[ IWizardSessionFields::STEPS ][ $stepID ] )       ) AND
                  (         $_SESSION[ IWizardSessionFields::STEPS ][ $stepID ] != null )
               );

    }


    public function getStepStatus ( $stepID ) {

        $stepStatus = null;

        if (  isset( $_SESSION [ IWizardSessionFields::STEP_STATUS ][ $stepID ] ) ) {
            $stepStatus = $_SESSION [IWizardSessionFields::STEP_STATUS][$stepID];
        }

        return ( $stepStatus );
    }


    public function setStepStatus ( $stepID , $status  ) {
         $_SESSION[ IWizardSessionFields::STEP_STATUS ][ $stepID ] = $status;
    }


    /**
     * Sets the current step
     */
    public function setCurrentStep( $step ) {

             self::$instance->setField ( IWizardSessionFields::CURRENT_STEP  , $step );
    }

    /**
    * Returns the current step
    */
    public function getCurrentStep() {
            return ( self::$instance->getField( IWizardSessionFields::CURRENT_STEP  ) );
    }


    /**
    * Sets a step fields values - used after insuring the fields are valid
    */
    public function setStepData( $stepID , $stepFields ) {

        if ( !isset($_SESSION[ IWizardSessionFields::STEPS ]) )
            $_SESSION[ IWizardSessionFields::STEPS ] = array();

       $_SESSION[ IWizardSessionFields::STEPS ][ $stepID ]  = $stepFields;

    }

    public function setStepDataByField( $stepID , $fieldName , $fieldValue ) {
        $_SESSION[ IWizardSessionFields::STEPS ][ $stepID ][$fieldName] = $fieldValue;
    }

    /**
     * Gets a step field value
     */
    public function getStepData( $stepID ) {


        if ( isset( $_SESSION[ IWizardSessionFields::STEPS ] ) )
            if ( isset( $_SESSION[ IWizardSessionFields::STEPS ][ $stepID ] ) )
                return $_SESSION[ IWizardSessionFields::STEPS ][ $stepID ];

        return null;
    }



    public function checkTimeout( $updateTime = true )
    {
        // Check if the timeout field exists.
        if( isset( $_SESSION['timeout'] ) )
        {
            // See if the number of seconds since the last
            // visit is larger than the timeout period.
            $duration = time() - (int)$_SESSION['timeout'];

			
            if( ( $duration > SESSION_TIMEOUT )  )
            {
                // Destroy the session
                $this->destroy();

                // Log off the user if such connected
                if ( is_user_logged_in() ) {
                    wp_logout();
                }

                //header( "Location: " . HOME );
				wp_redirect( home_url() ); exit;

            }
        }
		else {
			error_log("Session not set");
			error_log( print_r( $_SESSION['timeout'],true) );
		}
		
		if ( $updateTime ) {
			// Update the timout field with the current time.
			$_SESSION['timeout'] = time();
		}	
    }
	
	
	
	
    public function isSessionTimeout()
    {
		$sessionTimeout = false;
		
		$session_login_time = $this->getField( IWizardSessionFields::SESSION_LOGIN_TIME );
		
		// If we registerd the session login time
		if ( !empty($session_login_time) ) 
		{	
	
		    // See if the number of seconds since the last
            // visit is larger than the timeout period.
            $duration = time() - (int)$session_login_time;
			
			if( ( $duration > SESSION_TIMEOUT ) /*AND ( basename(get_page_template()) == 'wizard.php' )*/ )
            {
               $sessionTimeout = true;
            }

		}

		
		return ( $sessionTimeout );
		
    }
	
	
	public function closeAndLogout()
	{
		 // Destroy the session
         $this->destroy();

         // Log off the user if such connected
        if ( is_user_logged_in() ) {
             wp_logout();
       }
	}
	

}// EOC


interface IWizardSessionFields {

    const CURRENT_STEP = 'CURRENT_STEP';
    const STEPS        = 'STEPS';
    const STEP_STATUS  = 'STEPS_STATUS';
	const LANGUAGE_LOCALE = 'langauge_local';
	const AJAX_SESSION_EXPIRED = 'SESSION_EXP'; // Used in ajax call to identify that the session is over
	const SESSION_LOGIN_TIME   = 'SESSION_LOGIN_TIME';
	
}

interface IWizardSessionFieldsStatus {

    const VALIDATING  = 'VALIDATING';
    const FIRST_TIME  = 'FIRST_TIME';
    const NO_ERRORS   = 'NO_ERRORS';
}