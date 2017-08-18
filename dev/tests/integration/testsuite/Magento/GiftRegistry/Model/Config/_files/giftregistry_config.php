<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
return [
    'attribute_types' => ['text' => ['label' => 'Text'], 'text2' => ['label' => 'Text']],
    'attribute_groups' => [
        'event_information' => ['sortOrder' => '5', 'visible' => 'true', 'label' => 'Event Information'],
        'event_information2' => ['sortOrder' => '5', 'visible' => 'true', 'label' => 'Event Information'],
    ],
    'registry' => [
        'static_attributes' => [
            'event_country' => [
                'type' => 'country',
                'group' => 'event_information',
                'visible' => 'true',
                'label' => 'Event Country',
            ],
            'event_country2' => [
                'type' => 'country',
                'group' => 'event_information',
                'visible' => 'true',
                'label' => 'Event Country',
            ],
        ],
        'custom_attributes' => [
            'my_event_special' => [
                'type' => 'country',
                'group' => 'event_information',
                'visible' => 'true',
                'label' => 'My event special',
            ],
            'my_event_special2' => [
                'type' => 'country',
                'group' => 'event_information',
                'visible' => 'true',
                'label' => 'My event special',
            ],
        ],
    ],
    'registrant' => [
        'static_attributes' => [
            'role' => ['type' => 'select', 'group' => 'registrant', 'visible' => 'true', 'label' => 'Role'],
            'role2' => ['type' => 'select', 'group' => 'registrant', 'visible' => 'true', 'label' => 'Role'],
        ],
        'custom_attributes' => [
            'my_special_attribute' => [
                'type' => 'country',
                'group' => 'registrant',
                'visible' => 'true',
                'label' => 'My special attribute',
            ],
            'my_special_attribute2' => [
                'type' => 'country',
                'group' => 'registrant',
                'visible' => 'true',
                'label' => 'My special attribute',
            ],
        ],
    ]
];
