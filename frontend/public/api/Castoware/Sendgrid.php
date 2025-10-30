<?php
/* composer require sendgrid/sendgrid */
/* make sure to set up & verify sender email at https://app.sendgrid.com/settings/sender_auth */

namespace Castoware;

use Exception;

class Sendgrid
{
  private $key, $cipher, $keyFile;

  function __construct($key = null)
  {
    $util = new Util;

    $this->keyFile = $util->externalPath . '/sendgrid.key';
    $this->key = $key ?? bin2hex(openssl_random_pseudo_bytes(256));
    $this->cipher = "aes-256-gcm";
  }

  function encrypt($plaintext)
  {
    $cipher = $this->cipher;
    $ivlen = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext = openssl_encrypt($plaintext, $cipher, $this->key, $options = 0, $iv, $tag);
    return $this->key . bin2hex($tag) . bin2hex($iv) . $ciphertext;
  }

  function decrypt($cipherHash)
  {
    $key = substr($cipherHash, 0, 512);
    $tag = hex2bin(substr($cipherHash, 512, 32));
    $iv = hex2bin(substr($cipherHash, 544, 24));
    $ciphertext = substr($cipherHash, 568);

    return openssl_decrypt($ciphertext, $this->cipher, $key, $options = 0, $iv, $tag);
  }

  function setupKeyFile($apiKey)
  {
    $hash = $this->encrypt($apiKey);
    file_put_contents($this->keyFile, $hash);
  }

  function apiKey()
  {
    return $this->decrypt(file_get_contents($this->keyFile));
  }

  function sendEmail($replyTo, $replyToName, $to, $toName, $subject, $body)
  {
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom('contacts@castoware.com', 'CastoWare Development');
    $email->setReplyTo($replyTo, $replyToName);
    $email->setSubject($subject);
    $email->addTo($to, $toName);
    $email->addContent('text/html', $body);

    $sendgrid = new \SendGrid($this->apiKey());

    try {
      $response = $sendgrid->send($email);

      $sendStatus = [
        'statusCode' => $response->statusCode(),
        'headers' => $response->headers(),
        'body' => $response->body()
      ];

      return $sendStatus;
    } catch (Exception $e) {
      error_log(print_r($e, true));
      return false;
    }
  }
}

/*
  1. get api key from sendgrid
  2. uncomment following code
  3. set $apiKey = api key
  4. run this script
  5. recomment code below
*/

// $apiKey="";
// $crypt = new sendgrid();
// $crypt->setupKeyFile($apiKey, $fileLocation);
