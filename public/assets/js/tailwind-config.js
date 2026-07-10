/*
 * Tailwind Play CDN configuration.
 *
 * Must be loaded BEFORE the cdn.tailwindcss.com script. The per-salon
 * brand palette is dynamic (owners pick a brand color for their public
 * page), so the server exposes it as JSON in the <html data-brand-palette>
 * attribute and this file — the only place that configures Tailwind —
 * reads it from there. No inline scripts needed in the Blade layouts.
 */
(function () {
    'use strict';

    var palette = {};

    try {
        palette = JSON.parse(document.documentElement.getAttribute('data-brand-palette') || '{}');
    } catch (e) {
        palette = {};
    }

    window.tailwind = window.tailwind || {};
    window.tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['Inter', 'Cairo', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Tahoma', 'sans-serif'],
                },
                colors: {
                    brand: palette,
                },
                boxShadow: {
                    card: '0 1px 2px 0 rgb(9 9 11 / 0.04), 0 1px 3px 0 rgb(9 9 11 / 0.06)',
                },
            },
        },
    };
})();
