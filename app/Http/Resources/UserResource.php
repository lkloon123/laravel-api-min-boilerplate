<?php

namespace App\Http\Resources;

/**
 * Class User
 *
 * @mixin \App\Model\User
 * */
class UserResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'email' => $this->email,
        ], $this->dateTimeData());
    }
}
