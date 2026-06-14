<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;

final class BrowseCompaniesController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $companies = Company::where('status',true)->get();

        return sendSuccessResponse(
            message: 'تم جلب قائمة الشركات بنجاح',
            data: $companies
        );
    }
}
