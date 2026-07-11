# Deployment package

`salons-deploy.zip` is a ready-to-upload build for shared hosting
(DirectAdmin/cPanel). It contains **no secrets** — you provide them at
install time.

What's inside:

- `vendor/` pre-installed with `composer install --no-dev --optimize-autoloader`
  (no Composer needed on the server)
- `.env` pre-set to `APP_ENV=production`, `APP_DEBUG=false`; `APP_KEY` is
  empty (the installer generates it), `SETUP_TOKEN` is empty (you choose one)
- Root `.htaccess` that routes all requests into `public/`, so the extracted
  contents can be placed directly in `public_html`

## Install steps (summary)

1. Upload the zip and extract; move the contents of the extracted `salons/`
   folder into `public_html/`.
2. Create a MySQL database + user in the hosting panel.
3. Edit `.env`: set `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, and set
   `SETUP_TOKEN` to a long random string of your choice.
4. Visit `https://<central-domain>/setup/<YOUR_TOKEN>?seed=1` — this generates
   the APP_KEY, runs migrations, seeds demo data, and links storage.
5. Delete the `SETUP_TOKEN` line from `.env` to disable the installer.

See the main README for DNS/subdomain configuration.
