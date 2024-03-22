<?php

declare(strict_types=1);

namespace App\Exceptions\Http;


use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class UnableToUpdateHolidayPlanException extends HttpException
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct(Response::HTTP_INTERNAL_SERVER_ERROR, "Unable to update the holiday plan", $previous);
    }
}
