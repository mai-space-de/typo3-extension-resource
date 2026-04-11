<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_resource', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_mairesource_template')))
    ->setDefaultConfig()
    ->setLabel('title')
    ->setSearchFields('title, description')
    ->setIconFile('EXT:mai_resource/Resources/Public/Icons/tx_mairesource_template.svg')
    ->setSortingField()
    ->addColumn(
        'title',
        $lang('tx_mairesource_template.title'),
        ['type' => 'input', 'size' => 50, 'max' => 255, 'eval' => 'trim,required']
    )
    ->addColumn(
        'description',
        $lang('tx_mairesource_template.description'),
        ['type' => 'text', 'rows' => 5, 'cols' => 50, 'eval' => 'trim']
    )
    ->addColumn(
        'file',
        $lang('tx_mairesource_template.file'),
        [
            'type' => 'file',
            'minitems' => 1,
            'maxitems' => 1,
            'appearance' => [
                'createNewRelationLinkTitle' => $lang('tx_mairesource_template.file.addFile'),
            ],
        ]
    )
    ->addColumn(
        'categories',
        $lang('tx_mairesource_template.categories'),
        ['type' => 'category']
    )
    ->addTypeShowItem(
        '0',
        'title, description, file, categories,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
