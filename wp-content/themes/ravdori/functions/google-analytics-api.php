<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once(FUNCTIONS_DIR . '/libs/google-api/src/Google/' . 'autoload.php');



function initializeAnalytics()
{
  // Creates and returns the Analytics Reporting service object.

  // Use the developers console and download your service account
  // credentials in JSON format. Place them in this directory or
  // change the key file location if necessary.
  $KEY_FILE_LOCATION = get_home_path() .  'RavDori-a9c1d1bd69aa.json';


  // Create and configure a new client object.
  $client = new Google_Client();
  $client->setApplicationName("RavDori");
  $credentials  = $client->loadServiceAccountJson($KEY_FILE_LOCATION,'https://www.googleapis.com/auth/analytics.readonly');

  $client->setAssertionCredentials($credentials);

  if ($client->getAuth()->isAccessTokenExpired()) {
		$client->getAuth()->refreshTokenWithAssertion();
  }
  
  $response = json_decode($client->getAuth()->getAccessToken());
  $accessToken = $response->access_token;


  $analytics = new Google_Service_AnalyticsReporting($client);

  return $analytics;
}



/**
 * Queries the Analytics Reporting API V4.
 *
 * @param service An authorized Analytics Reporting API V4 service object.
 * @return The Analytics Reporting API V4 response.
 */
function getReport($analytics) {

  // Replace with your view ID, for example XXXX.
  $VIEW_ID = "173303242";
	
  $beginingOfTheYear = date('Y-m-d', strtotime('first day of january this year'));
  //$todayMinusYear = $today->modify('-1 year')->format('Y-m-d');	
	
	
  // Create the DateRange object.
  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
  $dateRange->setStartDate( $beginingOfTheYear );
  $dateRange->setEndDate("today");
  
  //$dateRange->setStartDate("2018-12-15");
  //$dateRange->setEndDate("2018-12-16");

  // Create the Metrics object.
  $sessions = new Google_Service_AnalyticsReporting_Metric();
  $sessions->setExpression("ga:sessions");
  $sessions->setAlias("sessions");
  
  // Create the Dimension object
  $dimensions = new Google_Service_AnalyticsReporting_Dimension();
  $dimensions->setName("ga:userType");

  
  // Create the ReportRequest object.
  $request = new Google_Service_AnalyticsReporting_ReportRequest();
  $request->setViewId($VIEW_ID);
  $request->setDateRanges($dateRange);
  $request->setMetrics(array($sessions));
  $request->setDimensions(array($dimensions));

  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
  $body->setReportRequests( array( $request) );
  return $analytics->reports->batchGet( $body );
}


/**
 * Parses and prints the Analytics Reporting API V4 response.
 *
 * @param An Analytics Reporting API V4 response.
 */
function getNumberOfVisits(&$reports) {
  
  $data = [];
  
  for ( $reportIndex = 0; $reportIndex < count( $reports ); $reportIndex++ ) {
    $report = $reports[ $reportIndex ];
    $header = $report->getColumnHeader();
    $dimensionHeaders = $header->getDimensions();
    $metricHeaders = $header->getMetricHeader()->getMetricHeaderEntries();
    $rows = $report->getData()->getRows();

    for ( $rowIndex = 0; $rowIndex < count($rows); $rowIndex++) {
      $row = $rows[ $rowIndex ];
      $dimensions = $row->getDimensions();
      $metrics = $row->getMetrics();
      for ($i = 0; $i < count($dimensionHeaders) && $i < count($dimensions); $i++) {
        //print($dimensionHeaders[$i] . ": " . $dimensions[$i] . "\n");
		 $data[] = $dimensionHeaders[$i] . ": " . $dimensions[$i];
      }

      for ($j = 0; $j < count($metrics); $j++) {
        $values = $metrics[$j]->getValues();
        for ($k = 0; $k < count($values); $k++) {
          $entry = $metricHeaders[$k];
          //print($entry->getName() . ": " . $values[$k] . "\n");
		  
		  $data[] = $entry->getName() . ": " . $values[$k];
        }
      }
    }
  }
  // TODO: String check
  $visits_count = preg_replace('/[^0-9]/', '', $data[1]);
  
  return ( $visits_count );
}
