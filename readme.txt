=== Review Management Tool Widget Embedder ===
Contributors: pradeepps
Tags: testimonials, review-widget, review-management, social-proof, reputation-management
Requires at least: 5.8
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.1.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Plugin URI: https://blog.reviewmanagementtool.com/2025/07/how-to-easily-add-testimonials-to-your.html
Author URI: https://reviewmanagementtool.com/aboutUs

Embed testimonial widgets from ReviewManagementTool.com via shortcode or Gutenberg block. Supports iframe, dynamic JS, and static JS (CDN) methods.

== Description ==

The **Review Management Tool – Testimonial Embed** plugin lets you display testimonials from [ReviewManagementTool.com](https://reviewmanagementtool.com) anywhere on your WordPress site — using a shortcode or a Gutenberg block.

Choose your embed method:
- **Iframe (default):** safest and most compatible
- **Static JS (CDN):** loads a small, widget-specific JS file for fast rendering
- **Dynamic JS:** uses one shared loader script across widgets

This plugin sanitizes input, escapes output, and avoids any external network calls until you actually place a widget on a page.

**Highlights**
- Shortcode: `[rmtool]`
- Gutenberg block with sidebar controls
- Multiple widgets per page
- Lightweight and responsive

Ideal for agencies, SaaS, freelancers, coaches, consultants, and eCommerce sites relying on social proof.

== External services ==

This plugin connects to external services **only when** you embed a widget on a page. It may load assets and/or data from:

- **reviewmanagementtool.com** — used for the dynamic JS loader and iframe embed.
  - **What is sent/when:** When a page with a widget is visited, the browser requests the loader script or iframe. This inherently sends standard web request metadata (IP address, user agent, referrer) and the **Widget ID** in the URL to identify which testimonials to display.
  - **Policies:** Terms: https://reviewmanagementtool.com/terms-and-conditions • Privacy: https://reviewmanagementtool.com/privacy-policy

- **cdn.reviewmanagementtool.com** — used for the **Static JS** method (a widget-specific JS file served via CDN).
  - **What is sent/when:** When a page with a static widget is visited, the browser requests the widget’s JS file from the CDN. This also sends standard request metadata and the **Widget ID** appears in the file path.
  - **Policies:** Terms: https://reviewmanagementtool.com/terms-and-conditions • Privacy: https://reviewmanagementtool.com/privacy-policy

No requests are made on new installs until a widget is placed (via shortcode or block).

== Installation ==

1. In your WordPress dashboard, go to **Plugins → Add New**.
2. Search for **Review Management Tool – Testimonial Embed**.
3. Click **Install Now**, then **Activate**.
4. Open **Settings → Review Management Tool** for a quick start.

== How to Use ==

1. Create a widget at [ReviewManagementTool.com](https://reviewmanagementtool.com) and copy its **Widget ID**.
2. In WordPress, choose ONE method:

- **Iframe (default / safest):**  
  `[rmtool widget_id="YOUR_ID" type="iframe"]`

- **Static JS (fast, CDN file per widget):**  
  `[rmtool widget_id="YOUR_ID" type="static"]`

- **Dynamic JS (shared loader):**  
  `[rmtool widget_id="YOUR_ID" type="js"]`

Or insert the **Review Management Widget** block, then set **Widget ID** and **Method** in the block sidebar.

== Screenshots ==

1. Gutenberg block in WordPress editor
2. Settings page with quick guide
3. Embedded testimonial preview
4. Widget dashboard on ReviewManagementTool.com

== Source code and build ==

The unminified source for any compiled assets (e.g., `build/index.js` for the block editor) is publicly available:

- Source repository: https://github.com/praadsreview/rmt_wordpress
- Build steps:
  1) `npm ci`
  2) `npm run build`
  3) The compiled assets are written to `/build` and included in the plugin ZIP.

(If you prefer bundling source directly, include `/src` in the plugin ZIP and document build steps here.)

== Frequently Asked Questions ==

= What happens if I don’t specify a `widget_id`? =  
Nothing renders on the front end. Admins will see a friendly reminder to set a Widget ID.

= Which method should I choose? =  
- **Iframe**: most compatible; good default.  
- **Static JS**: fastest path for production (loads one small file from CDN per widget).  
- **Dynamic JS**: one shared loader script for multiple widgets.

= Can I use multiple widgets on the same page? =  
Yes. All three methods support multiple instances.

= Does this plugin load anything by default? =  
No. It loads assets from `reviewmanagementtool.com` or `cdn.reviewmanagementtool.com` **only** when you embed a widget on a page.

= Where do I get my Widget ID? =  
Log in to [ReviewManagementTool.com](https://reviewmanagementtool.com), open your widget, and copy the ID.

= Where can I find a full tutorial? =  
See: [How to Add Testimonials to WordPress](https://blog.reviewmanagementtool.com/2025/07/how-to-easily-add-testimonials-to-your.html)

= Who can I contact for support? =  
Email: [hello@reviewmanagementtool.com](mailto:hello@reviewmanagementtool.com)

== Privacy ==

This plugin makes outbound requests to `reviewmanagementtool.com` and `cdn.reviewmanagementtool.com` **only after** you embed a widget. No external requests are made by default on new installs.

== Changelog ==

= 1.1.2 =
* Addressed review items: contributor username, external services section with policy links, human-readable source link, and unique function prefixes.
* Added **Static JS** embed method (`type="static"`) and ensured no default remote calls.
* Improved sanitization, escaping, and i18n.

= 1.1.1 =
* Refactor to unique `revimato_` prefix. Documented external services and build.

= 1.1.0 =
* Added Static JS embed method and parity across block/shortcode.

= 1.0.1 =
* Added Quick Start guide and tutorial link.

= 1.0.0 =
* Initial release with shortcode and Gutenberg block support.
