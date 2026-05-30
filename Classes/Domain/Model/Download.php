<?php

declare(strict_types=1);

namespace Maispace\MaiResource\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Download extends AbstractEntity
{
    protected string $title = '';

    protected string $description = '';

    protected ?FileReference $file = null;

    /**
     * @var ObjectStorage<Category>
     */
    protected ObjectStorage $categories;

    public function __construct()
    {
        $this->categories = new ObjectStorage();
    }

    public function initializeObject(): void
    {
        $this->categories = new ObjectStorage();
    }

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

    /**
     * @return ObjectStorage<Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param ObjectStorage<Category> $categories
     */
    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }
}
