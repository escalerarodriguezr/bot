<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Ui\Http\Request\Transformer;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestBodyTransformer
{
    public function transform(Request $request)
    {

        switch ($request->headers->get('Content-Type')) {
            case 'application/json':
                $data = \json_decode($request->getContent(), true);
                if (!empty($data)) {
                    $request->request = new ParameterBag($data);
                }
                break;
            default:
                if ( !empty($request->headers->get('Content-Type')) && !str_contains($request->headers->get('Content-Type'), 'form-data')
                ) {
                    throw new BadRequestHttpException('Invalid Content-Type');
                }
                $data = \json_decode($request->getContent(), true);
                if (!empty($data)) {
                    $request->request = new ParameterBag($data);
                }
                break;
        }
    }

}