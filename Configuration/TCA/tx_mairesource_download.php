<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\FieldConfig\CategoryConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\FileConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InputConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\SelectMultipleConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\TextConfig;
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
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim,required')
    )
    ->addColumn(
        'description',
        $lang('tx_mairesource_download.description'),
        (new TextConfig())->setRows(5)->setCols(50)->setEval('trim')
    )
    ->addColumn(
        'file',
        $lang('tx_mairesource_download.file'),
        (new FileConfig())
            ->setMinItems(1)
            ->setMaxItems(1)
            ->setAppearance([
                'createNewRelationLinkTitle' => $lang('tx_mairesource_download.file.addFile'),
            ])
    )
    ->addColumn(
        'fe_groups',
        $lang('tx_mairesource_download.fe_groups'),
        (new SelectMultipleConfig())
            ->setForeignTable('fe_groups')
            ->setForeignTableWhere('ORDER BY fe_groups.title')
            ->setSize(5)
            ->setMinItems(0)
    )
    ->addColumn(
        'categories',
        $lang('tx_mairesource_download.categories'),
        new CategoryConfig()
    )
    ->addTypeShowItem(
        '0',
        'title, description, file, categories,
        --div--;' . $lang('tab.access') . ', fe_groups, --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
