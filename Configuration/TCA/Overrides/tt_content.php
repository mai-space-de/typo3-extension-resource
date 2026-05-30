<?php

declare(strict_types=1);

defined('TYPO3') or die();

use Maispace\MaiBase\TableConfigurationArray\CType;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

$lang = Helper::localLangHelperFactory('mai_resource', 'Default/locallang_tca.xlf');

ExtensionUtility::registerPlugin(
    'MaiResource',
    'List',
    $lang('plugin.list.title'),
    'mai-content',
    'maispace_feature',
);

(new CType('maispace_resource_list', $lang('ctype.resource_list'), 'mai-content'))
    ->addDefaultHeaderPalette()
    ->addCustomFields('pi_flexform')
    ->addDefaultLanguageTab()
    ->addDefaultAccessTab()
    ->setGroup('maispace_feature')
    ->register();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:mai_resource/Configuration/FlexForms/ResourceListPlugin.xml',
    'maispace_resource_list',
);
