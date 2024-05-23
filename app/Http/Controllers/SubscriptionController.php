<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Subscription;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;


class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = DB::table('subscriptions')
            ->join('users', 'subscriptions.id_user', '=', 'users.id')
            ->join('plans', 'subscriptions.id_plan', '=', 'plans.id')
            ->select('subscriptions.*', 'users.fullname as fullname', 'plans.plan_name as plan_name')
            ->orderBy('subscriptions.created_at', 'desc')
            ->paginate(5);
        $freelances = User::where('id_role', 3)->get();
        $plans = Plan::all();
        return view('admin.subscription.index', compact('subscriptions', 'freelances', 'plans'));
    }

    public function store(Request $request)
    {
        $startDate = null;
        $endDate = null;
        $validator = null;

        if (is_null($request->duration)) {
            $validator = Validator::make($request->all(), [
                'id_user' => ['required'],
                'id_plan' => ['required'],
                'start_date' => ['required'],
                'end_date' => ['required'],
            ]);

            $startDate = $request->start_date;
            $endDate = $request->end_date;
            // difference between startDate and endDate in months
            $diff = abs(strtotime($endDate) - strtotime($startDate));
            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor($diff / (30 * 60 * 60 * 24));
            $data['duration'] = $months;
        } else {
            $validator = Validator::make($request->all(), [
                'id_user' => ['required'],
                'id_plan' => ['required'],
                'duration' => ['required'],
            ]);

            $startDate = date('Y-m-d');
            $endDate = date('Y-m-d', strtotime($startDate . ' + ' . $request->duration . ' months'));
            $data['duration'] = $request->duration;
        }

        if ($validator->fails()) {
            $error = "You have failed add new subscription.\n" . strval($validator->errors());
            Alert::error('Failed Message', $error);
            return redirect()->route('admin.subscription.show');
        }

        $data['id_user'] = $request->id_user;
        $data['id_plan'] = $request->id_plan;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;

        if (!$data) {
            dd('error');
        } else {
            $result = Subscription::create($data);
            if ($result) {
                // tambah ke table transaction
                $dataTransaction['id_subscription'] = $result->id;
                $dataTransaction['id_user'] = $request->id_user;
                $plan = Plan::find($request->id_plan);
                $dataTransaction['amount'] = $plan->price;
                $dataTransaction['date'] = date('Y-m-d');
                $dataTransaction['status'] = 'PAID';
                DB::table('transaction_admins')->insert($dataTransaction);

                Alert::success('Success Message', 'You have successfully add new subscription.');
                return redirect()->route('admin.subscription.show');
            } else {
                Alert::error('Failed Message', 'You have failed add new subscription.');
                return redirect()->route('admin.subscription.show');
            }
        }



    }

    public function destroy($id)
    {
        $subscription = Subscription::find($id);

        if (!$subscription) {
            Alert::error('Failed Message', 'You have failed delete subscription.');
            return redirect()->route('admin.subscription.show');
        }

        try {
            $subscription->delete();
        } catch (\Throwable $th) {
            if($th->getCode() == 23000){
                Alert::error('Failed Message', 'You have failed delete subscription. Subscription is being used by transaction.');
                return redirect()->route('admin.subscription.show');
            }
        }


        Alert::success('Success Message', 'You have successfully delete subscription.');
        return redirect()->route('admin.subscription.show');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => ['required'],
            'id_plan' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
        ]);

        if ($validator->fails()) {
            $error = "You have failed update subscription.\n" . strval($validator->errors());
            Alert::error('Failed Message', $error);
            return redirect()->route('admin.subscription.show');
        }

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        // difference between startDate and endDate in months
        $diff = abs(strtotime($endDate) - strtotime($startDate));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor($diff / (30 * 60 * 60 * 24));
        $data['duration'] = $months;


        $data['id_user'] = $request->id_user;
        $data['id_plan'] = $request->id_plan;
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;
        // dd($data);

        $subscription = Subscription::find($id);

        if (!$subscription) {
            Alert::error('Failed Message', 'You have failed update subscription.');
            return redirect()->route('admin.subscription.show');
        }

        $result = $subscription->update($data);
        if ($result) {



            Alert::success('Success Message', 'You have successfully update subscription.');
            return redirect()->route('admin.subscription.show');
        } else {
            Alert::error('Failed Message', 'You have failed update subscription.');
            return redirect()->route('admin.subscription.show');
        }
    }

    public function upgradeshow()
    {
        // $plans = Plan::where('id', '!=', 1)->get();
        $plans = Plan::all();

        $currentPlan = 0;

        if (Auth::user()->id_role == 4) {
            $currentPlan = 2;
        } else if (Auth::user()->id_role == 3) {
            $currentPlan = 1;
        }

        return view('workspace.upgrade.index', compact('plans', 'currentPlan'));
    }

    public function upgrade($planid)
    {
        $plan = Plan::find($planid);

        // cari subscription lama yang belum dibayar
        $subscriptions = Subscription::where('id_user', Auth::user()->id)
            ->where('status', 'PENDING')->get();

        foreach ($subscriptions as $subscription) {
            $subscription->status = "NOT PAID";
            $subscription->save();
        }

        // masukkan subscription table
        $subscription = Subscription::create([
            'id_user' => Auth::user()->id,
            'id_plan' => $plan->id,
            'duration' => 12,
            'status' => "PENDING", // belum dibayar pending dulu yee
            'start_date' => now(),
            'end_date' => now()->addMonths(12),
        ]);

        // masukkan transaction_admins table
        if ($subscription) {
            // tambah ke table transaction
            $dataTransaction['id_subscription'] = $subscription->id;
            $dataTransaction['id_user'] = $subscription->id_user;
            $plan = Plan::find($subscription->id_plan);
            $dataTransaction['amount'] = $plan->price;
            $dataTransaction['date'] = now();
            $dataTransaction['status'] = 'PENDING';

            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = config('midtrans.isProduction');
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = config('midtrans.is3ds');

            
            $params = array(
                'transaction_details' => array(
                    'order_id' => rand(),
                    'gross_amount' => $plan->price,
                ),
                'customer_details' => array(
                    'first_name' => Auth::user()->fullname,
                    'email' => Auth::user()->email,
                ),
            );
            
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $dataTransaction['snap_token'] = $snapToken;

            // cari payment lama yang belum dibayar dan pending
            $transactionAdmins = TransactionAdmin::where('id_user', Auth::user()->id)
                ->where('status', 'PENDING')->get();

            foreach ($transactionAdmins as $transactionAdmin) {
                $transactionAdmin->status = "CANCEL";
                $transactionAdmin->save();
            }


            $transactionAdmin = TransactionAdmin::create($dataTransaction);

            if ($transactionAdmin) {
                return redirect()->route('workspace.subscriptions.bayar', $transactionAdmin->id);
            }
            
        } else {
            Alert::error('Failed Message', 'You have failed to upgrade.');
            return redirect()->route('workspace.subscriptions.upgradeshow');
        }




    }

    public function bayar($transactionid){
        $transaction = TransactionAdmin::find($transactionid);
        if (!$transaction) {
            Alert::error('Failed Message', 'You have failed to pay.');
            return redirect()->route('workspace.subscriptions.upgradeshow');
        }

        return view('workspace.upgrade.bayar', compact('transaction'));
    }

    public function success($transactionId){
        $transactionadmin = TransactionAdmin::find($transactionId);
        if (!$transactionadmin) {
            Alert::error('Failed Message', 'You have failed to pay.');
            return redirect()->route('workspace.subscriptions.upgradeshow');
        }

        // change freelance role to 4, premium

        $user = Auth::user();
        $user->id_role = 4;
        $user->save();

        $transactionadmin->status = "PAID";
        $transactionadmin->save();

        // change subscription status to ACCESS
        $subscription = Subscription::find($transactionadmin->id_subscription);
        $subscription->status = "ACTIVE";
        $subscription->start_date = now();
        $subscription->end_date = now()->addMonths($subscription->duration);
        $subscription->save();

        // add transaction expense type
        $expense = new Transaction();
        $expense->id_project = 1;
        // buatkan nota pembelian
        $expense->id_user = Auth::user()->id;
        $expense->created_date = now();
        $expense->amount = $transactionadmin->amount;
        $expense->is_income = 0;
        $expense->description = "Upgrade to Premium";
        $expense->category = "Subscription";
        $expense->source = "Dana Pribadi";
        $expense->save();

        return view('workspace.upgrade.success');
    }
}
