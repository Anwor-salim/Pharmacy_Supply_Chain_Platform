<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Auth;

use App\Actions\Auth\RegisterPharmacyAction;
use App\DTOs\Auth\RegisterPharmacyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterPharmacyRequest;
use Illuminate\Http\JsonResponse;

final class RegisterPharmacyController extends Controller
{
    public function __invoke(RegisterPharmacyRequest $request): JsonResponse
    {
        $dto = RegisterPharmacyDTO::fromRequest($request);
        $result = app(RegisterPharmacyAction::class)($dto);

        return sendSuccessResponse(
            message: 'تم تسجيل الصيدلية بنجاح',
            data: $result,
            status: 201
        );
    }
}
