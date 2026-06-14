<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Auth;

use App\Actions\Auth\LoginAction;
use App\DTOs\Auth\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;

final class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $dto = LoginDTO::fromRequest($request);
        $result = app(LoginAction::class)($dto);

        return sendSuccessResponse(
            message: 'تم تسجيل الدخول بنجاح',
            data: $result,
        );
    }
}
