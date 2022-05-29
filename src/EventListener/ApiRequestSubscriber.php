<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ApiRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly KernelInterface $kernel,
        private readonly Security $security,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => 'onKernelView',
            KernelEvents::EXCEPTION => ['onKernelException', 2],
        ];
    }

    public function onKernelView(ViewEvent $event): void
    {
        $event->setResponse($this->controllerResultToResponse($event));
    }

    private function controllerResultToResponse(ViewEvent $event): Response
    {
        $controllerResult = $event->getControllerResult();

        if (null === $controllerResult) {
            return new Response(null, Response::HTTP_NO_CONTENT);
        }

        if ($controllerResult instanceof ConstraintViolationListInterface) {
            $errors = [];
            foreach ($controllerResult as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }

            return new JsonResponse(['status' => 'error', 'data' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $response = new JsonResponse($controllerResult);
        match ($event->getRequest()->getMethod()) {
            'POST' => $response->setStatusCode(Response::HTTP_CREATED),
            'DELETE' => $response->setStatusCode(Response::HTTP_NO_CONTENT),
            default => null,
        };

        return $response;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (!$this->isApiRoute()) {
            return;
        }
        $throwable = $event->getThrowable();
        $content = ['status' => 'error'];
        if ($throwable instanceof AccessDeniedException) {
            $status = null === $this->security->getToken() ? 401 : 403;
            $content['message'] = Response::$statusTexts[$status];
            $event->stopPropagation();
        } else {
            if ($throwable instanceof HttpException) {
                $status = $throwable->getStatusCode();
                $content['message'] = $throwable->getMessage();
            } else {
                $status = 500;
                $content['message'] = Response::$statusTexts[500];
                if ('dev' === $this->kernel->getEnvironment()) {
                    if ('application/json' !== $event->getRequest()->getContentType()) {
                        return;
                    }
                    $content['trace'] = $throwable->getTraceAsString();
                }
            }
        }

        $event->setResponse(new JsonResponse($content, $status));
    }

    private function isApiRoute(): bool
    {
        return str_starts_with($this->router->getContext()->getPathInfo(), '/api/');
    }
}
