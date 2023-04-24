<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Ui\Http\Request;

use Bot\Infrastructure\Ui\Http\Request\Transformer\RequestBodyTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestArgumentResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly RequestBodyTransformer $requestBodyTransformer
    )
    {

    }


    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $reflectionClass = new \ReflectionClass($argument->getType());
        if ($reflectionClass->implementsInterface(RequestDTO::class)) {
            return true;
        }

        return false;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $class = $argument->getType();
        $this->requestBodyTransformer->transform($request);
        $dto = new $class($request);

        $errors = $this->validator->validate($dto);

        if (\count($errors) > 0) {
            $errorCollection = array();
            foreach($errors as $error){
                $errorCollection[$error->getPropertyPath()] = $error->getMessage();
            }
            throw new UnprocessableEntityHttpException(json_encode($errorCollection,1));
        }

        yield $dto;
    }

}