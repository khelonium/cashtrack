<?php
return [
    'default' => [
        [
            'label' => 'Cashflow',
            'route' => 'home',
        ],

        [
            'label' => 'Prediction',
            'route' => 'prediction',
        ],

        [
            'label' => 'Merchants',
            'route' => 'merchants',
        ],
        [
            'label' => 'Weekly',
            'route' => 'weekly',
        ],

        [
            'label' => 'Monthly',
            'route' => 'report',
            'action' => 'monthly'
        ],

        [
            'label' => 'Yearly',
            'route' => 'report',
            'action' => 'yearly',
        ],

    ],
];