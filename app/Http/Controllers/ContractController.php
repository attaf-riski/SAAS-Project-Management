<?php

namespace App\Http\Controllers;

use App\Mail\MyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Contract;
use App\Models\Service;
use App\Models\ServiceDetail;
use App\Models\User;
use App\Models\ProjectModel;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class ContractController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan ID pengguna yang sedang login
        $userId = Auth::id();
        // Mengambil proyek yang dimiliki oleh pengguna yang sedang login
        $contracts = DB::table('contracts')
            ->where('contracts.id_user', $userId) // Filter berdasarkan user_id
            ->join('clients', 'contracts.id_client', '=', 'clients.id')
            ->select('contracts.*', 'clients.name as name')
            ->orderBy('contracts.created_at', 'desc')
            ->paginate(5);


        // Mengambil klien yang dimiliki oleh pengguna yang sedang login
        $clients = Client::where('user_id', $userId)->get();

         // if the request has data_count_shows
         if ($request->input('data_count_shows') != null) {
            $dataCountShows = $request->input('data_count_shows');
            $contracts = DB::table('contracts')
            ->where('contracts.id_user', $userId) // Filter berdasarkan user_id
            ->join('clients', 'contracts.id_client', '=', 'clients.id')
            ->select('contracts.*', 'clients.name as name')
            ->orderBy('contracts.created_at', 'desc')
            ->paginate($dataCountShows);   
            return view('workspace.contracts.index', compact('contracts', 'clients'));
        }
               // if the request has search
       if ($request->input('search') != null) {
        $contracts = DB::table('contracts')
            ->where('contracts.id_user', $userId) // Filter berdasarkan user_id
            ->where('contracts.contract_name', 'like', '%' . $request->search . '%')
            // ->orWhere('contracts.status', 'like', '%' . $request->search . '%')
            // ->orWhere('clients.name', 'like', '%' . $request->search . '%')
            ->join('clients', 'contracts.id_client', '=', 'clients.id')
            ->select('contracts.*', 'clients.name as name')
            ->orderBy('contracts.created_at', 'desc')
            ->paginate(5);  
        // $contracts = Contract::where('user_id', Auth::id())->where('contract_name', 'like', '%' . $request->search . '%')->paginate(5);
        return view('workspace.contracts.index', compact('contracts', 'clients'));
        }
            
        return view('workspace.contracts.index', compact('contracts', 'clients'));
    }

    public function showadd()
    {
        $userId = Auth::id();
        $clients = Client::where('user_id', $userId)->get();
        return view('workspace.contracts.addc', compact('clients'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'project_name' => 'required|string',
            'id_client' => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'final_invoice_date' => 'required|date',
            // Add more validation rules as needed
        ]);

        // Create a new Quotation instance
        $contract = new Contract();
        $contract->contract_name = $request->input('project_name');
        $contract->start_date = $request->input('start_date');

        if ($request->has('end_date')) {
            $contract->end_date = $request->input('end_date');
        } else {
            $contract->end_date = null;
        }

        $contract->status = 'NOT SENT';

        if(Auth::user()->id_role == 4){ // premium user
            $contract->contract_pdf = env('DEFAULT_TERM');
        }else{
            $contract->contract_pdf = 'DEFAULT';
        }
        
        $contract->id_client = $request->input('id_client');
        $contract->id_user = Auth::id();
        $contract->id_project = 1;
        $contract->final_invoice_date = $request->input('final_invoice_date');


        // deposit
        // Check if deposit information is provided
        if ($request->has('require_deposit')) {
            // Validate deposit percentage
            $request->validate([
                'deposit_percentage' => 'required|numeric|min:0|max:100',
            ]);

            // Calculate deposit amount
            $totalCost = 0;
            $servicePrices = $request->input('service_price');
            foreach ($servicePrices as $price) {
                $totalCost += $price;
            }
            $depositPercentage = $request->input('deposit_percentage');
            $depositAmount = ($depositPercentage / 100) * $totalCost;

            // Update the quotation with deposit information
            $contract->require_deposit = true;
            $contract->deposit_percentage = $depositPercentage;
            $contract->deposit_amount = $depositAmount;
            $contract->client_agrees_deposit = $request->has('client_agrees_deposit');
            $contract->save();
        } else {
            $contract->require_deposit = false;
            $contract->deposit_percentage = null;
            $contract->deposit_amount = null;
            $contract->client_agrees_deposit = false;
            $contract->save();
        }

        // Create each subscription
        $service = new Service();
        $service->id_contract = $contract->id;
        $service->id_project = 1;
        $service->id_quotation = 1;
        $service->save();

        // create each subscription detail
        $serviceNames = $request->input('service_name');
        $servicePrices = $request->input('service_price');
        $serviceFeeMethods = 'FIXED';
        $serviceDescriptions = $request->input('service_description');
        foreach ($serviceNames as $index => $serviceName) {
            $serviceDetail = new ServiceDetail();
            $serviceDetail->id_service = $service->id;
            $serviceDetail->service_name = $serviceName;
            $serviceDetail->price = $servicePrices[$index];
            $serviceDetail->pay_method = $serviceFeeMethods;
            $serviceDetail->description = $serviceDescriptions[$index];
            $serviceDetail->save();
        }

        // Redirect to the quotation index page
        return redirect()->route('workspace.contract.review', $contract->id);
    }

    public function review($id)
    {
        $contract = Contract::findOrFail($id);
        $services = Service::where('id_contract', $id)->get();
        $serviceDetails = ServiceDetail::where('id_service', $services[0]->id)->get();
        $total = $serviceDetails->sum('price');
        $contract->total = $total;
        $client = Client::find($contract->id_client);
        $user = User::find($contract->id_user);
        return view('workspace.contracts.contract', compact('contract', 'services', 'serviceDetails', 'client', 'user'));
    }

    public function sendemail($id)
    {
        $contract = Contract::findOrFail($id);
        $client = Client::find($contract->id_client);
        return view('workspace.contracts.sendmail', compact('contract', 'client'));
    }

    public function finishemail(Request $request, $id)
    {

        $contract = Contract::findOrFail($id);
        $contract->status = "SENT";
        $contract->save();
        $client = Client::find($contract->id_client);
        $user = User::find($contract->id_user);
        $services = Service::where('id_contract', $id)->get();
        $serviceDetails = ServiceDetail::where('id_service', $services[0]->id)->get();
        Mail::to($request->recipient)->send(new MyEmail($contract, $client, $user, $serviceDetails, $request->subject, $request->message));
        Alert::success('Success Message', 'You have successfully send email.');
        return redirect()->route('workspace.contract');
    }

    public function showUpdate($id)
    {
        $contract = Contract::findOrFail($id);
        $clients = Client::where('user_id', Auth::id())->get();
        $user = User::find($contract->id_user);
        $services = Service::where('id_contract', $id)->get();
        $serviceDetails = ServiceDetail::where('id_service', $services[0]->id)->get();
        return view('workspace.contracts.editcontract', compact('contract', 'clients', 'user', 'services', 'serviceDetails'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|string',
            'id_client' => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'final_invoice_date' => 'required|date',
            // Add more validation rules as needed
        ]);

        if ($validator->fails()) {
            Alert::error('Failed Message', 'You have failed update contract.');
            return redirect()->route('workspace.contract.showupdate', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Find the contract to update
        $contract = Contract::findOrFail($id); // assuming $id is the contract ID being updated

        // Update contract details
        $contract->contract_name = $request->input('project_name');
        $contract->start_date = $request->input('start_date');
        $contract->end_date = $request->input('end_date');
        $contract->id_client = $request->input('id_client');
        $contract->final_invoice_date = $request->input('final_invoice_date');

        // Update the user ID only if necessary
        if ($contract->id_user !== Auth::id()) {
            $contract->id_user = Auth::id();
        }

        // Update deposit information if provided
        if ($request->has('require_deposit')) {
            // Validate deposit percentage
            $request->validate([
                'deposit_percentage' => 'required|numeric|min:0|max:100',
            ]);

            // Calculate deposit amount
            $totalCost = 0;
            $servicePrices = $request->input('service_price');
            foreach ($servicePrices as $price) {
                $totalCost += $price;
            }
            $depositPercentage = $request->input('deposit_percentage');
            $depositAmount = ($depositPercentage / 100) * $totalCost;

            // Update contract with deposit information
            $contract->require_deposit = true;
            $contract->deposit_percentage = $depositPercentage;
            $contract->deposit_amount = $depositAmount;
            $contract->client_agrees_deposit = $request->has('client_agrees_deposit');
        } else {
            $contract->require_deposit = false;
            $contract->deposit_percentage = null;
            $contract->deposit_amount = null;
            $contract->client_agrees_deposit = false;
        }

        // Save the updated contract
        $contract->save();

        // ambil service 
        $service = Service::where('id_contract', $contract->id)->first();

        // hapus yang lama 
        ServiceDetail::where('id_service', $service->id)->delete();

        // masukkan yang baru
        // create each subscription detail
        $serviceNames = $request->input('service_name');
        $servicePrices = $request->input('service_price');
        $serviceFeeMethods = 'FIXED';
        $serviceDescriptions = $request->input('service_description');
        foreach ($serviceNames as $index => $serviceName) {
            $serviceDetail = new ServiceDetail();
            $serviceDetail->id_service = $service->id;
            $serviceDetail->service_name = $serviceName;
            $serviceDetail->price = $servicePrices[$index];
            $serviceDetail->pay_method = $serviceFeeMethods;
            $serviceDetail->description = $serviceDescriptions[$index];
            $serviceDetail->save();
        }

        return redirect()->route('workspace.contract.showeditterm', $contract->id);
    }

    public function showeditterm($id)
    {
        $contract = Contract::findOrFail($id);
        $services = Service::where('id_contract', $id)->get();
        $serviceDetails = ServiceDetail::where('id_service', $services[0]->id)->get();
        $total = $serviceDetails->sum('price');
        $contract->total = $total;
        $client = Client::find($contract->id_client);
        $user = User::find($contract->id_user);
        return view('workspace.contracts.editterm', compact('contract', 'services', 'serviceDetails', 'client', 'user'));
    }

    public function editterm(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);
        if(Auth::user()->id_role == 4){ // premium user
            $contract->contract_pdf = $request->term;
        }else{
            $contract->contract_pdf = 'DEFAULT';
        }
        $contract->save();
        Alert::success('Success Message', 'You have successfully update contract.');
        // Redirect to the contract review page

        return redirect()->route('workspace.contract', $contract->id);
    }

    public function accepted($id)
    {
        $contract = Contract::findOrFail($id);
        // check if contract need to pay firstly or not
        if ($contract->client_agrees_deposit == true) {
            // get subscription detail
            $services = Service::where('id_contract', $id)->get();
            $serviceDetails = ServiceDetail::where('id_service', $services[0]->id)->get();
            $client = Client::find($contract->id_client);
            $user = User::find($contract->id_user);


            // page pembayaran
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
                    'gross_amount' => $contract->deposit_amount,
                ),
                'customer_details' => array(
                    'first_name' => $user->fullname,
                    'email' => $user->email,
                ),
            );

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $contract['snap_token'] = $snapToken;

            return view('workspace.contracts.paidacceptpage', compact('contract'));
        } else if ($contract->client_agrees_deposit == false) {
            // page terimakasih
            $contract->status = "APPROVED";

            // create project based on contract
            $data['project_name'] = $contract->contract_name;
            $data['start_date'] = date('Y-m-d');
            $data['end_date'] = $contract->end_date;
            $data['status'] = 'ACTIVE';
            $data['id_client'] = $contract->id_client;
            $data['user_id'] = $contract->id_user;

            $project = ProjectModel::create($data);
            $contract->id_project = $project->id;
            $contract->save();

            // update service id project
            $service = Service::where('id_contract', $id)->first();
            $service->id_project = $contract->id_project;
            $service->save();

            return view('workspace.contracts.acceptpage');
        }

    }

    public function successpaiddpcontract($id)
    {
        $contract = Contract::find($id);
        $contract->status = "APPROVED";

        // create project based on contract
        $data['project_name'] = $contract->contract_name;
        $data['start_date'] = date('Y-m-d');
        $data['end_date'] = $contract->end_date;
        $data['status'] = 'ACTIVE';
        $data['id_client'] = $contract->id_client;
        $data['user_id'] = $contract->id_user;

         $contract = ProjectModel::create($data);

        $contract->id_project = $contract->id;
        $contract->save();

        // include transaction data
        $transaction = new Transaction();
        $transaction->id_project = $contract->id_project;
        $transaction->id_user = Auth::user()->id;
        $transaction->created_date = date('Y-m-d');
        $transaction->amount = $contract->deposit_amount;
        $transaction->description = "Deposit for " . $contract->contract_name;
        $transaction->source = Client::find($contract->id_client)->name;
        $transaction->category = "Deposit";
        $transaction->is_income = 1;
        $transaction->save();

        // update service id project
        $service = Service::where('id_contract', $id)->first();
        $service->id_project = $contract->id_project;
        $service->save();
        return view('workspace.contracts.acceptpage');
    }

    public function dismissed($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->status = "DISMISSED";
        $contract->save();
        return view('workspace.contracts.dismissedpage');
    }

    public function deleteContract(Request $request, $id)
    {
        $contract = Contract::find($id);
        $service = Service::where('id_contract', $id)->first();
        $service->id_contract = 1;
        $service->save();
        $contract->delete();
        Alert::success('Success Message', 'You have successfully delete contract.');
        return redirect()->route('workspace.contract');
    }

}
