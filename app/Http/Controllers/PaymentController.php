<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
 public function createOrder(Request $request)
    {
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $order = $api->order->create([
            'receipt' => 'order_' . time(),
            'amount' => $request->amount * 100,
            'currency' => 'INR'
        ]);

        // ✅ Save pending payment
        $payment = Payment::create([
            'application_id' => $request->application_id,
            'razorpay_order_id' => $order['id'],
            'amount' => $request->amount,
            'service_fee' => $request->service_fee ?? 0,
            'status' => 'pending',
        ]);

        return response()->json([
            'id' => $order['id'],
            'amount' => $order['amount']
        ]);
    }
 public function paymentSuccess(Request $request)
    {
        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        try {
            // ✅ Verify signature (VERY IMPORTANT)
            $api->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature
            ]);

            // ✅ Update payment
            $payment = Payment::where('razorpay_order_id', $request->razorpay_order_id)->first();

            if ($payment) {
                $payment->update([
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature'  => $request->razorpay_signature,
                    'status'              => 'success',
                ]);
            }

            // ✅ Mark application paid
            \App\Models\VisaApplication::where('id', $payment->application_id)
                ->update(['payment_status' => 'paid','payment_gateway_ref'=>$payment->id]);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {


            // ❌ mark failed
            Payment::where('razorpay_order_id', $request->razorpay_order_id)
                ->update(['status' => 'failed']);

            return response()->json(['status' => 'failed'], 400);
        }
    }

    public function paymentInvoice(Request $request)
    {
        $applicationId = $request->app_id;

        $application = \App\Models\VisaApplication::with([
            'travelers.documents',
            'visaPayment',
            'country'
        ])->findOrFail($applicationId);

        return view('admin.visa-apply.invoice', compact('application'));
    }

    public function downloadInvoice($id)
    {
        $application = \App\Models\VisaApplication::with([
            'travelers',
            'visaPayment'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('admin.visa-apply.invoice', compact('application'));

        return $pdf->download('Invoice-' . $application->application_ref . '.pdf');
    }
        
}
