<?php

namespace App\Exceptions;

use App\Services\V1\ResponseCode;
use Exception;
use Iqbalatma\LaravelUtils\APIResponse;
use Throwable;

class ForbiddenActionException extends Exception
{
    public function __construct(string $message = "This action is forbidden", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return APIResponse
     */
    public function render(): APIResponse
    {
        return new APIResponse(
            null,
            $this->getMessage(),
            responseCode: ResponseCode::ERR_FORBIDDEN(),
            exception: $this
        );
    }
}
