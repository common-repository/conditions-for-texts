=== Conditions for Texts (Dynamic Content) ===
Contributors: afnet
Tags: conditions, if, if statements, text blocks, if conditions, bedingungen, if bedingungen, textblöcke, conditions-for-text, conditions for text, Dynamic content, Shortcoder, tablepress, table press, create shortcode, content personalization, ifso, website customization, website personalization, conditional content, Real-time personalization, referral source, time & date, returning visitor, new visitor, personalization, smart site, custom website, marketing tool, conversion, dynamic web content, geolocation, user location, time, date, Scheduler, referrer, custom parameters, custom URL
Donate link: https://appfield.net/plug-ins/conditions-for-texts/
Requires at least: 4.0
Tested up to: 5.0.2
Requires PHP: 5.6
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

**MAKE YOUR CONTENT DYNAMIC!**
Use if statements with variables to display text blocks only under certain conditions. For example: Publish other text blocks or headlines at christmas time, than in the rest of the year.

== Description ==
The WordPress plug-in **Conditions for Texts** allows bloggers to use IF- & IF-ELSE conditions in their posts and pages. With this amazing plug-in specific content and texts can be published in accordance with certain conditions, for example date or time.

**The plug-in comes in a standard- and extended version - this is the standard version!** Please read the [documentation](https://appfield.net/plug-ins/conditions-for-texts/english/) to get information about the differences between the two versions and how to get the extended version.

**Do you have any ideas, or wishes for more variables in the plug-in included or did you find a bug - please [contact us](https://appfield.net/contact/)!**

== How to use this plug-in: ==

    // In Standard Version:
    [IF $month == 12]It's christmas time[/IF]
    [IF $month != 12]It's not christmas time[/IF]
    [IF $hour <= 12]Before lunch[/IF][IF $hour > 12]After lunch[/IF]
    [IF $month == 12]It's christmas time[ELSE]It's not christmas time[/IF]

    // In Extended Version:
    [IF $month == 12 AND $day==6]It's Saint Nicholas[ELSE]I's not Saint Nicholas[/IF]

*We recommend the use of the syntax in the plain text editing location.*

== The following comparison operators are supported: ==
!= , == , >= , <= ,  < , >

== The following variables for the conditions are available: ==

= In Standard- & Extended Version: =

* **$day** – Day (1…31)
* **$month** – Month (1..12)
* **$year** – Year (2018)
* **$hour** – Hour (1..24)
* **$minute** – Minute (1..59)
* **$date** – Date (2018-12-24)

= Only included in the Extended Version: =

* **$remoteIP** – IP-Address of the visitor (123.123.123.123)
* **$userAgent** – User-Agent of the visitor (String)
* **$httpReferer** – Referer-URL of the visitor (String)
* **$browserLanguage** – Language of the Browser (e.g. de,en,...)
* **$permaLink** – Permanent url of the current page or post (https://.../category/title)
* **$dayOfWeek**– Day of the week (0=Sonntag, 6=Samstag)
* **$dayOfYear** – Day of the year (1...365)
* **$leapYear**  – Leap year (true, false)
* **$amORpm** – Morning or Afternoon (am, pm)
* **$pageId**– ID of the current page (int)
* **$postId** – ID of the current post (int)
* **$categoryName** – Name of the category (String)
* **$authorId** – ID of the author (int)

[Do you have any ideas or wishes for more variables, please use our contact form!](https://appfield.net/contact/)

== Where can i use the condition statements and which third party plugins are supported?: ==
**You can use the conditions in the following sections:**

* Content text of a page or post (the_content)
* Title of a page or post (the_title, pre_get_document_title)
* Meta-Title and Meta-Description (wpseo_title, wpseo_metadesc)
* Text in the header <head></head> (wp_head)
* Text in the footer (wp_footer)
* Text in widgets (widget_text)

**The following third party plugins are supported:**

* Shortcode-Plugins like [Shortcoder](https://wordpress.org/plugins/shortcoder/) (Useful: Check if parameters in the shortcode content are set!)
* [Tablepress](https://wordpress.org/plugins/tablepress/)

[If you want another plugin-support, please use our contact form!](https://appfield.net/contact/)

== Installation ==
You can install this plugin directly from your WordPress dashboard:

**Standard-Version:**

1. Go to the *Plugins* menu and click *Add New*.
2. Search for *Conditions for Texts* or click on "Upload plugin" and choose the downloaded zip-file *conditions-for-texts-standard.zip*
3. Click *Install Now*.
4. Activate the plug-in.
5. Have fun!

**Extended-Version:**

1. [Download the Extended Version from here](https://appfield.net/plug-ins/conditions-for-texts/english/)
1. Go to the *Plugins* menu and click *Add New*.
2. Click on "Upload plugin" and choose the downloaded zip-file *conditions-for-texts-extended.zip*
3. Click *Install Now*.
4. Activate the plug-in.
5. Have fun!

== Changelog ==
= 1.0.2 =
* Bug
= 1.0.1 =
* Add IF-ELSE support for standard version. 
= 1.0.0 =
* Initial release.
<!--stackedit_data:
eyJoaXN0b3J5IjpbLTEwOTA4MjQ1MTFdfQ==
-->