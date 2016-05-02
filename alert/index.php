<?php
// Copyright M. Harms 2016 (MIT License). All rights reserved.
 
// header('Content-type: application/json');

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	$data = json_decode( file_get_contents('php://input'), true );
	echo '{"context":"'.$data['status'].'"}';
	// TODO
} else {
	// redirect .... forgotten / at end of URL?
}
/* Redmine: Create Issue
 POST: https://ddd-dynop-redmine.cloudapp.net/redmine/issues.json
Content-Type: application/json
{
  "issue": {
    "project_id": 1,
    "tracker_id": 1,
    "status_id": 1,
    "subject": "Example",
    "priority_id": 4,
    "custom_fields":[{"id":1, "value":"PrM (PoC)"}]
  }
}
 */
	
/* sample alert from MS docu https://azure.microsoft.com/en-us/documentation/articles/insights-webhooks-alerts/
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
	            "portalLink": "https://portal.azure.com/#resource/subscriptions/s1/resourceGroups/useast/providers/microsoft.foo/sites/mysite1”                                
	},
	"properties": {
	              "key1": "value1",
	              "key2": "value2"
	              }
}
 */

?>