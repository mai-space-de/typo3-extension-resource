<?php

declare(strict_types=1);

namespace Maispace\MaiResource\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * ResourceCategory model representing a category type for resources.
 * Types: Download (0), Vorlage/Template (1), Formular/Form (2), Info (3)
 */
class ResourceCategory extends AbstractEntity
{
    /**
     * Category type constant: Download
     */
    public const TYPE_DOWNLOAD = 0;

    /**
     * Category type constant: Vorlage (Template)
     */
    public const TYPE_VORLAGE = 1;

    /**
     * Category type constant: Formular (Form)
     */
    public const TYPE_FORMULAR = 2;

    /**
     * Category type constant: Info
     */
    public const TYPE_INFO = 3;

    protected string $title = '';

    /**
     * Category type: 0=Download, 1=Vorlage, 2=Formular, 3=Info
     */
    protected int $type = self::TYPE_DOWNLOAD;

    protected string $description = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function isDownload(): bool
    {
        return $this->type === self::TYPE_DOWNLOAD;
    }

    public function isVorlage(): bool
    {
        return $this->type === self::TYPE_VORLAGE;
    }

    public function isFormular(): bool
    {
        return $this->type === self::TYPE_FORMULAR;
    }

    public function isInfo(): bool
    {
        return $this->type === self::TYPE_INFO;
    }
}
