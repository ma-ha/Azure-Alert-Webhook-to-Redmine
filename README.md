# AzureAlert-Webhook-to-Redmine
Webhook for Microsoft Azure alerts to create issue (bug) in Redmine automatically.

Requirements: You need an PHP enabled web server. 
I use an Azure VM with Ubuntu 16.04 LTS, Apache 2.4.18 and PHP 7.0.4.

## Installation
It's easy and can be done in 3 min. 

On the the (Redmine) server (Azure VM):

1. Create a `/var/www/html/alert` folder
2. Copy the (https://github.com/ma-ha/AzureAlert-Webhook-to-Redmine/blob/master/alert/index.php)[index.php) into the new folder 
   (alternatives: create the file, open it in an editor , e.g. vi and copy/paste the PHP code from GIThub or simply clone the project there) 
3. Configure the `$redmineURL`to your needs 
4. Configure Azure alert webhook to `http://<redmine-server-name or IP>/alert/`

I recommend to configure a DNS name in Azure for the Redmine server endpoint, so you don't need a static IP.

Test it with _curl_ or _REST easy_ :

	POST http://<server-name or IP>/alert/
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

## Options
### Token authentication
In alert/index.php insert a token string, eg 

	...
	// Optional: Define a token for simple authentication:
	$token  = 'abcdefg'; // ouch, not very secure
	... 

To authenticate using this token, you need to add an URL parameter in the Azure web hook URL.
The web hook should look now this way `http://<server>/alert/?token=abcdefg`

### Specify project_id
Issues are created within the first project (project_id=1) by default. 
If you have to create the issue in a different project, 
you can easily do that by adding the _project_id_ as URL parameter in the web hook, e.g.
`http://<server>/alert/?token=abcdefg&project_id=2`

Advice: You can get the project id via the [http://www.redmine.org/projects/redmine/wiki/Rest_Projects](Redmine API)