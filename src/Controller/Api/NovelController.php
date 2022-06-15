<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Api\ApiPaginator;
use App\Api\Filter\ApiFilterProcessor;
use App\Api\Filter\StringFilter;
use App\Repository\NovelRepository;
use Symfony\Component\Routing\Annotation\Route;

class NovelController extends AbstractRestController
{
    public const ITEM_ATTRIBUTES = [
        'id',
        'title',
        'shortDescription', // used for meta description
        'description',
        'createdAt',
        'chapters' => [
            'id',
            'number',
            'title',
            'createdAt',
        ],
        'commentSection' => [
            'id',
        ],
    ];

    public const LIST_ATTRIBUTES = [
        'id',
        'title',
        'shortDescription',
        'createdAt',
    ];

    #[Route('/novels/featured', name: 'api_novels_get_list_featured', methods: ['GET'])]
    public function featured(NovelRepository $novelRepository): array
    {
        $novels = $novelRepository->getLatestNovels();

        return $this->viewList($novels);
    }

    #[Route('/novels/{id<\d+>}', name: 'api_novels_get_item', methods: ['GET'])]
    public function get(int $id, NovelRepository $novelRepository): array
    {
        $novel = $novelRepository->getNovel($id);

        return $this->viewItem($novel);
    }

    #[Route('/novels', name: 'api_novels_get_list', methods: ['GET'])]
    public function list(ApiFilterProcessor $filter, ApiPaginator $apiPaginator, NovelRepository $novelRepository): array
    {
        $queryBuilder = $novelRepository->getNovelsQB();

        $filter->apply($queryBuilder, [
            new StringFilter([
                'param' => 'title',
                'field' => 'n.title',
            ]),
        ]);

        return $this->viewList($apiPaginator->paginate($queryBuilder));
    }
}
