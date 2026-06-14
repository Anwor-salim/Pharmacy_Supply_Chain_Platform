<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class CompanyManagementController extends Controller
{
    public function index(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // التحقق من أن المستخدم أدمن
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'غير مصرح لك بالقيام بهذا الإجراء'], 403);
        }

        $companies = Company::all();

        return sendSuccessResponse(
            message: 'تم جلب جميع الشركات بنجاح',
            data: $companies
        );
    }

    public function toggleStatus(Company $company): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // التحقق من أن المستخدم أدمن
        if ($user->role !== 'admin') {
            return response()->json(['message' => 'غير مصرح لك بالقيام بهذا الإجراء'], 403);
        }

        $company->status = !$company->status;
        $company->save();

        $statusLabel = $company->status ? 'تفعيل' : 'إلغاء تفعيل';

        return sendSuccessResponse(
            message: "تم {$statusLabel} الشركة بنجاح",
            data: $company
        );
    }
}
