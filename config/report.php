<?php

return [
    'A4' => [
        'xmax' => 210,
        'x0' => 4,
        'x1' => 106,
        'y0' => 8,
        'y1' => 16,
        'y2' => 28,
        'y3' => 36,
        'th' => 5,
        'header' => [
            'vehicle' => 0,
            'process' => 30,
            'ition' => 64,
            'date' => 108,
            'tyoku' => 134,
            'now' => 157,
        ],
    ],
    'A3' => [
        'xmax' => 420,
        'x0' => 10,
        'x1' => 36,
        'y0' => 10,
        'y1' => 20,
        'y1_ana' => 26,
        'y2' => 33,
        'th' => 6.2,
        'header' => [
            'vehicle' => 0,
            'process' => 40,
            'ition' => 76,
            'part' => 120,
            'date' => 160,
            'tyoku' => 190,
            'now' => 350,
        ],
    ],
    'timeChunks' => [
        [
            'label' => '6:30〜7:30',
            'start' => ['H' => 6, 'i' => 30],
            'end' => ['H' => 7, 'i' => 30],
        ],[
            'label' => '7:30〜8:30',
            'start' => ['H' => 7, 'i' => 30],
            'end' => ['H' => 8, 'i' => 40],
        ],[
            'label' => '8:40〜9:40',
            'start' => ['H' => 8, 'i' => 40],
            'end' => ['H' => 9, 'i' => 40],
        ],[
            'label' => '9:40〜10:40',
            'start' => ['H' => 9, 'i' => 40],
            'end' => ['H' => 11, 'i' => 25],
        ],[
            'label' => '11:25〜12:25',
            'start' => ['H' => 11, 'i' => 25],
            'end' => ['H' => 12, 'i' => 25],
        ],[
            'label' => '12:25〜13:25',
            'start' => ['H' => 12, 'i' => 25],
            'end' => ['H' => 13, 'i' => 35],
        ],[
            'label' => '13:35〜14:35',
            'start' => ['H' => 13, 'i' => 35],
            'end' => ['H' => 14, 'i' => 45],
        ],[
            'label' => '14:45〜15:15',
            'start' => ['H' => 14, 'i' => 45],
            'end' => ['H' => 16, 'i' => 15],
        ],[
            'label' => '16:15〜17:15',
            'start' => ['H' => 16, 'i' => 15],
            'end' => ['H' => 17, 'i' => 15],
        ],[
            'label' => '17:15〜18:15',
            'start' => ['H' => 17, 'i' => 15],
            'end' => ['H' => 18, 'i' => 25],
        ],[
            'label' => '18:25〜19:25',
            'start' => ['H' => 18, 'i' => 25],
            'end' => ['H' => 19, 'i' => 25],
        ],[
            'label' => '19:25〜20:25',
            'start' => ['H' => 19, 'i' => 25],
            'end' => ['H' => 21, 'i' => 10],
        ],[
            'label' => '21:10〜22:10',
            'start' => ['H' => 21, 'i' => 10],
            'end' => ['H' => 22, 'i' => 10],
        ],[
            'label' => '22:10〜23:10',
            'start' => ['H' => 22, 'i' => 10],
            'end' => ['H' => 23, 'i' => 20],
        ],[
            'label' => '23:20〜0:20',
            'start' => ['H' => 23, 'i' => 20],
            'end' => ['H' => 24, 'i' => 30],
        ],[
            'label' => '0:30〜1:00',
            'start' => ['H' => 24, 'i' => 30],
            'end' => ['H' => 30, 'i' => 30],
        ],[
            'label' => '直合計',
            'start' => ['H' => 6, 'i' => 30],
            'end' => ['H' => 30, 'i' => 30],
        ]
    ]
];