<?php
namespace App\Exceptions;

use Throwable;

class FileNotFoundException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = $message ?? __('file_not_found');
        parent::__construct($message, $code, $previous);
    }

}
