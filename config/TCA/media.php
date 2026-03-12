<?php

return [
    'ctrl' => [
        'table' => 'media',
        'entity' => \ForwardFW\Entity\Media::class,
        'identityField' => 'id',
        'identityFieldPublic' => 'public_id',
        'crdate' => 'crdate',
        'tstamp' => 'tstamp',
    ],
    'columns' => [
        'id' => [
            'config' => [
                'type' => 'autoincrement',
                'readonly' => true,
            ],
        ],
        'public_id' => [
            'config' => [
                'type' => 'NanoID',
                'readonly' => true,
            ],
        ],
        'crdate' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'tstamp' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'filename' => [
            'config' => [
                'type' => 'input',
                'readonly' => true,
            ],
        ],
        'extension' => [
            'config' => [
                'type' => 'input',
                'readonly' => true,
            ],
        ],
        'mime_type' => [
            'config' => [
                'type' => 'input',
                'readonly' => true,
            ],
        ],
        'width' => [
            'config' => [
                'type' => 'input',
                'readonly' => true,
            ],
        ],
        'height' => [
            'config' => [
                'type' => 'input',
                'readonly' => true,
            ],
        ],
        'size' => [
            'config' => [
                'type' => 'input',
                'readonly' => true,
            ],
        ],
        'public_path' => [
            'config' => [
                'type' => 'input',
                'readonly' => true,
            ],
        ],
    ],
];
