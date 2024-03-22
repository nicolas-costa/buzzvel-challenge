<?php

declare(strict_types=1);

namespace App\Exceptions\Http;


use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class UnableToFindHolidayPlanException extends HttpException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(Response::HTTP_NOT_FOUND, "Unable to find the holiday plan", $previous);
    }
}
