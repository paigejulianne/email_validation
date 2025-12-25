<?php

require __DIR__ . '/vendor/autoload.php';

use PaigeJulianne\EmailValidator\EmailValidator;
use PaigeJulianne\EmailValidator\Exceptions\EmailAddressFormatInvalid;
use PaigeJulianne\EmailValidator\Exceptions\EmailAddressHasNoMXRecords;
use PaigeJulianne\EmailValidator\Exceptions\EmailAddressUserDoesntExist;

if ($argc < 2) {
    echo "Usage: php validate_email.php <email>\n";
    exit(1);
}

$email = $argv[1];
$validator = new EmailValidator();

try {
    if ($validator->validate($email)) {
        echo "Email address is valid.\n";
    }
} catch (EmailAddressFormatInvalid $e) {
    echo "Error: Invalid email address format.\n";
    exit(1);
} catch (EmailAddressHasNoMXRecords $e) {
    echo "Error: No MX records found for the domain.\n";
    exit(1);
} catch (EmailAddressUserDoesntExist $e) {
    echo "Error: Email address does not exist.\n";
    exit(1);
} catch (\Exception $e) {
    echo "An unexpected error occurred: " . $e->getMessage() . "\n";
    exit(1);
}

