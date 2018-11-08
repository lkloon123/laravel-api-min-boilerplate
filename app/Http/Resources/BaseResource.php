<?php

namespace App\Http\Resources;

use Config;
use Illuminate\Http\Resources\Json\Resource;

class BaseResource extends Resource
{
    protected function dateTimeData()
    {
        $res = [
            'created_at' => isset($this->created_at) ? $this->created_at->format(Config::get('app.datetime_format')) : null,
            'updated_at' => isset($this->updated_at) ? $this->updated_at->format(Config::get('app.datetime_format')) : null,
            'deleted_at' => isset($this->deleted_at) ? $this->deleted_at->format(Config::get('app.datetime_format')) : null,
        ];

        if (!isset($this->deleted_at))
            return array_except($res, 'deleted_at');

        return $res;
    }
}
