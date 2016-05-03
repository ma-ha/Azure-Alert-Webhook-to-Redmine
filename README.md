# AzureAlert-Webhook-to-Redmine
Webhook for Azure alerts to create issue in Redmine automatically.

## Installation
On the the (Redmine) server (Azure VM):

1. Create a `/var/www/html/alert` folder
2. Copy the index.php into the new folder
3. Configure Azure alert webhook to `http://<server-name or IP>/alert`

Test it with _curl_ or _REST easy_ :

	POST http://<server-name or IP>/alert
	ContentType: application/json
	
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
