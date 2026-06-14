<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Company;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

final class GetCompanyOrdersAction
{
    public function __invoke(Company $company, string $status = ''): LengthAwarePaginator
    {
        $query = $company->orders()
            ->with(['pharmacy', 'items'])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate(10);
    }
}
