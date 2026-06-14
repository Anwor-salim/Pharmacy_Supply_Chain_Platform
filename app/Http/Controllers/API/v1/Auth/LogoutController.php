<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return sendSuccessResponse(
            message: 'تم تسجيل الخروج بنجاح'
        );
    }
}
