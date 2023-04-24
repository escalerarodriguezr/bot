<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Ui\Http\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestDTO
{
    public function __construct(Request $request);

}