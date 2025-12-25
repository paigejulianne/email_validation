<?php

require_once __DIR__ . '/vendor/autoload.php';

use EmailValidation\EmailValidator;

$validator = new EmailValidator();

echo "--- Running Email Validator Examples ---\n\n";

// Example 1: A syntactically correct email with a domain that has no MX records.
// Expected result: Invalid
echo "1. Validating an email with a domain that has no MX records (e.g., example.com)...\n";
$emailToValidate1 = 'test@example.com';
echo "   - Email: $emailToValidate1\n";
if ($validator->validate($emailToValidate1)) {
    echo "   - Result: Email address is valid.\n\n";
} else {
    echo "   - Result: Email address is invalid, as expected, because example.com has no MX records.\n\n";
}

// Example 2: A syntactically correct email, but one that does not actually exist.
// Expected result: Invalid
echo "2. Validating a syntactically correct email that does not exist...\n";
$emailToValidate2 = 'non-existent-email@gmail.com';
echo "   - Email: $emailToValidate2\n";
if ($validator->validate($emailToValidate2)) {
    echo "   - Result: Email address is valid.\n\n";
} else {
    echo "   - Result: Email address is invalid, as expected, because the SMTP server rejected it (user does not exist).\n\n";
}

echo "--- Validation examples complete. ---";
