# Connecting this site to your database

The site is already written to talk to MySQL. **You don't need to edit any of
the site's code** — all the database details live in one file, `config.php`,
which you create once. Everything else stays exactly as it is.

## What the hosting needs

- **PHP 8.0 or newer** with the `pdo_mysql` extension (standard on virtually
  all PHP hosting — nothing unusual required)
- **MySQL 5.7+ or MariaDB 10.2+**

If you're on typical shared hosting (cPanel, Plesk, GoDaddy, Bluehost,
Namecheap, IONOS…), both are already there.

---

## Step 1 — Create the database and its table

In your hosting control panel, find **MySQL Databases** and create:

- a **database** (any name — e.g. `scensob_transport`)
- a **user**, with a strong password
- then **add that user to the database** with all privileges

Write down the four values you'll need next: **host, database name, username,
password**.

Now create the table. In **phpMyAdmin** (in your control panel), select the
database you just made, open the **SQL** tab, paste in the contents of
`database/schema.sql` from this folder, and run it.

You should end up with one table called `transport_enquiries`.

> If you have command-line access instead, this does the same thing:
> ```
> mysql -u YOUR_USER -p YOUR_DATABASE < database/schema.sql
> ```

---

## Step 2 — Create `config.php`

In this folder there's a file called **`config.sample.php`**. Make a copy of it
named **`config.php`**, and fill in the four values from Step 1:

```php
<?php

return [
    'host'     => 'localhost',
    'database' => 'scensob_transport',
    'username' => 'your_db_user',
    'password' => 'your_db_password',
    'charset'  => 'utf8mb4',
];
```

That's the only file you edit. A few notes:

- **`host`** is `localhost` on most shared hosting. Some hosts use a specific
  server name instead — check the MySQL section of your control panel; if it
  shows something like `mysql.yourhost.com`, use that.
- **Leave `charset` as `utf8mb4`.** That's what lets names, `£`, accented
  characters and emoji save correctly.
- **Keep `config.php` private.** It holds your real password. Don't email it,
  don't put it in a public code repository, and don't include it in a zip you
  share. `config.sample.php` is the one that's safe to share.

---

## Step 3 — Upload

Upload everything in this folder to your web space, **except**:

- `standalone/` — preview-only copies (see the note at the bottom)
- `build-standalone.py` — a developer tool, not needed on the server

Make sure `submit.php`, `config.php` and the `assets/` folder all sit together
in the same directory as `contact.html`.

---

## Step 4 — Test it

Open the Contact page on your live site, fill the form in properly, and submit.

- **You see the confirmation screen** → open phpMyAdmin and look in
  `transport_enquiries`. Your submission should be there straight away; there's
  no delay or queue.
- **You see a red error message under the form** → the site couldn't reach the
  database. See troubleshooting below.

---

## If something goes wrong

**"This form is not configured yet"**
`config.php` is missing or wasn't uploaded. Check it sits next to `submit.php`.

**"Something went wrong sending that"**
The credentials in `config.php` don't match the database, or the table doesn't
exist yet. Re-check Step 1 and Step 2. Your host's **error log** (in the control
panel) will show the exact reason.

**The form submits but nothing appears in the table**
Almost always means an old copy of the page is being served. Hard-refresh the
page (Ctrl+F5), and make sure you uploaded the updated `assets/js/site.js`.

**Nothing happens at all when you click submit**
The host may not be running PHP for this folder — confirm the site is on a
PHP-enabled plan and that you're opening it over `http://` or `https://`, not
as a file from disk.

---

## About the `standalone/` folder

Those are single-file copies of each page, built so they can be opened straight
from a zip with no web server at all. Handy for previewing the design, but
**the form in them cannot submit** — saving to a database needs a real PHP
server, which those files deliberately don't rely on. Always use the top-level
files for the live site.
