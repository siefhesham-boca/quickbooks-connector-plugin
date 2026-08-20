<?php

namespace Bocapro\QuickbooksConnector\Exceptions;

use RuntimeException;

class QuickbooksException extends RuntimeException
{
    public static function fromSdkError(object $error): self
    {
        $message = method_exists($error, 'getResponseBody')
            ? (string) $error->getResponseBody()
            : 'Unknown QuickBooks Online API error.';

        $code = method_exists($error, 'getHttpStatusCode')
            ? (int) $error->getHttpStatusCode()
            : 0;

        return new self(
            "QuickBooks Online API error [{$code}]: {$message}",
            $code,
        );
    }
}
