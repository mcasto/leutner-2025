<?php

use Castoware\Sendgrid;

require_once(dirname(__DIR__) . '/vendor/autoload.php');


function sendContact($db, $request, $util)
{
  $contact = $request->body;
  $contact->date = date("Y-m-d H:i");

  $db->query("INSERT INTO contacts %v", (array) $contact);

  if ($contact->subject != '') {
    $emailConfig = json_decode(file_get_contents($util->externalPath . '/email-config.json'));
    $to = $emailConfig->to;
    $toName = $emailConfig->toName;

    $sg = new Sendgrid();
    $sg->sendEmail($contact->email, $contact->name, $to, $toName, $contact->subject, $contact->body);
  }

  $chimpConfig = json_decode(file_get_contents($util->externalPath . '/mailchimp.json'));
  $apiKey = $chimpConfig->key;
  $server = $chimpConfig->server;
  $listID = $chimpConfig->listId;

  $status = $contact->join == 1 ? 'subscribed' : 'unsubscribed';

  $client = new MailchimpMarketing\ApiClient();

  $client->setConfig([
    'apiKey' => $apiKey,
    'server' => $server,
  ]);

  $util->success();

  try {
    $response = $client->lists->setListMember($listID, [
      "email_address" => $contact->email,
      "full_name" => $contact->name,
      "status_if_new" => $status,
    ]);

    $db->query("INSERT INTO mailchimp_responses %v", [
      'submitted_info' => json_encode($contact),
      'response' => json_encode($response),
      'date' => date("Y-m-d")
    ]);
  } catch (Exception $e) {
    $db->query("INSERT INTO mailchimp_responses %v", [
      'submitted_info' => json_encode($contact),
      'response' => $e->getMessage(),
      'date' => date("Y-m-d")
    ]);

    $util->fail($e->getMessage());
  }

  $util->success();
}
