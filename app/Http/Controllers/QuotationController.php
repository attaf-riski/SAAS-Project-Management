<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    public function index(){
        // Mendapatkan ID pengguna yang sedang login
        $userId = Auth::id();
        // Mengambil proyek yang dimiliki oleh pengguna yang sedang login
        $quotations = DB::table('quotations')
            ->where('quotations.id_user', $userId) // Filter berdasarkan user_id
            ->join('clients', 'quotations.id_client', '=', 'clients.id')
            ->select('quotations.*', 'clients.name as name')
            ->paginate(5);

        
        // Mengambil klien yang dimiliki oleh pengguna yang sedang login
        $clients = Client::where('user_id', $userId)->get();
        return view('workspace.quotation.index', compact('quotations', 'clients'));
    }

    public function showadd(){
        $userId = Auth::id();
        $clients = Client::where('user_id', $userId)->get();
        return view('workspace.component.addqc', compact('clients'));
    }

    public function create(){
        return view('workspace.quotations.create');
    }

    public function store(Request $request){
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
        $quotation = new Quotation();
        $quotation->quotation_name = $request->input('project_name');
        $quotation->start_date = $request->input('start_date');
        $quotation->end_date = $request->input('end_date');
        $quotation->status = 'PENDING';
        $quotation->quotation_pdf = '';
        $quotation->id_client = $request->input('id_client');
        $quotation->id_user = Auth::id();
        $quotation->id_project = 1;
        $quotation->save();

        // Create each subscription

    }

    public function edit($id){
        $client = Client::find($id);

        return view('workspace.clients.edit', compact('client'));
    }

    public function update(Request $request, $id){
        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'no_telp' => $request->no_telp,
        ];

        Client::find($id)->update($data);

        return redirect()->route('workspace.clients');
    }

    public function show($id){
        $client = Client::find($id);

        return view('workspace.clients.show', compact('client'));
    }

    public function status($id, $status){
       // update status
         $quotation = Quotation::find($id);
            $quotation->status = $status;
            $quotation->save();
        return redirect()->route('workspace.quotation');
    }
}
