<?php

declare(strict_types=1);

namespace Maispace\MaiResource\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class DownloadRepository extends Repository
{
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING,
    ];

    public function findFromPages(array $pageUids): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($pageUids);

        return $query->execute();
    }

    public function findFromPagesByCategoryUid(array $pageUids, int $categoryUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setStoragePageIds($pageUids);
        $query->matching(
            $query->contains('categories', $categoryUid),
        );

        return $query->execute();
    }
}
