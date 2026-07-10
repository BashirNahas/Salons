/*
 * Salons System — shared page behaviors.
 *
 * Small, dependency-free enhancements used across the whole app, wired
 * through data attributes so Blade templates contain no inline handlers:
 *
 *   data-confirm="…"    on a <form>  → native confirmation dialog before submit
 *   data-autosubmit     on a <select> → submits its form on change
 *
 * Listeners are delegated from the document, so markup added later
 * (pagination, Alpine templates) works without re-binding.
 */
(function () {
    'use strict';

    // Confirmation dialogs (destructive actions: delete, cancel appointment).
    document.addEventListener('submit', function (event) {
        var form = event.target.closest('form[data-confirm]');

        if (form && ! window.confirm(form.getAttribute('data-confirm'))) {
            event.preventDefault();
        }
    });

    // Auto-submitting filters (status/salon dropdowns above tables).
    document.addEventListener('change', function (event) {
        var control = event.target.closest('[data-autosubmit]');

        if (control && control.form) {
            control.form.submit();
        }
    });
})();
