<?php

declare(strict_types=1);

namespace Maispace\Resource\Controller;

use Maispace\Resource\Domain\Model\ResourceCategory;
use Maispace\Resource\Domain\Repository\ResourceCategoryRepository;
use Maispace\Resource\Domain\Repository\ResourceRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller for Resource plugin actions.
 */
class ResourceController extends ActionController
{
    public function __construct(
        protected readonly ResourceRepository $resourceRepository,
        protected readonly ResourceCategoryRepository $resourceCategoryRepository,
    ) {
    }

    /**
     * Download list action: displays all resources of type Download.
     */
    public function downloadListAction(): ResponseInterface
    {
        $resources = $this->resourceRepository->findByCategoryType(ResourceCategory::TYPE_DOWNLOAD);
        $this->view->assign('resources', $resources);
        return $this->htmlResponse();
    }

    /**
     * Categorized list action: displays resources grouped by category.
     */
    public function categorizedListAction(): ResponseInterface
    {
        $categories = $this->resourceCategoryRepository->findAll();
        $resourcesByCategory = [];

        foreach ($categories as $category) {
            $resourcesByCategory[] = [
                'category' => $category,
                'resources' => $this->resourceRepository->findByCategory($category),
            ];
        }

        $this->view->assign('resourcesByCategory', $resourcesByCategory);
        return $this->htmlResponse();
    }

    /**
     * Info box widget action: displays resources of type Info.
     */
    public function infoBoxWidgetAction(): ResponseInterface
    {
        $resources = $this->resourceRepository->findByCategoryType(ResourceCategory::TYPE_INFO);
        $this->view->assign('resources', $resources);
        return $this->htmlResponse();
    }

    /**
     * Form preview action: displays resources of type Formular as inline previews.
     */
    public function formPreviewAction(): ResponseInterface
    {
        $resources = $this->resourceRepository->findByCategoryType(ResourceCategory::TYPE_FORMULAR);
        $this->view->assign('resources', $resources);
        return $this->htmlResponse();
    }
}
