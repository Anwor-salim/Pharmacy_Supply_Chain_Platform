<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1;

use App\Actions\Invoices\ConfirmInvoiceAction;
use App\Actions\Invoices\DeleteInvoiceAction;
use App\Actions\Invoices\IndexInvoiceAction;
use App\Actions\Invoices\ShowInvoiceAction;
use App\Actions\Invoices\StoreInvoiceAction;
use App\Actions\Invoices\StoreReturnInvoiceAction;
use App\Actions\Payments\StorePaymentAction;
use App\DTOs\Invoices\StoreInvoiceDTO;
use App\DTOs\Invoices\StoreReturnInvoiceDTO;
use App\DTOs\Payments\StorePaymentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\StoreInvoiceRequest;
use App\Http\Requests\Invoices\StoreReturnInvoiceRequest;
use App\Http\Requests\Payments\StorePaymentRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class InvoiceController extends Controller
{
    public function index(Request $request, IndexInvoiceAction $action): JsonResponse
    {
        $invoices = $action->execute(
            type:        $request->query('type'),
            status:      $request->query('status'),
            customer_id: $request->query('customer_id') ? (int) $request->query('customer_id') : null
        );

        return sendSuccessResponse(
            message: 'تم جلب قائمة الفواتير بنجاح',
            data:    InvoiceResource::collection($invoices)
        );
    }

    public function store(StoreInvoiceRequest $request, StoreInvoiceAction $action): JsonResponse
    {
        $dto = StoreInvoiceDTO::fromRequest($request);
        $invoice = $action->execute($dto);

        return sendSuccessResponse(
            message: 'تم إنشاء الفاتورة بنجاح',
            data:    new InvoiceResource($invoice->load('items')),
            status:  201
        );
    }

    public function confirm(Invoice $invoice, ConfirmInvoiceAction $action): JsonResponse
    {
        if ($response = $this->checkInvoiceOwnership($invoice)) {
            return $response;
        }

        $invoice = $action->execute($invoice);

        return sendSuccessResponse(
            message: 'تم تأكيد الفاتورة بنجاح',
            data:    new InvoiceResource($invoice->load('items'))
        );
    }

    public function storeReturn(StoreReturnInvoiceRequest $request, StoreReturnInvoiceAction $action): JsonResponse
    {
        $dto = StoreReturnInvoiceDTO::fromRequest($request);
        $invoice = $action->execute($dto);

        return sendSuccessResponse(
            message: 'تم إنشاء فاتورة المرتجع بنجاح',
            data:    new InvoiceResource($invoice),
            status:  201
        );
    }

    public function show(Invoice $invoice, ShowInvoiceAction $action): JsonResponse
    {
        if ($response = $this->checkInvoiceOwnership($invoice)) {
            return $response;
        }

        $invoice = $action->execute($invoice);

        return sendSuccessResponse(
            message: 'تم جلب تفاصيل الفاتورة بنجاح',
            data:    new InvoiceResource($invoice)
        );
    }

    public function destroy(Invoice $invoice, DeleteInvoiceAction $action): JsonResponse
    {
        if ($response = $this->checkInvoiceOwnership($invoice)) {
            return $response;
        }

        $action->execute($invoice);

        return sendSuccessResponse(
            message: 'تم حذف الفاتورة بنجاح'
        );
    }

    public function storePayment(
        Invoice $invoice,
        StorePaymentRequest $request,
        StorePaymentAction $action
    ): JsonResponse {
        if ($response = $this->checkInvoiceOwnership($invoice)) {
            return $response;
        }

        $dto = StorePaymentDTO::fromRequest($request, (int) $invoice->id);
        $payment = $action->execute($dto);

        return sendSuccessResponse(
            message: 'تم تسجيل الدفعة بنجاح',
            data:    $payment,
            status:  201
        );
    }
    
    public function indexPayments(Invoice $invoice): JsonResponse
    {
        if ($response = $this->checkInvoiceOwnership($invoice)) {
            return $response;
        }

        return sendSuccessResponse(
            message: 'تم جلب سجل المدفوعات بنجاح',
            data:    $invoice->payments()->latest()->get()
        );
    }

    private function checkInvoiceOwnership(Invoice $invoice): ?JsonResponse
    {
        $company = auth()->user()->company;
        if (!$company || $invoice->company_id !== $company->id) {
            return response()->json(['message' => 'غير مصرح لك بالوصول لهذه الفاتورة'], 403);
        }
        return null;
    }
}
