# Email Validation Module

A PHP Composer module for robust email address validation. This module performs comprehensive checks, including syntax validation, MX (Mail Exchange) record lookup, and SMTP (Simple Mail Transfer Protocol) server verification to ensure an email address is not only well-formed but also deliverable.

## Features

-   **Syntax Validation**: Checks if the email address conforms to standard email format rules using `filter_var(FILTER_VALIDATE_EMAIL)`.
-   **MX Record Lookup**: Determines if the domain of the email address has valid MX records, indicating it can receive mail.
-   **SMTP Verification**: Connects to the domain's SMTP server to verify if the email address is accepted for delivery.

## Installation

To install this module, navigate to your project's root directory and run the following Composer command:

```bash
composer require email_validation/email_validation
```

## Usage

Here's how to use the `EmailValidator` class in your PHP project:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use EmailValidation\EmailValidator;

$validator = new EmailValidator();

echo "--- Running Email Validator Examples ---\\n\\n";

// Example 1: A syntactically correct email with a domain that has no MX records.
// Expected result: Invalid
echo "1. Validating an email with a domain that has no MX records (e.g., example.com)...";
$emailToValidate1 = 'test@example.com';
echo "   - Email: $emailToValidate1";
if ($validator->validate($emailToValidate1)) {
    echo "   - Result: Email address is valid.\\n\\n";
} else {
    echo "   - Result: Email address is invalid, as expected, because example.com has no MX records.\\n\\n";
}

// Example 2: A syntactically correct email, but one that does not actually exist.
// Expected result: Invalid
echo "2. Validating a syntactically correct email that does not exist...";
$emailToValidate2 = 'non-existent-email@gmail.com'; // Replace with a real non-existent email if testing
echo "   - Email: $emailToValidate2";
if ($validator->validate($emailToValidate2)) {
    echo "   - Result: Email address is valid.\\n\\n";
} else {
    echo "   - Result: Email address is invalid, as expected, because the SMTP server rejected it (user does not exist).\\n\\n";
}

echo "--- Validation examples complete. ---";
```

## License

This project is licensed under the MIT License - see the `LICENSE` file for details (if one were to be created).

```