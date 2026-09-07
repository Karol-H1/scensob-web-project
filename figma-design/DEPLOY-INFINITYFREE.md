# Putting the three sites on InfinityFree

Free PHP + MySQL hosting, which is what these sites need. The code deploys
**unchanged** — the only file you create is `config.php`, once per site.

Everything here applies to any free PHP host; only the control panel differs.

---

## What you upload

Run this to build the package:

```
python build-upload-zip.py
```

It produces `scensob-upload-to-htdocs.zip`, whose contents go straight into the
web root. Inside:

```
index.html              redirects the bare domain to the group site
group/                  Home, About, Portfolio, Contact
it/                     Home, Services, About, Portfolio, Quote
transport-delivery/     Home, Services, About, Catalog, Contact
```

**Keep the three sites as sibling folders.** Every link between them is
relative — `../it/index.html`, `../transport-delivery/contact.html` — so moving
one of them to the root breaks the links in the other two.

The zip deliberately leaves out `config.php` (live credentials), `standalone/`
(local preview builds), `database/`, `build-standalone.py` and the `.md` notes.

---

## Step 1 — Create the account and subdomain

Sign up at infinityfree.com, create a hosting account, and take the free
subdomain it offers. Note the **control panel** login — it's separate from the
account login.

A new subdomain can take a while to start resolving. If the address doesn't
load at first, that's usually propagation rather than a mistake.

---

## Step 2 — Upload

The web root is the **`htdocs`** folder. Anything outside it isn't served.

Either use the panel's **File Manager** (upload the zip, extract it in place),
or connect over **FTP** with FileZilla using the FTP details from the panel.

Afterwards `htdocs` should contain `index.html` and the three site folders,
**not** a single folder holding all of them. A common slip is extracting into
`htdocs/scensob-upload-to-htdocs/`, which puts every URL one level too deep.

---

## Step 3 — Create the database

In the panel, open **MySQL Databases** and create one. Free hosting limits how
many you get, and **one database serves all three sites** — the three table
names don't clash, which is why they were built that way.

Copy down four values exactly as the panel shows them:

| | Notes |
| --- | --- |
| **Database name** | Usually prefixed, e.g. `if0_00000000_scensob` |
| **Username** | Usually the account id, also prefixed |
| **Password** | The one you set |
| **Host** | **Not `localhost`** — see below |

> **The host is the thing that catches people out.** On shared free hosting the
> database runs on a separate server, so the host is something like
> `sqlNNN.infinityfree.com`, not `localhost`. Copy it exactly from the panel.
> Getting this wrong produces "Could not save your enquiry" with a connection
> error in the logs.

---

## Step 4 — Create the tables

Open **phpMyAdmin** from the panel and select your new database. Go to the
**SQL** tab, paste in the whole of `scensob-all-tables.sql`, and run it.

You should end up with three tables:

- `group_enquiries`
- `it_quote_requests`
- `transport_enquiries`

---

## Step 5 — Create `config.php` — three times

Each site folder has a `config.sample.php`. For each of `group`, `it` and
`transport-delivery`, copy that file to `config.php` in the same folder and
fill in the four values from Step 3.

All three point at the **same** database:

```php
<?php

return [
    'host'     => 'sqlNNN.infinityfree.com',   // from the panel, not localhost
    'database' => 'if0_00000000_scensob',
    'username' => 'if0_00000000',
    'password' => 'your_database_password',
    'charset'  => 'utf8mb4',
];
```

Leave `charset` as `utf8mb4` — that's what lets `£`, accents and emoji save
correctly.

You can do this straight in the panel's File Manager: copy the sample, rename
the copy, edit it in the browser.

---

## Step 6 — Test

Open each site's form, fill it in properly, and submit.

- **Confirmation screen** → check phpMyAdmin; the row is there immediately.
- **Red error under the form** → see the table below.

Test all three. They're separate endpoints writing to separate tables, so one
working doesn't prove the others do.

| Message | Cause |
| --- | --- |
| "This form is not configured yet" | `config.php` missing from that site's folder |
| "Something went wrong sending that" | Wrong credentials, wrong host, or tables not created |
| Nothing happens at all | PHP not running — check the file is `.php` and inside `htdocs` |
| Page loads unstyled | `assets/` didn't upload, or the folder structure is one level too deep |

Free hosts often run a bot check on first visit. If a form fails once and then
works after reloading the page, that's what it was — worth knowing before you
demo it to anyone.

---

## Before you show it to anyone

**All the content is fake.** Every name, statistic, case study, office address
and staff photo came from the Figma comps — "6,400+ operatives placed", the
five leadership profiles, the six case studies. On a public URL under a real
company's name that reads as fact.

Two things worth doing:

1. Add `<meta name="robots" content="noindex, nofollow">` to each page's
   `<head>` so it stays out of search results. **Take it back out before the
   real site launches** — left in place it would keep the live site out of
   Google entirely, which is the opposite of what you want.
2. Check whoever owns the brand is happy for it to be online at all, even
   unlisted.

**Also:** free hosting is for demonstrating, not for running the real site. It
has traffic limits, no meaningful support, and accounts get suspended for
inactivity. When this goes live properly, use the real hosting — the steps are
the same, and `SETUP.md` in each site folder covers them.
