=== Review Management Tool ===
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

Embed testimonial widgets from ReviewManagementTool.com via shortcode. Loads a compact, widget-specific JS from CDN for fast rendering.

== Description ==

The **Review Management Tool – Testimonial Embed** plugin lets you display testimonials from [ReviewManagementTool.com](https://reviewmanagementtool.com) anywhere on your WordPress site — using a simple shortcode.

**How it works**
- You create a widget in ReviewManagementTool.com and get its **Widget ID**.
- The shortcode renders a container and enqueues a **static JS** file from your configured CDN base (default: `https://cdn.reviewmanagementtool.com/widget-static`), e.g. `testimonial-widget-{WIDGET_ID}.js`.
- No inline `<script>` tags — passes Plugin Check standards.

**Highlights**
- Shortcode: `[rmt_widget id="YOUR_ID"]`
- Multiple widgets per page
- Lightweight and responsive
- No external requests until a widget is actually embedded on a page
- Settings screen to set a default Widget ID and CDN Base URL

Ideal for agencies, SaaS, freelancers, coaches, consultants, and eCommerce sites relying on social proof.

== External services ==

This plugin connects to external services **only when** you embed a widget on a page. It may load assets and/or data from:

- **reviewmanagementtool.com** — used by your account outside of the plugin (e.g., where you create/manage widgets).  
  - **Policies:** Terms: https://reviewmanagementtool.com/terms-and-conditions • Privacy: https://reviewmanagementtool.com/privacy-policy

- **cdn.reviewmanagementtool.com** — used for the static JS method (a widget-specific JS file served via CDN).  
  - **What is sent/when:** When a page with a static widget is visited, the browser requests the widget’s JS file from the CDN. This sends standard web request metadata and the **Widget ID** appears in the file path.  
  - **Policies:** Terms: https://reviewmanagementtool.com/terms-and-conditions • Privacy: https://reviewmanagementtool.com/privacy-policy

No requests are made on new installs until a widget is placed via shortcode.

== Installation ==

1. In your WordPress dashboard, go to **Plugins → Add New**.
2. Search for **Review Management Tool** (or upload the ZIP).
3. Click **Install Now**, then **Activate**.
4. Open **Settings → Review Management Tool** to set a **Default Widget ID** and (optionally) a **CDN Base URL**.

== How to Use ==

1. Create a widget at [ReviewManagementTool.com](https://reviewmanagementtool.com) and copy its **Widget ID**.
2. Add the shortcode to any page or post:


- If you omit `id`, the plugin will use the **Default Widget ID** from the settings page.
- You can place multiple shortcodes/widgets on a single page.

== Screenshots ==

1. Settings page with quick guide
2. Embedded testimonial preview
3. Widget dashboard on ReviewManagementTool.com

== Source code and build ==

- Source repository: https://github.com/praadsreview/rmt_wordpress
- Build steps (if you use a build for admin assets):
  1) `npm ci`
  2) `npm run build`
  3) The compiled assets are written to `/build` and included in the plugin ZIP.

== Frequently Asked Questions ==

= What happens if I don’t specify an `id`? =
Nothing renders unless you have set a **Default Widget ID** in Settings. If neither is provided, the shortcode outputs a friendly message.

= Can I use multiple widgets on the same page? =
Yes. Add the shortcode multiple times with different IDs.

= Does this plugin load anything by default? =
No. It only loads the widget JS from your configured CDN when a page actually contains the shortcode.

= Where do I get my Widget ID? =
Log in to [ReviewManagementTool.com](https://reviewmanagementtool.com), open your widget, and copy the ID.

= Where can I find a full tutorial? =
See: [How to Add Testimonials to WordPress](https://blog.reviewmanagementtool.com/2025/07/how-to-easily-add-testimonials-to-your.html)

= Who can I contact for support? =
Email: [hello@reviewmanagementtool.com](mailto:hello@reviewmanagementtool.com)

== Privacy ==

This plugin makes outbound requests to `cdn.reviewmanagementtool.com` **only after** you embed a widget via shortcode. No external requests are made by default on new installs.

== Changelog ==

= 1.1.2 =
* Fix: Documentation alignment and Stable tag updated to match plugin header.
* Improvement: Clear instructions for shortcode usage and settings page.
* Internal: Maintains no-inline-script rule; enqueues static widget JS via CDN.

= 1.1.1 =
* Internal refactor and documentation improvements.

= 1.1.0 =
* Added settings for Default Widget ID and CDN Base URL.

= 1.0.0 =
* Initial public release with shortcode support.
* Static JS (CDN) method for fast, isolated loads.
* Sanitization, escaping, and i18n foundations.
* No external requests unless a widget is embedded.

== Upgrade Notice ==

= 1.1.2 =
Recommended update to ensure the readme Stable tag matches the plugin version and to improve usage docs.
