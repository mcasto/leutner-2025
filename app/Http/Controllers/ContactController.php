<?php

namespace App\Http\Controllers;

use App\Mail\ContactMailer;
use App\Models\Contact;
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
            return ['status' => 'error', 'message' => 'Invalid contact information'];
        }

        $contact = Contact::create($validator->valid());

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

            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
