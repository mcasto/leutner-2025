<?php

namespace App\Http\Controllers;

use App\Mail\ContactMailer;
use App\Models\Contact;
use App\Models\ContactFailure;
use App\Models\MailchimpResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use MailchimpMarketing\ApiClient;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|required',
            'email' => 'email|required',
            'subject' => 'string|required',
            'body' => 'string|required',
            'join' => 'boolean|required'
        ]);

        if ($validator->fails()) {
            ContactFailure::create([
                'source' => 'backend',
                'reason' => $validator->errors()->first() ?: 'Invalid contact information',
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'body' => $request->input('body'),
                'join' => $request->boolean('join'),
            ]);

            return ['status' => 'error', 'message' => 'Invalid contact information'];
        }

        $data = $validator->valid();
        $hash = Contact::hashFor($data['email'], $data['subject'], $data['body']);

        $isDuplicate = Contact::where('hash', $hash)
            ->where('created_at', '>=', now()->subHour())
            ->exists();

        if ($isDuplicate) {
            ContactFailure::create([
                'source' => 'backend',
                'reason' => 'Duplicate submission',
                'name' => $data['name'],
                'email' => $data['email'],
                'subject' => $data['subject'],
                'body' => $data['body'],
                'join' => $data['join'],
            ]);

            return ['status' => 'error', 'message' => 'This appears to be a duplicate message of one already submitted.'];
        }

        $contact = Contact::create([...$data, 'hash' => $hash]);

        // send email about contact
        try {
            Mail::to(config('mail.to.address'))
                ->send(new ContactMailer($contact));
        } catch (Exception $e) {
            logger()->error($e);
            return ['status' => 'error', 'message' => 'Unable to send contact email'];
        }

        $status = $contact->join ? 'subscribed' : 'unsubscribed';

        $client = new ApiClient();

        $client->setConfig([
            'apiKey' => config('app.mailchimp.key'),
            'server' => config('app.mailchimp.server')
        ]);

        $subscriberHash = md5(strtolower($contact->email));

        try {
            $response = $client->lists->setListMember(config('app.mailchimp.list_id'), $subscriberHash, [
                'email_address' => $contact->email,
                'status_if_new' => $status, // or 'pending' for double opt-in
                'status' => $status, // Update existing member status
            ]);

            MailchimpResponse::create([
                'submitted_info' => $contact->toArray(),
                'response' => $response,
            ]);

            return ['status' => 'ok'];
        } catch (Exception $e) {
            MailchimpResponse::create([
                'submitted_info' => $contact->toArray(),
                'response' => $e->getMessage(),
            ]);

            // Mailchimp is a secondary integration - the contact is already
            // saved and the notification email already sent, so a Mailchimp
            // failure shouldn't be surfaced to the person submitting the form.
            return ['status' => 'ok'];
        }
    }
}
