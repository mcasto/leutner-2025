<div>
    <div>
        From: <a href="mailto:{{ $contact->email }}"> {{ $contact->name }} <{{ $contact->email }}></a>
    </div>
    <div>
        Subject: {{ $contact->subject }}
    </div>
    <p>
        {{ $contact->body }}
    </p>
    <div>
        Received: {{ $contact->created_at }}
    </div>
</div>
