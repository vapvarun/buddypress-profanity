=== Wbcom Designs - BuddyPress Profanity ===
Contributors: wbcomdesigns  
Donate link: https://wbcomdesigns.com  
Tags: buddypress, profanity, filter, community, activity, comments, messages  
Requires at least: 3.0.1  
Tested up to: 6.8.1
Stable tag: 2.0.1  
License: GPLv2 or later  
License URI: http://www.gnu.org/licenses/gpl-2.0.html  

With this powerful profanity filtering plugin, you can keep your BuddyPress community free from inappropriate language. Ensure that posts, comments, and messages are clean and respectful to maintain a family-friendly environment.

== Description ==

The **BuddyPress Profanity** plugin provides robust tools to filter out inappropriate language in your community. Designed for BuddyPress, it ensures that your posts, comments, and messages are free from offensive words, offering a safer and more inclusive environment for all users.

Key Features:
- Filters offensive keywords from posts, comments, and messages.
- Keeps your BuddyPress database unaffected, filtering content dynamically for display only.
- Fully customizable settings for case sensitivity, strict filtering, and keyword replacement.
- Easy integration and intuitive user interface.

== Installation ==

Follow these steps to install and activate the plugin:

1. Download the zip file and extract it.
2. Upload the `buddypress-profanity` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Alternatively, install the plugin directly through the WordPress Plugins Installer (Dashboard → Plugins → Add New).
5. Configure the plugin through the settings menu and enjoy a profanity-free community!  

For advanced customisations, you can contact us for [Custom Development](https://wbcomdesigns.com/hire-us/).

== Frequently Asked Questions ==

= Does this plugin require BuddyPress? =  
Yes, BuddyPress must be installed and activated for this plugin to work.

= Can I filter multiple keywords? =  
Absolutely! Under the "Keywords to Remove" setting in the General tab, you can define multiple filters for keywords.

= How can I use a custom character to replace filtered keywords? =  
You can use the filters provided by the plugin for this purpose. An example is detailed in the "Support" tab within the plugin settings.

= Does this plugin modify the BuddyPress database? =  
No, the plugin only filters content for display purposes. The BuddyPress database remains unchanged.

= What does the Case Matching setting do? =  
The Case Matching setting offers two options:
- **Case Sensitive:** Filters exactly match, considering letter casing.  
- **Case Insensitive:** Filters all occurrences regardless of letter casing.  
For broader filtering, we recommend using the Case Insensitive option.

= How does the Strict Filtering setting work? =  
Strict Filtering ensures embedded keywords are filtered appropriately. Turning this option ON ensures better profanity detection and filtering.

== Changelog ==

= 2.0.1 =
* Fixed: Fatal error on activity page.
* Fixed: bbPress-related options now only show when bbPress is active.
* Fixed: Email input in activity/messages now correctly masked.
* Fixed: Blocked keywords now filter properly when forum activities are created.
* Fixed: Settings saved notice and backend UI issues with bb platform.
* Fixed: PHPCS issues in various plugin files for improved code standards.
* Fixed: Attempt to read license on license page now properly handled.
* Improved: Admin script loading optimized for better performance.
* Improved: Minified and optimized CSS, JS with RTL support.
* Improved: Email and phone masking logic across activity/message content.
* Improved: Content filtering refactored into a unified method.
* Updated: Strings for consistency and grammar.
* Updated: Documentation link and license page handling.
* Removed: Unused files and redundant code.

= 2.0.0 =  
* Enhanced UI for managing notices and keyword imports.  
* Optimised plugin settings registration.  
* Corrected user feedback form link.  

= 1.9.9 =  
* Removed dependency on hardG for improved consistency.  
* Updated language files.  
* Improved backend options with responsive fixes.  

= 1.9.8 =  
* Streamlined `filter_content` method for better performance.  
* Enhanced sanitisation mechanisms.  
* Improved multisite support.  

= 1.9.7 =  
* Fixed license deactivation issue when the response fails.  

= 1.9.6 =  
* Updated plugin banners and links.  
* Fixed compatibility with PHP 8.2.  

= 1.9.5 =  
* Fixed filtering issues in BuddyPress notifications.  
* Resolved plugin redirect conflicts during bulk activation.  

= 1.9.4 =  
* Improved bulk save option UI.  

= 1.9.3 =  
* Fixed keyword import functionality.  

= 1.9.2 =  
* Resolved BuddyBoss admin notice issue.  

= 1.9.0 =  
* Redesigned admin UI with better icons.  

= 1.8.0 =  
* Added bbPress Forums and Topics support.  
* Enhanced backend UI.  

= 1.7.0 =  
* Introduced support for BP Better Messages.  
* Added bulk import options for custom word lists.  

= 1.5.0 =  
* Enabled users to add new keywords dynamically.  

= 1.0.0 =  
* Initial release.

== Upgrade Notice ==

= 2.0.0 =  
Upgrade to version 2.0.0 to enjoy an improved user interface, better keyword filtering options, and enhanced plugin stability.

== Screenshots ==

1. **Settings Page**  
   User-friendly settings page to configure filtering options effortlessly.  

2. **Activity Feed**  
   Filtered activity feed ensuring no offensive language is displayed.  

3. **Keyword Management**  
   Easily manage and customize your keyword list for filtering.

== Development ==

This plugin is actively maintained by **Wbcom Designs**. We welcome contributions and feedback. Reach out to us for suggestions or improvements!
