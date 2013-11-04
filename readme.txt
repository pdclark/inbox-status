=== Inbox Status ===
Contributors: brainstormmedia, pdclark, blobaugh
Plugin URI: https://github.com/brainstormmedia/inbox-status
Author URI: http://brainstormmedia.com
Tags: email, mail, gmail, imap, inbox, unread
Requires at least: 3.4
Tested up to: 3.7.1
Stable tag: 1.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Display stats from your email inbox.

== Description ==

Retrieve information from your email inbox over IMAP, then display information, like your unread email count.

**Widget**

* **Inbox Status — Email Count** displays total email and total unread count.

**Shortcodes**

`[inbox-unread]`
`[inbox-total]`

**Theme Actions**

`
// Display unread email count in your theme
do_action( 'inbox_status_unread_count' );

// Display total email count in your theme
do_action( 'inbox_status_total_count' );
`

== Screenshots ==

1. Inbox info in a widget.
1. Unread count in a menu item.
1. Add shortcodes to menu items.

== Installation ==

1. Search for "Inbox Status" under `WordPress Admin > Plugins > Add New`
1. Activate the plugin.
1. Input your IMAP settings in `Settings > Inbox Status`

== Changelog ==

= 1.0 =
* Initial public release.