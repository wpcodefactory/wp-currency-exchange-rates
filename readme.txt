=== WP Currency Exchange Rates ===
Contributors: wpcodefactory, algoritmika, anbinder
Tags: currency exchange rates, currency, exchange rates
Requires at least: 4.4
Tested up to: 6.1
Stable tag: 1.2.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Currency exchange rates for WordPress.

== Description ==

**WP Currency Exchange Rates** plugin lets you display currency exchange rates on your site.

### &#9989; Main Features ###

Rates can be displayed with:

* "Currency Exchange Rates" **widget**,
* `[alg_cer_get_exchange_rate]` and/or `[alg_cer_get_saved_exchange_rate]` **shortcodes**,
* `alg_cer_get_exchange_rate()` and/or `alg_cer_get_saved_exchange_rate()` **PHP functions**.

Exchange rates are updated via [Fixer.io API](https://fixer.io/terms). Please note that you will have to get your free API key from [Fixer.io](https://fixer.io/product).

### &#128472; Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Changelog ==

= 1.2.0 - 23/12/2022 =
* Fix - Admin "Update rates now" button message fixed.
* Dev - Localisation - `load_plugin_textdomain()` function moved to the `init` action.
* Dev - Plugin is initialized on `plugins_loaded` now.
* Dev - Code refactoring.
* Tested up to: 6.1.
* Readme.txt updated.
* Deploy script added.

= 1.1.0 - 05/03/2020 =
* Fix - Default "Exchange Rates Server" value fixed (was `yahoo`).
* Fix - Fixer.io - API Endpoint fixed.
* Fix - "API key" option added.
* Fix - Non `EUR` base currency fixed.
* Dev - Widget - "Use saved rates" option added.
* Plugin URI updated.
* Author URI updated.
* Donate link removed.
* Tags updated.
* POT file uploaded.
* Tested up to: 5.3.

= 1.0.0 - 20/08/2017 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
