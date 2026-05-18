# React Islands

This directory contains the React Islands used by the Codru Festival WordPress theme.

React Islands are small React components rendered from PHP templates. WordPress outputs a placeholder `div` with `data-react-island` and JSON encoded `data-props`; the browser bundle mounts the matching registered React component on page load and when matching nodes are added later.

Production does not run Node.js. Build locally and commit both the source files and the generated `dist` files.

## Directory Layout

```text
assets/react-islands/
+-- dist/
|   +-- react-islands.css
|   +-- react-islands.js
+-- src/
    +-- components/
    +-- main.jsx
    +-- styles.css
```

Important theme files:

- `functions.php` enqueues `assets/react-islands/dist/react-islands.js` and `assets/react-islands/dist/react-islands.css` when they exist.
- `functions.php` defines `codrufestival_react_island()`.
- `vite.config.js` builds the browser bundle into `assets/react-islands/dist`.
- `tailwind.config.cjs` controls Tailwind scanning, theme tokens, safelist, and disabled preflight.
- `package.json` defines the `build` and `dev` commands.

## Commands

From the repository root:

```bash
npm install --prefix app/public/wp-content/themes/codrufestival
npm run build --prefix app/public/wp-content/themes/codrufestival
```

For watch builds from the repository root:

```bash
npm run dev --prefix app/public/wp-content/themes/codrufestival
```

From the theme root:

```bash
npm install
npm run build
npm run dev
```

The `build` command writes:

- `assets/react-islands/dist/react-islands.js`
- `assets/react-islands/dist/react-islands.css`

## Adding Or Updating Islands

1. Create or update a component in `assets/react-islands/src/components`.
2. Import and register it in the `registry` object in `assets/react-islands/src/main.jsx`.
3. Render it from PHP with `codrufestival_react_island()`.
4. Run `npm run build`.
5. Commit the updated source and `assets/react-islands/dist` files together.

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

Currently registered islands:

- `ArtistExpandableCards`
- `BrandCultureCards`
- `CountdownBadge`
- `NewsletterSignupTeaser`

`ExpandableCard` is a shared React component used by registered islands; it is not mounted directly from PHP.

## Props

Pass all dynamic data from PHP through the `codrufestival_react_island()` props array. The helper JSON encodes the props into `data-props`, and `src/main.jsx` parses them before rendering the React component.

Keep props serializable. Avoid passing DOM selectors or relying on global state when a value can be passed directly from PHP.

## Tailwind CSS

Tailwind CSS is imported from `src/styles.css` and compiled into `assets/react-islands/dist/react-islands.css`.

Tailwind classes can be used in:

- `*.php`
- `templates/**/*.php`
- `page-templates/**/*.php`
- `template-parts/**/*.php`
- `includes/**/*.php`
- `assets/react-islands/src/**/*.{js,jsx,ts,tsx}`

Tailwind preflight is disabled in `tailwind.config.cjs` so existing WordPress theme styles are not reset. After adding or changing Tailwind classes in PHP or React, rebuild before deploying and commit the updated CSS output.

If classes are built dynamically and Tailwind cannot detect them statically, make the class names explicit in source or add them to the `safelist` in `tailwind.config.cjs`.

## Deployment Checklist

- Run `npm run build --prefix app/public/wp-content/themes/codrufestival`.
- Confirm `assets/react-islands/dist/react-islands.js` exists.
- Confirm `assets/react-islands/dist/react-islands.css` exists when styles are imported.
- Commit source and `dist` files together.
- Keep `node_modules/` uncommitted.
