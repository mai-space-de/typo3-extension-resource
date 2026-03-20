<?php

defined('TYPO3') or exit('Access denied.');

return [
    'ctrl' => [
        'title' => 'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:tx_resource_domain_model_resourcecategory',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'iconfile' => 'EXT:resource/Resources/Public/Icons/tx_resource_domain_model_resourcecategory.svg',
        'searchFields' => 'title,description',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                    title,type,description,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
            ',
        ],
    ],
    'palettes' => [
        'hidden' => [
            'showitem' => 'hidden',
        ],
        'language' => [
            'showitem' => 'sys_language_uid,l18n_parent',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l18n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['label' => '', 'value' => 0],
                ],
                'foreign_table' => 'tx_resource_domain_model_resourcecategory',
                'foreign_table_where' => 'AND {#tx_resource_domain_model_resourcecategory}.{#pid}=###CURRENT_PID### AND {#tx_resource_domain_model_resourcecategory}.{#sys_language_uid} IN (-1,0)',
                'default' => 0,
            ],
        ],
        'l18n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'title' => [
            'exclude' => false,
            'label' => 'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:tx_resource_domain_model_resourcecategory.title',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'max' => 255,
                'eval' => 'trim',
                'required' => true,
            ],
        ],
        'type' => [
            'exclude' => false,
            'label' => 'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:tx_resource_domain_model_resourcecategory.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:tx_resource_domain_model_resourcecategory.type.download',
                        'value' => 0,
                    ],
                    [
                        'label' => 'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:tx_resource_domain_model_resourcecategory.type.vorlage',
                        'value' => 1,
                    ],
                    [
                        'label' => 'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:tx_resource_domain_model_resourcecategory.type.formular',
                        'value' => 2,
                    ],
                    [
                        'label' => 'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:tx_resource_domain_model_resourcecategory.type.info',
                        'value' => 3,
                    ],
                ],
                'default' => 0,
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:tx_resource_domain_model_resourcecategory.description',
            'config' => [
                'type' => 'text',
                'cols' => 60,
                'rows' => 3,
            ],
        ],
    ],
];
