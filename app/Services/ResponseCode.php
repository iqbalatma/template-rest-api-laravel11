<?php

namespace App\Services;

use Iqbalatma\LaravelUtils\Interfaces\ResponseCodeInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method static ResponseCodeInterface ERR_NOT_FOUND()
 */
class ResponseCode extends \Iqbalatma\LaravelUtils\ResponseCode
{
    protected const string ERR_NOT_FOUND = "ERR_NOT_FOUND";


    /**
     * @return void
     */
    protected function mapHttpCode(): void
    {
        $this->httpCode = match ($this->name) {
            self::ERR_NOT_FOUND => Response::HTTP_NOT_FOUND,
            default => null
        };

        if ($this->httpCode === null) {
            parent::mapHttpCode();
        }
    }
}
