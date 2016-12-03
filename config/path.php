<?php

return [
    'local' => [
        'inline' => base_path('FailureMapping/Inline'),
        'output' => base_path('FailureMapping/Output'),
        'backup' => base_path('FailureMapping/Backup'),
    ],

    'akj' => [
        'inline' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Inline',
        'output' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Output',
        'backup' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Backup'
    ],

    'takaoka' => [
        'inline' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Inline',
        'output' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Output',
        'backup' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Backup'
    ]
];