# Codru Festival

This repository contains the WordPress site for Codru Festival. The repository root is the WordPress web root.

The custom theme lives at:

```text
wp-content/themes/codrufestival
```

## Theme Frontend

The theme uses React Islands for interactive components rendered from PHP templates and mounted in the browser.

- React source lives in `wp-content/themes/codrufestival/assets/react-islands/src`.
- React components live in `wp-content/themes/codrufestival/assets/react-islands/src/components`.
- Components are registered in `wp-content/themes/codrufestival/assets/react-islands/src/main.jsx`.
- Built browser assets live in `wp-content/themes/codrufestival/assets/react-islands/dist`.
- WordPress enqueues the built JS and CSS from `wp-content/themes/codrufestival/functions.php`.
- PHP templates render islands with `codrufestival_react_island()`.

Currently registered islands:

- `ArtistExpandableCards`
- `BrandCultureCards`
- `CountdownBadge`
- `NewsletterSignupTeaser`

## Install And Build

Production does not need Node.js. Build the React Islands locally and commit both the source files and the generated `dist` files.

From this repository root:

```bash
npm install --prefix wp-content/themes/codrufestival
npm run build --prefix wp-content/themes/codrufestival
```

For local watch builds:

```bash
npm run dev --prefix wp-content/themes/codrufestival
```

From the theme root:

```bash
cd wp-content/themes/codrufestival
npm install
npm run build
npm run dev
```

The committed build outputs are:

```text
wp-content/themes/codrufestival/assets/react-islands/dist/react-islands.js
wp-content/themes/codrufestival/assets/react-islands/dist/react-islands.css
```

Keep `node_modules/` uncommitted.

## Updating An Island

1. Create or update a component in `wp-content/themes/codrufestival/assets/react-islands/src/components`.
2. Register the component in the `registry` object in `wp-content/themes/codrufestival/assets/react-islands/src/main.jsx`.
3. Render it from PHP with `codrufestival_react_island()`.
4. Run `npm run build --prefix wp-content/themes/codrufestival`.
5. Commit the updated source and `wp-content/themes/codrufestival/assets/react-islands/dist` files together.

Example PHP usage:

```php
<?php
codrufestival_react_island('CountdownBadge', array(
    'targetDate' => '2026-08-28T00:00:00+03:00',
    'title' => 'CODRU Festival',
    'dayLabel' => 'days left',
));
?>
```

## Tailwind CSS

Tailwind is compiled into `wp-content/themes/codrufestival/assets/react-islands/dist/react-islands.css` by the Vite build. Tailwind preflight is disabled in `wp-content/themes/codrufestival/tailwind.config.cjs` so it does not reset the existing WordPress theme styles.

After adding or changing Tailwind classes in PHP or React, rebuild locally and commit the updated CSS output. If classes are generated dynamically and Tailwind cannot detect them, make the class names explicit in source or add them to the safelist.

## CPanel Deployment

For CPanel, either point the domain document root at this repository root or deploy the contents of this repository directly into `public_html`.

Before deploying theme changes, run locally:

```bash
npm run build --prefix wp-content/themes/codrufestival
```

Production also needs:

- A valid production `wp-config.php`.
- The production database imported and connected.
- The production `wp-content/uploads` directory.
- Required plugins under `wp-content/plugins`.
- Working `.htaccess` and permalink settings.

Do not rely on Node.js, Vite, or `node_modules` on the production server.
