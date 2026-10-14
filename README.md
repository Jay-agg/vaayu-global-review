# Vaayu Global website — design review

A review copy of the Vaayu Global website (Spexo "Consultive" template with the Vaayu
Global colours, copy and imagery), runnable in the browser with WordPress Playground.

**Open the site:**
https://playground.wordpress.net/?mode=seamless&blueprint-url=https://raw.githubusercontent.com/Jay-agg/vaayu-global-review/main/blueprint.json

The first load takes a minute: WordPress, the theme, its plugins and this snapshot are
downloaded into your browser. Each visitor gets a private copy that runs only in their
browser; changes are not saved or shared.

| File | Contents |
| --- | --- |
| `blueprint.json` | Playground setup: imports the snapshot, installs Spexo, Elementor, Redux Framework, Spexo Addons and Contact Form 7 from WordPress.org, then points the content at the session's address. |
| `vaayu-site.zip` | Snapshot of `wp-content`: database, uploads and the Vaayu Global child theme. |
| `rewrite-urls.php` | The address rewrite run by the blueprint (also embedded in it). |

Draft content: the six Insights articles are placeholders, and contact details and the
LinkedIn link are still to be added. The inquiry form does not send email in Playground.
