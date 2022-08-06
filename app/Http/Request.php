<?php

namespace App\Http;

use Illuminate\Http\Request as BaseRequest;
use Illuminate\Support\Str;

class Request extends BaseRequest
{
    public function wantsJson(): bool
    {
        return true;
        return Str::startsWith( $this->path(), 'api/') || parent::wantsJson();
    }
}
