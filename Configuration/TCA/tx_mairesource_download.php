<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_resource', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_mairesource_download')))
    ->setDefaultConfig()
    ->setLabel('title')
    ->setSearchFields('title, description')
    ->setIconFile('EXT:mai_resource/Resources/Public/Icons/tx_mairesource_download.svg')
    ->setSortingField()
    ->addColumn(
        'title',
        $lang('tx_mairesource_download.title'),
        ['type' => 'input', 'size' => 50, 'max' => 255, 'eval' => 'trim,required']
    )
    ->addColumn(
        'description',
        $lang('tx_mairesource_download.description'),
        ['type' => 'text', 'rows' => 5, 'cols' => 50, 'eval' => 'trim']
    )
    ->addColumn(
        'file',
        $lang('tx_mairesource_download.file'),
        [
            'type' => 'file',
            'minitems' => 1,
            'maxitems' => 1,
            'appearance' => [
                'createNewRelationLinkTitle' => $lang('tx_mairesource_download.file.addFile'),
            ],
        ]
    )
    ->addColumn(
        'fe_groups',
        $lang('tx_mairesource_download.fe_groups'),
        [
            'type' => 'select',
            'renderType' => 'selectMultipleSideBySide',
            'foreign_table' => 'fe_groups',
            'foreign_table_where' => 'ORDER BY fe_groups.title',
            'size' => 5,
            'minitems' => 0,
        ]
    )
    ->addColumn(
        'categories',
        $lang('tx_mairesource_download.categories'),
        ['type' => 'category']
    )
    ->addTypeShowItem(
        '0',
        'title, description, file, categories,
        --div--;' . $lang('tab.access') . ', fe_groups, --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
