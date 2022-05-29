<?php

namespace App\Controller\Api;

use App\Api\ApiPaginator;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
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
            'isAdmin',
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
    public function postComment(Request $request, RateLimiterFactory $postCommentLimiter ): ConstraintViolationListInterface|array
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $limiter = $postCommentLimiter->create($request->getClientIp());
        if (false === $limiter->consume()->isAccepted()) {
            throw new TooManyRequestsHttpException(message: 'You are being rate limited, wait 5 minutes before posting again.');
        }

        $comment = new Comment();

        $this->deserializeRequestContent($comment);

        $violations = $this->getViolations($comment);

        if ($violations->count() > 0) {
            return $violations;
        }

        return $this->viewCreate($comment);
    }

    #[Route('/comments/{id<\d+>}', methods: ['DELETE'])]
    public function deleteComment(int $id, CommentRepository $commentRepository): ConstraintViolationListInterface|null
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $comment = $commentRepository->find($id);

        $violations = $this->getViolations($comment);

        if ($violations->count() > 0) {
            return $violations;
        }

        $this->viewDelete($comment);

        return null;
    }
}
