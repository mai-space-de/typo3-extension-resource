<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\FieldConfig\CategoryConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\FileConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InputConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\TextConfig;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_resource', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_mairesource_template')))
    ->setDefaultConfig()
    ->setLabel('title')
    ->setIconFile('EXT:mai_resource/Resources/Public/Icons/tx_mairesource_template.svg')
    ->setSortingField()
    ->addColumn(
        'title',
        $lang('tx_mairesource_template.title'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')->setRequired()
    )
    ->addColumn(
        'description',
        $lang('tx_mairesource_template.description'),
        (new TextConfig())->setRows(5)->setCols(50)->setEval('trim')
    )
    ->addColumn(
        'file',
        $lang('tx_mairesource_template.file'),
        (new FileConfig())
            ->setMinItems(1)
            ->setMaxItems(1)
            ->setAppearance([
                'createNewRelationLinkTitle' => $lang('tx_mairesource_template.file.addFile'),
            ])
    )
    ->addColumn(
        'categories',
        $lang('tx_mairesource_template.categories'),
        new CategoryConfig()
    )
    ->addTypeShowItem(
        '0',
        'title, description, file, categories,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
