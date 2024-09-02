<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Karim007\LaravelBkashTokenize\Facade\BkashPaymentTokenize;
use Karim007\LaravelBkashTokenize\Facade\BkashRefundTokenize;
use Log;

class BkashTokenizePaymentController extends Controller
{
    public function index()
    {
        return view('bkashT::bkash-payment');
    }
    public function createPayment(Request $request)
    {
        $amount = $request->input('amount'); // Use input() method for consistency

        $inv = uniqid();
        $requestData = [
            'intent' => 'sale',
            'mode' => '0011',
            'payerReference' => $inv,
            'currency' => 'BDT',
            'amount' => $amount,
            'merchantInvoiceNumber' => $inv,
            'callbackURL' =>route('bkash-callBack'),
        ];

        $request_data_json = json_encode($requestData);

        // Call the bKash API
        $response = BkashPaymentTokenize::cPayment($request_data_json);
        // dd($response);
        // Check if response is an array
        if (is_array($response)) {
            $paymentId = $response['paymentID'] ?? null;
            $bkashURL = $response['bkashURL'] ?? null;

            // dd($paymentId); // Debug the payment ID

            if ($paymentId && $bkashURL) {
                return redirect()->away($bkashURL); // Redirect to bKash payment page
            } else {
                return response()->json(['status' => 'error', 'message' => 'Payment ID or bkashURL not received']);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Invalid response format']);
        }
    }




    public function callBack(Request $request)
    {

        // Log the entire request data to inspect what bKash is sending
  // Log the entire request data to inspect what bKash is sending
  Log::info('bKash Callback Data:', $request->all());

  // Extracting the transaction details sent by bKash
  $transactionId = $request->input('trxID') ?? $request->input('transaction_id') ?? null;
  $paymentId = $request->input('paymentID');
  $status = $request->input('transactionStatus');

  dd($transactionId); // Debug the transaction ID





        // if ($request->status == 'success'){
        //     $response = BkashPaymentTokenize::executePayment($request->paymentID);
        //     //$response = BkashPaymentTokenize::executePayment($request->paymentID, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
        //     if (!$response){ //if executePayment payment not found call queryPayment
        //         $response = BkashPaymentTokenize::queryPayment($request->paymentID);
        //         //$response = BkashPaymentTokenize::queryPayment($request->paymentID,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
        //     }

        //     if (isset($response['statusCode']) && $response['statusCode'] == "0000" && $response['transactionStatus'] == "Completed") {
        //         /*
        //          * for refund need to store
        //          * paymentID and trxID
        //          * */
        //         return BkashPaymentTokenize::success('Thank you for your payment', $response['trxID']);
        //     }
        //     return BkashPaymentTokenize::failure($response['statusMessage']);
        // }else if ($request->status == 'cancel'){
        //     return BkashPaymentTokenize::cancel('Your payment is canceled');
        // }else{
        //     return BkashPaymentTokenize::failure('Your transaction is failed');
        // }
    }

    public function searchTnx($trxID)
    {
        //response
        return BkashPaymentTokenize::searchTransaction($trxID);
        //return BkashPaymentTokenize::searchTransaction($trxID,1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }

    public function refund(Request $request)
    {
        $paymentID='';
        $trxID='your transaction no';
        $amount=5;
        $reason='this is test reason';
        $sku='abc';
        //response
        return BkashRefundTokenize::refund($paymentID,$trxID,$amount,$reason,$sku);
        //return BkashRefundTokenize::refund($paymentID,$trxID,$amount,$reason,$sku, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }
    public function refundStatus(Request $request)
    {
        $paymentID='Your payment id';
        $trxID='your transaction no';
        return BkashRefundTokenize::refundStatus($paymentID,$trxID);
        //return BkashRefundTokenize::refundStatus($paymentID,$trxID, 1); //last parameter is your account number for multi account its like, 1,2,3,4,cont..
    }
}
