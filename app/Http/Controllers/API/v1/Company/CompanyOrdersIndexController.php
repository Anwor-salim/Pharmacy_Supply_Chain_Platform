<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Company;

use App\Actions\Orders\GetCompanyOrdersAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CompanyOrdersIndexController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user    = $request->user();
        $company = $user->userable;
        $status  = $request->query('status', '');

        $orders = app(GetCompanyOrdersAction::class)($company, $status);

        return sendSuccessResponse(
            message: __('messages.get_data'),
            data: $orders,
        );
    }
}
