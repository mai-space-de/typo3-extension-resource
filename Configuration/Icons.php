<?php

declare(strict_types=1);

$allIcons = 'EXT:mai_base/Resources/Public/Icons/all-icons/';

return [
    'ext-maispace-mai_resource' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:mai_resource/Resources/Public/Icons/Extension.svg',
    ],
    'mai-resource' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => $allIcons . 'interface-content-archive--box-content-banker-archive-file--Streamline-Core.svg',
    ],
    'mai-resource-download' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => $allIcons . 'interface-download-button-1--arrow-button-down-download-internet-network-server-upload--Streamline-Core.svg',
    ],
    'mai-resource-infobox' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => $allIcons . 'interface-alert-information-circle--information-frame-info-more-help-point-circle--Streamline-Core.svg',
    ],
    'mai-resource-template' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => $allIcons . 'Interface-file-text--text-common-file--Streamline-Core.svg',
    ],
];
