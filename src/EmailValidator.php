<?php

namespace PaigeJulianne\EmailValidator;

use PaigeJulianne\EmailValidator\Exceptions\EmailAddressFormatInvalid;
use PaigeJulianne\EmailValidator\Exceptions\EmailAddressHasNoMXRecords;
use PaigeJulianne\EmailValidator\Exceptions\EmailAddressUserDoesntExist;

class EmailValidator
{
    /**
     * @var string
     */
    protected $email;

    /**
     * @var array
     */
    protected $domains;

    /**
     * @var string
     */
    protected $fromUser = 'user';

    /**
     * @var string
     */
    protected $fromHost = 'localhost';

    /**
     * @var array
     */
    protected $mxHosts;

    /**
     * @var resource
     */
    protected $socket;

    /**
     * @var int
     */
    protected $maxConnectionTimeout = 30;

    /**
     * @var int
     */
    protected $maxStreamTimeout = 5;

    /**
     * Validate email address
     *
     * @param string $email
     * @return bool
     * @throws EmailAddressFormatInvalid
     * @throws EmailAddressHasNoMXRecords
     * @throws EmailAddressUserDoesntExist
     */
    public function validate(string $email): bool
    {
        $this->email = $email;

        if (!$this->validateSyntax()) {
            throw new EmailAddressFormatInvalid('Invalid email address format');
        }

        if (!$this->lookupMxRecords()) {
            throw new EmailAddressHasNoMXRecords('No MX records found for the domain');
        }

        return $this->validateSmtp();
    }

    /**
     * Validate email address syntax
     *
     * @return bool
     */
    protected function validateSyntax(): bool
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Lookup MX records for the domain
     *
     * @return bool
     */
    protected function lookupMxRecords(): bool
    {
        list(, $domain) = explode('@', $this->email);
        $this->domains[] = $domain;

        return getmxrr($domain, $this->mxHosts);
    }

    /**
     * Validate email address via SMTP
     *
     * @return bool
     * @throws EmailAddressUserDoesntExist
     */
    protected function validateSmtp(): bool
    {
        foreach ($this->mxHosts as $host) {
            $this->socket = @fsockopen($host, 25, $errno, $errstr, $this->maxConnectionTimeout);

            if (!$this->socket) {
                continue;
            }

            stream_set_timeout($this->socket, $this->maxStreamTimeout);
            $this->readSmtpResponse();

            $this->sendCommand('EHLO ' . $this->fromHost);
            $response = $this->readSmtpResponse();

            if (substr($response, 0, 3) != '250') {
                $this->sendCommand('HELO ' . $this->fromHost);
                $this->readSmtpResponse();
            }

            $this->sendCommand('MAIL FROM: <test@test.com>');
            $this->readSmtpResponse();

            $this->sendCommand('RCPT TO: <' . $this->email . '>');
            $response = $this->readSmtpResponse();

            $this->sendCommand('QUIT');
            fclose($this->socket);

            if (substr($response, 0, 3) == '250') {
                return true;
            }
        }

        throw new EmailAddressUserDoesntExist('User does not exist');
    }

    /**
     * Send SMTP command
     *
     * @param string $command
     */
    protected function sendCommand(string $command)
    {
        fputs($this->socket, $command . "\r\n");
    }

    /**
     * Read SMTP response
     *
     * @return string
     */
    protected function readSmtpResponse(): string
    {
        $response = '';
        while (substr($response, 3, 1) != ' ') {
            $response = fgets($this->socket, 256);
        }
        return $response;
    }
}
