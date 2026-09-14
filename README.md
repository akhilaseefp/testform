# Test Form

A simple **contact form** built with plain **PHP** and **MySQL**. When a user submits the form, the data is saved to the database and an email notification is sent.

## Tech Stack
- **Language:** PHP (procedural, no framework)
- **Database:** MySQL (`formdb`, schema in `contact_form.sql`)
- **Email:** PHP `mail()` function
- **Frontend:** Plain HTML (no Bootstrap or CSS framework — the form is bare HTML)

## What It Does
- **`index.php`** — renders the contact form (full name, phone, email, subject, message).
- **`process_form.php`** — validates the submitted data, inserts it into the `contact_form` table, and sends an email notification.

## How It Works
1. The form in `index.php` POSTs to `process_form.php`.
2. `process_form.php` validates each field:
   - Full name — required, and must match `/^[a-zA-Z]*$/` (letters only — **spaces are NOT allowed**, despite the common expectation for names).
   - Phone number — required (checked with `empty()`), then validated with `/^[0-9]*$/` (digits only).
   - Email — required, validated with `filter_var(FILTER_VALIDATE_EMAIL)`.
   - Subject and message — required.
3. If validation passes, a token is generated with `md5(uniqid(rand(), true))`.
4. The data is inserted into MySQL using a **plain `INSERT` statement with string interpolation** (see Security Issues below).
5. An email is sent with `mail()` to `akhilaseefp@gmail.com` containing the form details.
6. On success, it prints "Form Submitted successfully"; on error, it prints the MySQL error.

**Note:** All input passes through the `test_input()` function, which applies `stripslashes()` and **`htmlspecialchars()`** before database insertion. This transforms the stored data (e.g., `&` becomes `&amp;`), which may cause double-encoding issues if the data is later retrieved and displayed without decoding.

## File Structure
```
testform/
├── index.php          # The contact form
├── process_form.php   # Validation, DB insert, email send
└── contact_form.sql   # Schema (contact_form table)
```

## Running
Requires a MySQL database (create `formdb` and run `contact_form.sql`) and a working PHP mail setup.

## Security Issues (Critical — do not use in production)
1. **SQL Injection — CRITICAL:** `/c/htdocs/testform/process_form.php` inserts user input directly into a SQL string using string interpolation:
   ```php
   $sql = "INSERT INTO contact_form(...) VALUES ('$full_name','$phone_number',...)";
   ```
   Neither `htmlspecialchars()` nor `stripslashes()` prevents SQL injection. A single quote in any field will break the query, and an attacker could craft SQL injection payloads. **This must be fixed with prepared statements (`mysqli_prepare` / `mysqli_stmt_bind_param`) before production use.**
2. **Email Header Injection — MODERATE:** The `From:` email header is set directly to the submitted `$email` value (`headers= "From: $email"`). While `filter_var(FILTER_VALIDATE_EMAIL)` provides basic validation, a fixed sender address is best practice.
3. **Error Message Disclosure — MODERATE:** On database error, the full SQL query and MySQL error are printed directly to the user (`echo "Error :". $sql ."<br>" .$conn->error`), leaking database structure and query details to end users.
4. **Weak Token Generation:** `md5(uniqid(rand(), true))` uses MD5 and the non-cryptographically-secure `rand()` function. If this token is ever used for verification (e.g., email confirmation links), it is trivially guessable.
5. **No CSRF Protection:** The form has no CSRF token, making it vulnerable to cross-site request forgery attacks.
