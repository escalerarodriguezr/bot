<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Ui\Http\Listener\Shared;

use Bot\Domain\Client\Exception\ClientAlreadyExistsException;
use Bot\Domain\Client\Exception\ClientNotFoundException;
use Bot\Domain\User\Exception\UserNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class JsonTransformerExceptionListener
{
    const ERRORS_KEY = 'errors';
    const CLASS_KEY = 'class';
    const CODE_KEY = 'code';
    const MESSAGE_KEY = 'message';

    public function __construct(
        private readonly LoggerInterface $logger
    )
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious();
        }

        if(!$exception instanceof NotFoundHttpException){
            $this->logger->error($exception);
        }

        $data = [
            self::CLASS_KEY => \get_class($exception),
            self::CODE_KEY => Response::HTTP_INTERNAL_SERVER_ERROR,
            self::MESSAGE_KEY => $exception->getMessage(),
        ];

        if ($exception instanceof HttpExceptionInterface) {
            $data[self::CODE_KEY] = $exception->getStatusCode();
        }

        if (\in_array($data[self::CLASS_KEY], $this->getNotFoundExceptions(), true)) {
            $data[self::CODE_KEY] = Response::HTTP_NOT_FOUND;
        }

        if (\in_array($data[self::CLASS_KEY], $this->getDeniedExceptions(), true)) {
            $data[self::CODE_KEY] = Response::HTTP_FORBIDDEN;
        }

        if (\in_array($data[self::CLASS_KEY], $this->getConflictExceptions(), true)) {
            $data[self::CODE_KEY] = Response::HTTP_CONFLICT;
        }

        if ($exception instanceof UnprocessableEntityHttpException) {
            $data[self::ERRORS_KEY] = [];
            foreach ( json_decode($exception->getMessage()) as $key => $error ){
                $data[self::ERRORS_KEY][$key] = $error;
            }
        }

        $event->setResponse($this->prepareResponse($data));

    }

    private function prepareResponse(array $data): JsonResponse
    {
        $response = new JsonResponse($data, $data[self::CODE_KEY]);
        $response->headers->set('X-Error-Code', (string) $data[self::CODE_KEY]);
        $response->headers->set('X-Server-Time', (string) \time());

        return $response;
    }

    private function getNotFoundExceptions(): array
    {
        return [
            ClientNotFoundException::class,
            UserNotFoundException::class
        ];
    }

    private function getConflictExceptions(): array
    {
        return [
            ClientAlreadyExistsException::class
        ];
    }

    private function getDeniedExceptions(): array
    {
        return [
            AccessDeniedException::class,
        ];
    }

}