=== Personalize Content ===
Contributors: adapti
Donate link: www.adapti.me
Tags: personalization, adaptation, user experience
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 4.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create Personalized website contents according to each single user.

== Description ==

Create better user experiences by making your website personalize itself according to each user. Never show the same and « generic » content to your users again.

= Main Features: =
1. Connect to adapti.me API and access its functionalities
2. Create triggers allowing to understand your users and get Insights
3. Create and display page versions adapted to different types of users
4. Give users the control of their DATA back; let them see and control what information we have on them.

= How the service works: =
Adapti.me is a tracking and personalization service. In order to work, the services uses an API including a few functionalities. 

1a. If Known - We identify the visitor (with Fingerprint technology)
1b. If Unknown, not in private mode and accepts cookies (browser settings & cookie messages) - We create a new profile for this user. 
2. We track the users (mouse-tracking, navigation and click) except on no-go zones such as payment and profile pages.
3. With data-mining and an AI we interpret the tracked information into crypted and highly secure user-data.
4. We use these non-nominative user-data to find what contents are best adapted to the user and display it.
5. The user can connect on adapti.me and choose to change or delete the collected informations

= Links & Information: =
About the service: https://www.adapti.me/
Terms & Conditions: https://www.adapti.me/terms-and-conditions/
Privacy Policy: https://www.adapti.me/privacy/
 
== Installation ==

1. Upload the folders to the `/wp-content/plugins/adapti-link-wp` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create an account on www.adapti.me as a website owner and go through the installation process.
4. Define your website as using Wordpress and copy-paste the API Token.
5. Use the adapti.me dashboard to create Triggers to define your users (ex: visitors of this page are male)
6. Use the wordpress page editor to create page versions adapted to specific user information (ex: a version of the page for male users)
7. Be sure to add all legal informations about usage of cookies. Find tips on https://www.adapti.me/help-support/

== Changelog ==
= 2.0.1 =
* Insert automaticly script
* All rules are created direcly on adapti dashboard (www.adapti.me)

= 1.0.2 =
* Correcting count of rules
* Fixing small bugs
* Faster AJAX connection
* Better commenting of the code

= 1.0.1 =
* Correcting bug on Post pages
* Clean and commenting of Code
