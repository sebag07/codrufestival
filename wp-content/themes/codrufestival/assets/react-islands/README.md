# React Islands

This theme ships React components as prebuilt static assets. The production server does not need Node.js.

Build locally before deploying theme changes:

```bash
npm install --prefix app/public/wp-content/themes/codrufestival
npm run build --prefix app/public/wp-content/themes/codrufestival
```

Commit both the source files in `assets/react-islands/src` and the built files in `assets/react-islands/dist`.

Tailwind CSS is compiled into `assets/react-islands/dist/react-islands.css`. You can use Tailwind classes in:

- Theme PHP files: `*.php`, `templates/**/*.php`, `page-templates/**/*.php`, `template-parts/**/*.php`, `includes/**/*.php`
- React island files: `assets/react-islands/src/**/*.{js,jsx,ts,tsx}`

Tailwind preflight is disabled in `tailwind.config.cjs` so existing theme styles are not reset. After adding new Tailwind classes, rebuild before deploying.

Render an island from any PHP template:

```php
<?php
codrufestival_react_island('CountdownBadge', array(
    'targetDate' => '2026-08-28T00:00:00+03:00',
    'title' => 'CODRU Festival',
    'dayLabel' => 'days left',
));
?>
```

Add new components by exporting them from `assets/react-islands/src/main.jsx` through the `registry` object, then rebuild.
