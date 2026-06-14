<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterCompanyAction;
use App\Actions\Auth\RegisterPharmacyAction;
use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterCompanyDTO;
use App\DTOs\Auth\RegisterPharmacyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterCompanyRequest;
use App\Http\Requests\Auth\RegisterPharmacyRequest;

class AuthController extends Controller
{
    public function registerCompany(
        RegisterCompanyRequest $request,
        RegisterCompanyAction $action
    ) {
        $dto = RegisterCompanyDTO::fromRequest($request);
        $result = $action->execute($dto);

        return sendSuccessResponse(
            message: 'تم تسجيل الشركة بنجاح',
            data: $result,
            status: 201
        );
    }

    public function registerPharmacy(
        RegisterPharmacyRequest $request,
        RegisterPharmacyAction $action
    ) {
        $dto = RegisterPharmacyDTO::fromRequest($request);
        $result = $action->execute($dto);

        return sendSuccessResponse(
            message: 'تم تسجيل الصيدلية بنجاح',
            data: $result,
            status: 201
        );
    }

    public function login(
        LoginRequest $request,
        LoginAction $action
    ) {
        $dto = LoginDTO::fromRequest($request);
        $result = $action->execute($dto);

        return sendSuccessResponse(
            message: 'تم تسجيل الدخول بنجاح',
            data: $result
        );
    }
}
