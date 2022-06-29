<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Api\ApiPaginator;
use App\Api\Filter\ApiFilterProcessor;
use App\Api\Filter\StringFilter;
use App\Repository\ChapterRepository;
use Symfony\Component\Routing\Annotation\Route;

class ChapterController extends AbstractRestController
{
    public const ITEM_ATTRIBUTES = [
        'id',
        'title',
        'content',
        'createdAt',
        'number',
        'novel' => [
            'id',
            'title',
            'shortDescription',
        ],
        'commentSection' => [
            'id',
        ],
    ];

    public const LIST_ATTRIBUTES = [
        'id',
        'title',
        'createdAt',
        'number',
        'novel' => [
            'id',
            'title',
        ],
    ];

    #[Route('/chapters/latest', name: 'api_chapters_get_list_latest', methods: ['GET'])]
    public function latest(ChapterRepository $chapterRepository): array
    {
        // limited to 10
        $chapters = $chapterRepository->findLatest();

        return $this->viewList($chapters);
    }

    #[Route('/chapters', name: 'api_chapters_get_list', methods: ['GET'])]
    public function list(
        ChapterRepository $chapterRepository,
        ApiFilterProcessor $filter,
        ApiPaginator $apiPaginator
    ): array {
        $queryBuilder = $chapterRepository->getChaptersQB();

        $filter->apply($queryBuilder, [
            new StringFilter([
                'param' => 'title',
                'field' => ['c.title', 'c.number', 'n.title'],
            ]),
        ]);

        return $this->viewList($apiPaginator->paginate($queryBuilder));
    }

    #[Route('/chapters/{id<\d+>}', name: 'api_chapters_get_item', methods: ['GET'])]
    public function get(int $id, ChapterRepository $chapterRepository): array
    {
        $novel = $chapterRepository->getChapter($id);

        $array = $this->viewItem($novel);
        $array['nextChapter'] = $chapterRepository->getNextChapter($novel)?->getId();
        $array['previousChapter'] = $chapterRepository->getPreviousChapter($novel)?->getId();

        return $array;
    }

    #[Route('/novels/{id<\d+>}/chapters', name: 'api_novels_chapters_get_list', methods: ['GET'])]
    public function getByNovel(int $id, ChapterRepository $chapterRepository): array
    {
        $novels = $chapterRepository->getChaptersByNovel($id);

        return $this->viewList($novels, [
            'id',
            'title',
            'number',
        ]);
    }
}
