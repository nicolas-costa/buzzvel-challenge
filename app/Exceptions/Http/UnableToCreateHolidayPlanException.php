<?php

declare(strict_types=1);

namespace App\Exceptions\Http;


use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class UnableToCreateHolidayPlanException extends HttpException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(Response::HTTP_INTERNAL_SERVER_ERROR, "Unable to create the holiday plan", $previous);
    }
}
