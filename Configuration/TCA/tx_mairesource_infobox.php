<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_resource', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_mairesource_infobox')))
    ->setDefaultConfig()
    ->setLabel('title')
    ->setSearchFields('title, content')
    ->setIconFile('EXT:mai_resource/Resources/Public/Icons/tx_mairesource_infobox.svg')
    ->setSortingField()
    ->addColumn(
        'title',
        $lang('tx_mairesource_infobox.title'),
        ['type' => 'input', 'size' => 50, 'max' => 255, 'eval' => 'trim,required']
    )
    ->addColumn(
        'content',
        $lang('tx_mairesource_infobox.content'),
        [
            'type' => 'text',
            'rows' => 15,
            'cols' => 50,
            'enableRichtext' => true,
            'richtextConfiguration' => 'default',
        ]
    )
    ->addColumn(
        'attachment',
        $lang('tx_mairesource_infobox.attachment'),
        [
            'type' => 'file',
            'maxitems' => 5,
            'appearance' => [
                'createNewRelationLinkTitle' => $lang('tx_mairesource_infobox.attachment.addFile'),
                'enabledControls' => ['info' => true, 'dragdrop' => true, 'sort' => true, 'hide' => true, 'delete' => true],
            ],
        ]
    )
    ->addColumn(
        'categories',
        $lang('tx_mairesource_infobox.categories'),
        ['type' => 'category']
    )
    ->addTypeShowItem(
        '0',
        'title, content, attachment, categories,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
