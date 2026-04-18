<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\FieldConfig\CategoryConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\FileConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InputConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\TextConfig;
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
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim,required')
    )
    ->addColumn(
        'content',
        $lang('tx_mairesource_infobox.content'),
        (new TextConfig())->setRows(15)->setCols(50)->enableRte()->setRichtextConfiguration('default')
    )
    ->addColumn(
        'attachment',
        $lang('tx_mairesource_infobox.attachment'),
        (new FileConfig())
            ->setMaxItems(5)
            ->setAppearance([
                'createNewRelationLinkTitle' => $lang('tx_mairesource_infobox.attachment.addFile'),
                'enabledControls' => ['info' => true, 'dragdrop' => true, 'sort' => true, 'hide' => true, 'delete' => true],
            ])
    )
    ->addColumn(
        'categories',
        $lang('tx_mairesource_infobox.categories'),
        new CategoryConfig()
    )
    ->addTypeShowItem(
        '0',
        'title, content, attachment, categories,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
