<?php

return [
    'client_id' => env('XERO_CLIENT_ID'),
    'client_secret' => env('XERO_CLIENT_SECRET'),
    "encoded_header" => base64_encode(env('XERO_CLIENT_ID') . ":" . env('XERO_CLIENT_SECRET')),
    'scopes' => env('XERO_SCOPES', 'openid profile email accounting.transactions offline_access'),
];
