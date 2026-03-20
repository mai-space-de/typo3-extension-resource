<?php

defined('TYPO3') or exit('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Maispace.Resource',
    'DownloadList',
    'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:plugin.downloadlist.title',
    'EXT:resource/Resources/Public/Icons/plugin_resource_downloadlist.svg',
    'maispace_resource'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Maispace.Resource',
    'CategorizedList',
    'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:plugin.categorizedlist.title',
    'EXT:resource/Resources/Public/Icons/plugin_resource_categorizedlist.svg',
    'maispace_resource'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Maispace.Resource',
    'InfoBoxWidget',
    'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:plugin.infoboxwidget.title',
    'EXT:resource/Resources/Public/Icons/plugin_resource_infoboxwidget.svg',
    'maispace_resource'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Maispace.Resource',
    'FormPreview',
    'LLL:EXT:resource/Resources/Private/Language/locallang_db.xlf:plugin.formpreview.title',
    'EXT:resource/Resources/Public/Icons/plugin_resource_formpreview.svg',
    'maispace_resource'
);
