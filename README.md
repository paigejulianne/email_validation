# Email Validation Module

A PHP Composer module for robust email address validation. This module performs comprehensive checks, including syntax validation, MX (Mail Exchange) record lookup, and SMTP (Simple Mail Transfer Protocol) server verification to ensure an email address is not only well-formed but also deliverable.

## Features

-   **Syntax Validation**: Checks if the email address conforms to standard email format rules using `filter_var(FILTER_VALIDATE_EMAIL)`.
-   **MX Record Lookup**: Determines if the domain of the email address has valid MX records, indicating it can receive mail.
-   **SMTP Verification**: Connects to the domain's SMTP server to verify if the email address is accepted for delivery.

## Installation

To install this module, navigate to your project's root directory and run the following Composer command:

```bash
composer require paigejulianne/email-validator
```

## Usage

To use the `EmailValidator` class, you first need to include the Composer autoloader. Then, instantiate the `EmailValidator` and call its `validate` method within a `try-catch` block to handle potential validation exceptions.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use PaigeJulianne\EmailValidator\EmailValidator;
use PaigeJulianne\EmailValidator\Exceptions\EmailAddressFormatInvalid;
use PaigeJulianne\EmailValidator\Exceptions\EmailAddressHasNoMXRecords;
use PaigeJulianne\EmailValidator\Exceptions\EmailAddressUserDoesntExist;

$validator = new EmailValidator();

$emailsToValidate = [
    'test@example.com', // Domain with no MX records (often used for examples)
    'non-existent-email@gmail.com', // Syntactically correct but user doesn't exist
    'valid-email@gmail.com', // Replace with a real, valid email for actual testing
    'invalid-format', // Syntactically incorrect
];

foreach ($emailsToValidate as $email) {
    echo "Validating: {$email}\n";
    try {
        if ($validator->validate($email)) {
            echo "  Result: '{$email}' is a valid email address.\n\n";
        }
    } catch (EmailAddressFormatInvalid $e) {
        echo "  Result: '{$email}' is invalid (Format Invalid): {$e->getMessage()}\n\n";
    } catch (EmailAddressHasNoMXRecords $e) {
        echo "  Result: '{$email}' is invalid (No MX Records): {$e->getMessage()}\n\n";
    } catch (EmailAddressUserDoesntExist $e) {
        echo "  Result: '{$email}' is invalid (User Does Not Exist): {$e->getMessage()}\n\n";
    } catch (\Exception $e) {
        echo "  Result: An unexpected error occurred for '{$email}': {$e->getMessage()}\n\n";
    }
}
```

## License

This project is licensed under the MIT License - see the `LICENSE` file for details (if one were to be created).

```