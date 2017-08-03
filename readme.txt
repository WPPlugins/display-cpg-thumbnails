=== Display CPG Thumbnails ===
Contributors: macmiller
Donate link: tbd
Tags: CPG, Gallery, Display Images, Coppermine, Widget
Requires at least: 3.2.1
Tested up to: 3.2.1
Stable tag: 1.0481

A widget that accesses your Coppermine Gallery and displays thumbnails on your Wordpress page.

== Description ==

The plugin is intended to create a number of links which you can display on your wordpress page.  Each link is represented by a thumbnail image which exists within the associated CPG Gallery.  Clicking on a link will take you to the associated picture within the CPG application.  There are a number of parameters which indicate how many images to display per row and how many columns, along with size information and flexible caption (user defined information).

As one of the parameter driven data points which can be used in the caption is user name.  For this reason the plugin will work correctly if the CPG Gallery is 'bridged' to phpbb or not (in the case in which it is bridged, the user name is pulled from the phpbb database).

One of the very nice features of the plug in is that you can specify the maximum number of images for one user (or the maximum number of images for one album).  If you are displaying the latest uploaded images and one user has just uploaded a bunch of images, this helps keep the display 'balanced' and present a cross section of images on your page.  

== Installation ==

1.  Copy to your plugin folder.
2.  Activate the plugin.
3.  Go to Widgets. 
4.  Drag the widget to the desired sidebar.
5.  Establish the required parameter values on the Widget Screen.
6.  Edit and set the DEFINE statemtents to correspond with your environment in the script 'DisplayCPGThumbnailsGuts.php'.

Widget Setup

* Title:  Type in the desired title.
* CPG Config Location:  This is the file location of your CPG Config file.  There is a Document Root Path display on the bottom of the screen to help you with this.  This file is in your CPG Directory and is conifg.inc.php.  The indicated location is the full file location and not the URL.  
* Random or Update:  This radio box selects if the display of selected thumbnails is random or update.
* Number Images:  The number of images to be displayed, must be a numeric integer value between 1 and 100 inclusive.
* Max Per Album:  The maximum number of images that can be displayed from any one album.  A value of -1 means the field is ignored.  Values allowed are between 1 and 5 inclusive.
* Max Per User:  The maximum number of images to be displayed from any one user.  A value of -1 means the field is ignored.  Values allowed are between 1 and 5 inclusive.
* Thumb Width:  The maximum width of the thumbnail displayed, in pixels.  A value of -1 means the field is ignored.  Valid values are between 50 and 100 inclusive.
* Thumb Height:  The maximum height of the displayed thumbnail in pixels.  A value of -1 means the field is ignored.  Valid values are between 50 and 100 inclusive.
* NBR of TBL Cols:  The number of columns displayed.  If -1 is entered the output is not returned as a html table, otherwise values must be between 1 and 12 inclusive.  
* Caption Options:  This controls the captioning text.  Valid options are 'USR' (for user name), 'DTE' (for upload date), 'TIT' (for picture title) and 'CAP' (for picture caption).  These are entered as you want text displayed below your thumbnails.  The values are entered separated by '/', eg. DTE/TIT to display only a date and title.
* Caption Prefixes:  The prefix displayed before the caption option.  These are entered separated by '/' and are free text.
* Caption Lengths:  Indicates the length in bytes of the associated caption option.
* Caption Cont. Text:  This is the text you want after the max length has been reached and that caption option has been trunctated.  For example, '...' would do something like 'my new do...' if the length was set to 9.  
* Document Root Path:  For display only.  Indicates the root file path.  This will be helpful in setting up the CPG Config Location.
* Parameter Validation Return Area:  For display only.  Indicates if there are any errors.

Defines in DisplayCPGTHumbnailsGuts.php

You will need to set up these variables depending on your requirements and setup.  The only item that is mandatory to set is the BRIDGEDtoPHPBB3.  All of these items are defines in the script because once set up they will not normally ever need to be modified.  

* define("BRIDGEDtoPHPBB3", TRUE);  ->  This indicates if your CPG environment is 'bridged' to a phpbb3 forum.  This is important since if your CPG environment is bridged to a forum the user name will be in the forum database (not within CPG).  Since the user name is displayed as a part of the caption this is important.  
* define("sqldb_MAX_READS", -1); ->  This is the max number of reads if not -1.  Recommend just leaving it as -1 unless it is required for performance.
* define("scaleImages", FALSE); ->  This indicates if the images are to be 'scaled'.  If this is set to true (not recommended) both the width and height directives will be used on the img tag.
* define("sizeDownOnly", TRUE); -> If set to true will not increase the size of the image.  Example, actual image height is 80 px, height is indicated as 100 px, the displayed image will be 80.  

CSS

You will need to add some CSS to your wordpress theme.  This normally goes into the style.css file in your theme folder.  There may be some more CSS you need to integrate the widget into your theme.  You can see the full scope of css at [coolthaihouse]( www.coolthaihouse.com/ "coolthaihouse web site") by using firebug.  

`table.CPGImage{
   border: none !important;
   margin: 0px 0px 0px 3px !important;
   width: auto !important;}
.CPGImage tr td {border-width: 0px 1px 0px 1px !important;
    border-color: #E4E2DB !important;
    border-style: dotted !important; 
    padding: 2px 2px 2px 2px !important;}
table.CPGImage td:first-child {border-left: 0 !important;}
table.CPGImage td:last-child {border-right: 0 !important;}
 

.CPGImage tr td {margin-bottom: 3px !important;}
.CPGImage p {
   width: 90px !important;
   margin-bottom: 0px !important;
   margin-right: 2px !important;
   padding-right: 2px !important;
   margin-left: 2px !important;
   margin-right: 2px !important;
   font-size: 9px !important;
   vertical-align: bottom !important;
   text-align: left !important;
   line-height: 1.1em !important;
   word-wrap: break-word !important;
   clear: both !important;
	}`

Table

If the NBR of TBL Cols is used (not -1) the output is tabelized.  

`The resultant tabel looks something like this:
		<table class="CPGImage">
			<tr>
				<td>
					<a href and img tags with text p>
				</td>
				<td>`
				etc.


== Frequently Asked Questions ==

= Why are tables used? =

The formatting of the output is easier in table format.  There is also a non-table output option available (set NBR of TBL Cols = -1).

= I never worked with CSS before.  Can I make this plugin look nice on my blog? =

In all likelyhood, no.

= I don't know how to edit a php script.  Should I attempt to use this plugin? =

Probably not.

= I don't know how to edit a php script.  Should I attempt to use this plugin? =


In all likelyhood, no.
== Screenshots ==

1. The available widget with associated text as it appears under the Appearance -> Widgets page.
2. This is how the screen widgets parameters setting page appears when you click on the widgets page.
3.  This is the generated output for a latest update display on the Wordpress page.
4.  This is the generated output for a random image display on the Wordpress page.

== Changelog ==

= 1.0 =
* Initial release.

= 1.01 =
* Updated the readme.txt file to the correct format.  Added associated images.  This update does not require an upgrade for existing plugin users.

= 1.02 =
= 1.03 =
= 1.04 =
= 1.047 =
= 1.048 =
= 1.0481 =
* This series of updates only used for SVN experimentation and slight updates to readme.

== Upgrade Notice ==

= 1.01 =
All of the updates from this version thru 1.0481 are optional, the only thing being changed is the readme.txt file.

