<?php
// Copyright M. Harms 2016 (MIT License). All rights reserved.

// TODO: specify Redmine URL
$redmineURL =  'http://<redmine-base-url>/issues.json';
// or e.g.:
// $redmineURL = 'https://<redmine-user>:<redmine-password>@<redmine-base-url>/issues.json';

// Optional: Define a token for simple authentication:
$token  = ''; 

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	
	if ( $token != '' ) {
		if ( isset( $_GET['token'] ) ) {
			if ( $_GET['token'] != $token ) {
				header('HTTP/1.0 401 Unauthorized');
				error_log( 'Token invalid!');
				exit;
			}
		} else {
			header('HTTP/1.0 401 Unauthorized');
			error_log( 'Token required!');
			exit;
		}
	}
	
	$data = json_decode( file_get_contents('php://input'), true );
	// var_dump( $data );
	
	if ( $data['status'] == 'Activated' ) {
		
		// construct "issue" data structure for Redmine "create issue" API 
		$issue = array();
		$project_id = 1;
		if ( isset( $_GET['project_id'] ) ) {
			$project_id = $_GET['project_id'];
		}
		$issue['issue'] = array(
				"project_id" => $project_id,
				"tracker_id" => 1,
				"status_id"  => 1,
				"subject"    => $data['context']['resourceName'].': '.$data['context']['name'].' (Alert)',
				"description"=> "",//file_get_contents('php://input'),
				"priority_id"=> 4
		);
		$data_string = json_encode( $issue ); 
		error_log( $data_string );
		// set up POST request to Redmine
		$creIssue = curl_init( $redmineURL );
		curl_setopt( $creIssue, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt( $creIssue, CURLOPT_POSTFIELDS, $data_string );
		curl_setopt( $creIssue, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $creIssue, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen( $data_string ) )
			);
		$result = curl_exec( $creIssue );
		
		header('Content-type: application/json');
		echo "{ \"status\": \"$result\" }";
		
	} else {
		echo '{ "status":"'.$data['status'].'", "error":"Status not Activted. Nothing to do!" }';
		error_log( "Status '".$data['status']."' not 'Activted'. Nothing to do!" );
	} 
} else {
	error_log( "No POST call! -- Redirect?? Forgotten / at end of URL? " );
}
	
/* 
Sample Azure Alert: 
from MS docu https://azure.microsoft.com/en-us/documentation/articles/insights-webhooks-alerts/

 {
	"status": "Activated",
	"context": {
	            "timestamp": "2015-08-14T22:26:41.9975398Z",
	            "id": "/subscriptions/s1/resourceGroups/useast/providers/microsoft.insights/alertrules/ruleName1",
	            "name": "ruleName1",
	            "description": "some description",
	            "conditionType": "Metric",
	            "condition": {
	                        "metricName": "Requests",
	                        "metricUnit": "Count",
	                        "metricValue": "10",
	                        "threshold": "10",
	                        "windowSize": "15",
	                        "timeAggregation": "Average",
	                        "operator": "GreaterThanOrEqual"
	                },
	            "subscriptionId": "s1",
	            "resourceGroupName": "useast",                                
	            "resourceName": "mysite1",
	            "resourceType": "microsoft.foo/sites",
	            "resourceId": "/subscriptions/s1/resourceGroups/useast/providers/microsoft.foo/sites/mysite1",
	            "resourceRegion": "centralus",
	            "portalLink": "https://portal.azure.com/#resource/subscriptions/s1/resourceGroups/useast/providers/microsoft.foo/sites/mysite1"                                
	},
	"properties": {
	              "key1": "value1",
	              "key2": "value2"
	              }
}

Redmine: Create Issue
  POST: https://xyz-vm.cloudapp.net/redmine/issues.json
  Content-Type: application/json
	{
	  "issue": {
	    "project_id": 1,
	    "tracker_id": 1,
	    "status_id": 1,
	    "subject": "Example",
	    "description":"......."
	    "priority_id": 4,
	    "custom_fields":[{"id":1, "value":"abc"}]
	  }
	}
 */

?>