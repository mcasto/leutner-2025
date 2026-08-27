<?php

namespace App\Http\Controllers;

use App\Mail\ContactMailer;
use App\Models\Contact;
use App\Models\ContactFailure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|required',
            'email' => 'email|required',
            'subject' => 'string|required',
            'body' => 'string|required',
        ]);

        if ($validator->fails()) {
            ContactFailure::create([
                'source' => 'backend',
                'reason' => $validator->errors()->first() ?: 'Invalid contact information',
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'body' => $request->input('body'),
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

        return ['status' => 'ok'];
    }
}
