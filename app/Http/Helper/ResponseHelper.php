<?php
/**
 * @author Lam Kai Loon <lkloon123@hotmail.com>
 */

namespace App\Http\Helper;


class ResponseHelper
{
    public static function success($data, $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data
        ], $status);
    }
}