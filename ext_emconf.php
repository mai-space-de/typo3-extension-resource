<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Resource',
    'description' => 'Downloads, Vorlagen, Formulare & Info-Boxen mit Zugriffsschutz per FE-Gruppe.',
    'version' => '1.0.0',
    'state' => 'stable',
    'category' => 'plugin',
    'author' => 'Joel Maximilian Mai',
    'author_email' => 'joel@maispace.de',
    'author_company' => 'Maispace',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
            'extbase' => '',
            'fluid' => '',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'autoload' => [
        'psr-4' => [
            'Maispace\\Resource\\' => 'Classes',
        ],
    ],
];
