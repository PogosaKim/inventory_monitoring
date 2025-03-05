<?php
return [
    'driver' => env('MAIL_DRIVER', 'smtp'),
    'host' => env('MAIL_HOST', 'smtp.gmail.com'),
    'port' => env('MAIL_PORT', 587),
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'reuelmendoza29@gmail.com'),
        'name' => env('MAIL_FROM_NAME', 'Reuel'),
    ],
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME'), // Use Gmail email
    'password' => env('MAIL_PASSWORD'), // Use Gmail App Password
    'sendmail' => '/usr/sbin/sendmail -bs',
];
