<?php

declare(strict_types=1);

namespace Maispace\MaiResource\Controller;

use Maispace\MaiBase\Controller\AbstractActionController;
use Maispace\MaiBase\Controller\Traits\AppendDataToPluginVariablesTrait;
use Maispace\MaiResource\Domain\Repository\DownloadRepository;
use Psr\Http\Message\ResponseInterface;

class DownloadController extends AbstractActionController
{
    use AppendDataToPluginVariablesTrait;

    public function __construct(
        private readonly DownloadRepository $downloadRepository,
    ) {}

    public function listAction(): ResponseInterface
    {
        $settings = $this->getSettings();
        $pageUids = $this->resolveStoragePageUids();
        $categoryUid = (int) ($settings['categoryUids'] ?? 0);

        if ($pageUids !== [] && $categoryUid > 0) {
            $downloads = $this->downloadRepository->findFromPagesByCategoryUid($pageUids, $categoryUid);
        } elseif ($pageUids !== []) {
            $downloads = $this->downloadRepository->findFromPages($pageUids);
        } else {
            $downloads = $this->downloadRepository->findAll();
        }

        $this->view->assignMultiple([
            'downloads' => $downloads,
            'settings' => $settings,
        ]);

        return $this->htmlResponse();
    }

    /**
     * @return list<int>
     */
    private function resolveStoragePageUids(): array
    {
        $pages = $this->settings['pages'] ?? '';
        if ($pages === '') {
            return [];
        }

        return array_values(array_filter(
            array_map('intval', explode(',', (string) $pages)),
            static fn(int $uid): bool => $uid > 0,
        ));
    }
}
