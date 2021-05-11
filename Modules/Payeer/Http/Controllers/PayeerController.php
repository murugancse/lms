<?php

namespace Modules\Payeer\Http\Controllers;

use App\Http\Controllers\DepositController;
use App\Http\Controllers\PaymentController;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Payeer\Entities\PayeerOrder;

class PayeerController extends Controller
{
    public function makePayment($request)
    {
        $responce = '';
        $order_id = uniqid();

        if ($request->type == "Deposit") {
            $m_desc = base64_encode('Deposit');
            $payAmount = $request->deposit_amount;
//            $amount = convertCurrency(getSetting()->currency->code ?? 'USD', 'USD', $request->deposit_amount);
        } else {
            $m_desc = base64_encode('Payment');
            $payAmount = $request->amount;
//            $amount = convertCurrency(getSetting()->currency->code ?? 'USD', 'USD', $request->amount);
        }

        $m_shop = env('PAYEER_MERCHANT') ?? '';
        $m_orderid = $order_id;
        $m_amount = number_format($payAmount, 2, '.', '');
        $m_curr = getSetting()->currency->code;

        $m_key = env('PAYEER_KEY') ?? '';


        /* $arParams = array(
             'success_url' => 'http://google.com/new_success_url',
             'fail_url' => 'http://google.com/new_fail_url',
             'status_url' => 'http://google.com/new_status_url',
             // Forming an array for additional fields
             'reference' => array(
                 'var1' => '1',
                 'var2' => '2',
                 'var3' => '3',
                 'var4' => '4',
                 'var5' => '5',
             ),

         );*/


        $arHash = array(
            $m_shop,
            $m_orderid,
            $m_amount,
            $m_curr,
            $m_desc,
            $m_key
        );
        $sign = strtoupper(hash('sha256', implode(":", $arHash)));


        $arGetParams = array(
            'm_shop' => $m_shop,
            'm_orderid' => $m_orderid,
            'm_amount' => $m_amount,
            'm_curr' => $m_curr,
            'm_desc' => $m_desc,
            'm_sign' => $sign,

        );

        $order = new PayeerOrder();
        $order->type = $request->type;
        $order->order_id = $order_id;
        $order->user_id = Auth::user()->id;
        $order->amount = $payAmount;
        $order->save();
        return 'https://payeer.com/merchant/?' . http_build_query($arGetParams);
    }


    public function paymentSuccess(Request $request)
    {


        $order = PayeerOrder::where('user_id', Auth::user()->id)->latest()->first();

        if ($order) {

            if ($order->type == "Deposit") {
                $deposit = new DepositController();
                $payWithMidtrans = $deposit->depositWithGateWay($order->amount, $request, "Payeer");

            } else {
                $payment = new PaymentController();
                $payWithMidtrans = $payment->payWithGateWay($request, "Payeer");
            }

            $order->delete();

            if ($payWithMidtrans) {
                Toastr::success('Payment done successfully', 'Success');
                return redirect(route('studentDashboard'));
            } else {
                Toastr::error('Something Went Wrong', 'Error');
                return redirect(route('studentDashboard'));
            }

        } else {
            Toastr::error('Something Went Wrong', 'Error');
            return redirect(route('studentDashboard'));
        }


    }


    public function paymentFailed()
    {
        $order = PayeerOrder::where('user_id', Auth::user()->id)->latest()->first();
        if ($order) {
            $order->delete();
        }
        Toastr::error('Payment Failed .', 'Failed');
        return redirect()->back();
    }
}
