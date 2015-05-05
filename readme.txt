=== Inbox Status ===
Contributors: pdclark, blobaugh
Plugin URI: https://github.com/pdclark/inbox-status
Author URI: http://pdclark.com
Tags: email, mail, imap, inbox, unread, gmail, yahoo, icloud, outlook, aol
Requires at least: 3.4
Tested up to: 3.9
Stable tag: 1.1.5
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display stats from your email inbox.

== Description ==

Retrieve information from your email inbox over IMAP, then display information, like your unread email count.

**Widget**

* **Inbox Status — Email Count** displays total email and total unread count.

**Shortcodes**

`
[inbox-unread]
[inbox-total]

[gmail-important]
[gmail-starred]
[gmail-primary]
[gmail-social]
[gmail-promotions]
[gmail-updates]
[gmail-forums]
`

**Theme Actions**

`
// Display unread email count in your theme
// Replace 'inbox-unread' with any of the other shortcode values listed above
<?php do_action( 'inbox_status_count', 'inbox-unread' ); ?>
`

== Screenshots ==

1. Shortcode examples.
1. Add shortcodes to menu items.
1. Unread count in a menu item.
1. Inbox info in a widget.

== Installation ==

1. Search for "Inbox Status" under `WordPress Admin > Plugins > Add New`
1. Activate the plugin.
1. Input your IMAP settings in `Settings > Inbox Status`

== Changelog ==

= 1.1.5 =
* Fix: (Minor) Avoid edge-case error output by typecasting array. Thanks @obo236.

= 1.1.4 =
* Fix: Widget output.

= 1.1.3 =
* Fix: Blank server field when 'Other' selected. Thanks for the report @blobaugh.

= 1.1.2 =
* Fix: Resolve fatal error for IMAP notices.

= 1.1.1 =
* Fix: Automatically show advanced options when "Other" email provider is selected.

= 1.1 =
* New: Server presets for popular email services: Gmail, Outlook, Yahoo, iCloud, and AOL.
* New: Meaningful notices when setting up the plugin and the IMAP connection.
* New: Support for Gmail priority inbox, categorized inbox, and stars.
* Changed: Theme actions changed for forward-compatibility with new shortcodes.
* Note: Stars display result of `is:starred` only. Gmail does not appear to allow searches for advanced stars, like `is:green-box` over IMAP.

= 1.0 =
* Initial public release.

== Upgrade Notice ==

= 1.1.5 =
* Fix: (Minor) Avoid edge-case error output by typecasting array. Thanks @obo236.
