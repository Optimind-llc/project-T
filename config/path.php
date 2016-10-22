<?php

return [
    'local' => [
        'inline' => base_path('FailureMapping/Inline'),
        'output' => base_path('FailureMapping/Output'),
    ],

    'akj' => [
        'inline' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Inline',
        'output' => 'C:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Output'
    ],

    'takaoka' => [
        'inline' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Inline',
        'output' => 'D:'.DIRECTORY_SEPARATOR.'FailureMapping'.DIRECTORY_SEPARATOR.'Output'
    ]
];