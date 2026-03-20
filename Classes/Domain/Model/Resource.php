<?php

declare(strict_types=1);

namespace Maispace\Resource\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Resource model representing a downloadable file, template, form, or info document.
 */
class Resource extends AbstractEntity
{
    /**
     * Access group constant: public (no login required)
     */
    public const ACCESS_GROUP_PUBLIC = 0;

    /**
     * Access group constant: members only (FE login required)
     */
    public const ACCESS_GROUP_MEMBERS = 1;

    protected string $title = '';

    protected string $description = '';

    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     * @Lazy
     */
    protected ?FileReference $file = null;

    /**
     * @var \Maispace\Resource\Domain\Model\ResourceCategory|null
     * @Lazy
     */
    protected ?ResourceCategory $category = null;

    /**
     * Access group: 0 = public, 1 = members only
     */
    protected int $accessGroup = self::ACCESS_GROUP_PUBLIC;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getFile(): ?FileReference
    {
        return $this->file;
    }

    public function setFile(?FileReference $file): void
    {
        $this->file = $file;
    }

    public function getCategory(): ?ResourceCategory
    {
        return $this->category;
    }

    public function setCategory(?ResourceCategory $category): void
    {
        $this->category = $category;
    }

    public function getAccessGroup(): int
    {
        return $this->accessGroup;
    }

    public function setAccessGroup(int $accessGroup): void
    {
        $this->accessGroup = $accessGroup;
    }

    public function isPublic(): bool
    {
        return $this->accessGroup === self::ACCESS_GROUP_PUBLIC;
    }

    public function isMembersOnly(): bool
    {
        return $this->accessGroup === self::ACCESS_GROUP_MEMBERS;
    }
}
