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
                'mysql://root:root@db:3306/inovacaoiel?reconnect=true' // Configure your development URL here
        ],

        // Mailer Settings
        'mailer' => [
            'type' => 'smtp', // Can be 'sendmail' or 'smtp'
            'debug' => 'true',

            // SMTP settings. Fill if using 'type' => 'smtp'
            'host' => 'smtp.gmail.com',
            'smtp-auth' => true,
            'username' => 'gestaodainovacao@cni.org.br',
            'password' => 'Gi_iel16',
            'smtp-secure' => 'tls',
            'port' => 587,

            'mail-from' => 'gestaodainovacao@cni.org.br',
            'mail-from-name' => 'Inovação IEL',
            'reply-to' => 'gestaodainovacao@cni.org.br',
            'reply-to-name' => 'Inovação IEL'
        ]
    ],
];
