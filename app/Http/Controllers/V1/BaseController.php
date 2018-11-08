<?php

namespace App\Http\Controllers\V1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function validateUser($user)
    {
        if ($user === null) {
            throw new NotFoundHttpException('user not found');
        }

        return true;
    }

    protected function validateModel($model)
    {
        if ($model === null) {
            throw new HttpException(500);
        }

        return true;
    }

    protected function saveModel(Model $model)
    {
        if (!$model->save()) {
            throw new HttpException(500);
        }

        return true;
    }
}
