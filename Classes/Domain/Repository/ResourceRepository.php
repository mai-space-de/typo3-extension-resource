<?php

declare(strict_types=1);

namespace Maispace\Resource\Domain\Repository;

use Maispace\Resource\Domain\Model\ResourceCategory;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for Resource domain objects.
 */
class ResourceRepository extends Repository
{
    protected $defaultOrderings = [
        'title' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Find all public resources (accessGroup = 0).
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findPublic(): \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('accessGroup', \Maispace\Resource\Domain\Model\Resource::ACCESS_GROUP_PUBLIC)
        );
        return $query->execute();
    }

    /**
     * Find all resources accessible to members (accessGroup = 1).
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findForMembers(): \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('accessGroup', \Maispace\Resource\Domain\Model\Resource::ACCESS_GROUP_MEMBERS)
        );
        return $query->execute();
    }

    /**
     * Find all resources by category.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByCategory(ResourceCategory $category): \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('category', $category)
        );
        return $query->execute();
    }

    /**
     * Find resources by category type.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByCategoryType(int $categoryType): \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('category.type', $categoryType)
        );
        return $query->execute();
    }
}
