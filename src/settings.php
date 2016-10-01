<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'inovacaoiel',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // ORM Settings
        'orm' => [
            'url' => isset($_ENV['CLEARDB_DATABASE_URL']) ?
                $_ENV['CLEARDB_DATABASE_URL'] :
                'mysql://root:root@127.0.0.1:8889/inovacaoiel?reconnect=true' // Configure your development URL here
        ],

        // Mailer Settings
        'mailer' => [
            'debug' => 'true',

            'host' => 'smtp.gmail.com',
            'smtp-auth' => true,
            'username' => 'email',
            'password' => 'secret',
            'smtp-secure' => 'tls',
            'port' => 587,

            'mail-from' => 'sample@iel.com.br',
            'mail-from-name' => 'Inovação IEL',
            'reply-to' => 'sample@iel.com.br',
            'reply-to-name' => 'Inovação IEL'
        ]
    ],
];
