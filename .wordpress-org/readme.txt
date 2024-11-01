=== Unofficial Magicseaweed Surf Forecast ===
Contributors: mschl4
Tags: surf report,surf forecast, surf, surf conditions, surfing, surf spot, wave conditions, swell conditions, MagicSeaweed
Tested up to: 3.7.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Get weekly surf forecast for selected surf spots onto your Wordpress blog with a widget.

== Description ==

Provides widgets to display forecasts of surf data for a selected spot.
Queries the Magicseaweed Surf Forecast API and displays the upcoming week's predicted surf conditions.
Forecasts wave height, swell height, swell direction, period, wind, and wind direction.
Useful options allow for an uncluttered display in tight sidebars.
Responsive design allows for any width;

== Installation ==

1. Unzip the archive and put the `magicseaweed-surf-report` folder into `/wp-content/plugins/` directory.
2. Activate the plugin from the Plugins menu.
3. Write to general@magicseaweed.com for an API Key and Secret.
4. Set you API Key and Secret in the admin plugins->Surf Forecast.
5. Put a check next to Use MagicSeaweed Data, indicating that you are OK with putting a powered-by MagicSeaweed
		logo at the top of the forecast data they are providing you with.
6. Select your surf spot on http://www.MagicSeaweed.com and retrieve the surf spot ID.
		It will be the last argument in the url string once a location is selected.
		Example:  3402    in the url    http://magicseaweed.com/Djeno-Point-Surf-Report/3402/
7. Add a widget to your sidebar and input the spot ID.
8. Select the forecast graphs you wish to display and save your widget.
9. Enjoy the upcoming weeks surf!

= Usage =
Case 1: You have a lot of width to the sidebar in which you are placing the widget. 
Suggested settings:
		timeLabels: '6 hours'
		barLabels: 'yes'
		arrows: 'more'.
		
	This will display the maximum amount, and most specific data available through the plugin display options.	
		
		
Case 2: You do not have a lot of width to the sidebar in which you are placing the widget.
Suggested settings:
		timeLabels: '24 hours'
		barLabels: 'no'
		arrows: 'less'.

	This will display less data with the intention to make the forecast graphs more readable by uncluttering the sidebar.	

	== Screenshots ==
1. This is a full surf forecast for one surf spot, with all graphs and details enabled.

2. This is a closeup of the swell and break height forecasts. 

3 This is a graph report with details disabled and container width reduced to that of a very small sidebar.

== Frequently Asked Questions ==

Do I have to display the Magicseaweed logo?
	Yes, Magicseaweed requires it for the use of their surf data.
	
Why are some of my graphs empty?
	Not all data will be available for all spots. You are probably accessing a bouy that doesn't provide the data you are looking for.
	Uncheck that graph in the widget admin.

How can I change the colors on the color-scaled period graphs?
	Edit the functions file and look for the $periodColorMap variable.
	Input you own hexColors there and the functions will create a new scale for you.

My font doesn't look right, what do I do?
	Edit the surf_report.css file in the includes folder. I would only edit the first element, AKA, #magicSeaweedSurfReport.
	Fonts should be edited there because the rest of the report is responsive to those font sizes.

== Changelog ==

= 1.0 =	
This is the first version.

For more help and support, see the plugin's official page: http://maxssite.com/magicseaweed-surf-forecast
email: schla106@umn.edu