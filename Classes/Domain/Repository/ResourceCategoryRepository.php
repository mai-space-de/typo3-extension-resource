<?php

declare(strict_types=1);

namespace Maispace\MaiResource\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for ResourceCategory domain objects.
 */
class ResourceCategoryRepository extends Repository
{
    protected $defaultOrderings = [
        'title' => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * Find all categories of a specific type.
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByType(int $type): \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->equals('type', $type)
        );
        return $query->execute();
    }
}
