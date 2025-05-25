<?php
/*
|-----------------------------------------------------------------------
| SMTP Configuration
|-----------------------------------------------------------------------
*/

$smtp_config = [
    'host'     => 'localhost',
    'auth'     => false,
    'username' => 'aclc-tabulation@localhost.net',
    'password' => '123456',
    'secure'   => '',
    'port'     => 25,
    'debug'    => 0,
    'from'     => [
        'email' => 'aclc-tabulation@localhost.net',
        'name'  => 'ACLC Tabulation Team'
    ],
    'recipient_email' => 'aclc-tabulation@localhost.net',
    'options'  => [
        'ssl'  => [
            'verify_peer'       => false,
            'verify_peer_name'  => false,
            'allow_self_signed' => true
        ]
    ]
];
