<?php

return [
    'local' => [
        'inline' => base_path('FailureMapping/680AInline'),
        'output' => base_path('FailureMapping/680AOutput'),
        'backup' => base_path('FailureMapping/680ABackup'),
        '950A' => [
            'inline' => base_path('FailureMapping/950AInline'),
            'output' => base_path('FailureMapping/950AOutput'),
            'backup' => base_path('FailureMapping/950ABackup')
        ]
    ],

    'akj' => [
        'inline' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Inline',
        'output' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Output',
        'backup' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Backup',
        '950A' => [
            'inline' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'950AInline',
            'output' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'950AOutput',
            'backup' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'950ABackup'
        ]
    ],

    'takaoka' => [
        'inline' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Inline',
        'output' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Output',
        'backup' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Backup',
        '950A' => [
            'inline' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'950AInline',
            'output' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'950AOutput',
            'backup' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'950ABackup'
        ]
    ],

    'motomachi' => [
        'inline' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'680AInline',
        'output' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'680AOutput',
        'backup' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'680ABackup',
        '950A' => [
            'inline' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'950AInline',
            'output' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'950AOutput',
            'backup' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'950ABackup'
        ]
    ]
];