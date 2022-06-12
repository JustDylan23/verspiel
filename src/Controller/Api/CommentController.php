<?php

namespace App\Controller\Api;

use App\Api\ApiPaginator;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class CommentController extends AbstractRestController
{
    protected const LIST_ATTRIBUTES = [
        'id',
        'author' => [
            'id',
            'username',
            'badges',
        ],
        'content',
        'replyCount',
        'createdAt',
    ];

    protected const WRITE_ATTRIBUTES = [
        'id',
        'content',
        'commentSection',
        'replyTo',
        'author',
    ];

    #[Route('/comment-sections/{id<\d+>}/comments', methods: ['GET'])]
    public function readComments(
        int $id,
        Request $request,
        ApiPaginator $apiPaginator,
        CommentRepository $commentRepository,
    ): array {
        $replyTo = $request->query->getInt('replyTo');
        if ($replyTo === 0) {
            $replyTo = null;
        }

        $qb = $commentRepository->findByCommentSection($id, $replyTo);

        $cursor = $request->query->getInt('cursor');

        if ($cursor <= 0) {
            $cursor = null;
        }

        $comments = $apiPaginator->cursorPaginate($qb, $cursor, 'DESC');

        return $this->viewList($comments);
    }

    #[Route('/comments', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function postComment(
        Request $request,
        RateLimiterFactory $postCommentLimiter
    ): ConstraintViolationListInterface|array {
        $limiter = $postCommentLimiter->create($request->getClientIp());
        $limiter->consume()->ensureAccepted();

        $comment = new Comment();

        $this->deserializeRequestContent($comment);

        $this->assertValid($comment);

        return $this->viewCreate($comment);
    }

    #[Route('/comments/{id<\d+>}', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function deleteComment(int $id, CommentRepository $commentRepository): ConstraintViolationListInterface|null
    {
        $comment = $commentRepository->find($id);

        $this->assertValid($comment);

        $this->viewDelete($comment);

        return null;
    }
}
