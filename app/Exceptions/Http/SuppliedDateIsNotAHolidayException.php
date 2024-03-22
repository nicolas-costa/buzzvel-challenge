<?php

declare(strict_types=1);

namespace App\Exceptions\Http;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class SuppliedDateIsNotAHolidayException extends HttpException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, "Supplied date is not a holiday", $previous);
    }
}
