<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Auth;

use App\Actions\Auth\RegisterCompanyAction;
use App\DTOs\Auth\RegisterCompanyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterCompanyRequest;
use Illuminate\Http\JsonResponse;

final class RegisterCompanyController extends Controller
{
    public function __invoke(RegisterCompanyRequest $request): JsonResponse
    {
        $dto = RegisterCompanyDTO::fromRequest($request);
        $result = app(RegisterCompanyAction::class)($dto);

        return sendSuccessResponse(
            message: 'تم تسجيل الشركة بنجاح',
            data: $result,
            status: 201
        );
    }
}
