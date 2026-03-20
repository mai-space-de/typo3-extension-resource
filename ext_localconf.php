<?php

defined('TYPO3') or exit('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Maispace.Resource',
    'DownloadList',
    [
        \Maispace\Resource\Controller\ResourceController::class => 'downloadList',
    ],
    [],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Maispace.Resource',
    'CategorizedList',
    [
        \Maispace\Resource\Controller\ResourceController::class => 'categorizedList',
    ],
    [],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Maispace.Resource',
    'InfoBoxWidget',
    [
        \Maispace\Resource\Controller\ResourceController::class => 'infoBoxWidget',
    ],
    [],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Maispace.Resource',
    'FormPreview',
    [
        \Maispace\Resource\Controller\ResourceController::class => 'formPreview',
    ],
    [],
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
