<?php

namespace App\Http\Controllers\Admin;

use App\Filters\VendorFilter;
use App\GlobalConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Models\Vendor;
use App\Imports\VendorsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create vendor'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit vendor'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete vendor'],['only' => 'destroy']);
        $this->middleware(['permission:list vendor'],['only' => 'index']);
    }
    public function index(VendorFilter $filter)
    {
        $vendorQuery = Vendor::query();

        $vendors = $vendorQuery->filter($filter)->latest('id')->paginate();
        $COUNTRY = GlobalConstants::COUNTRY;
        return view('admin.vendor.index', compact('vendors', 'COUNTRY'));
    }

    public function create()
    {
        $INVOICE_DUE_DATE = GlobalConstants::INVOICE_DUE_DATE;
        $COUNTRY = GlobalConstants::COUNTRY;
        return view('admin.vendor.create', compact('INVOICE_DUE_DATE','COUNTRY'));
    }

    
    public function store(CreateVendorRequest $request)
    {
        $data = $request->validated();
        if (isset($request->status)) {
            $data['status'] = 'yes';
        }
        else{
            $data['status'] = 'no';
        }

        if($request->invoice_due_date != null){
            $data['invoice_due_date'] = $request->invoice_due_date;
        }

        if($request->address_line_1 != null){
            $data['address_line_1'] = $request->address_line_1;
        }

        if($request->address_line_2 != null){
            $data['address_line_2'] = $request->address_line_2;
        }

        if($request->city != null){
            $data['city'] = $request->city;
        }

        if($request->state != null){
            $data['state'] = $request->state;
        }

        if($request->country_code != null){
            $data['country_code'] = $request->country_code;
        }

        if($request->postal_code != null){
            $data['postal_code'] = $request->postal_code;
        }

        if($request->phone != null){
            $data['phone'] = $request->phone;
        }

        if($request->cell != null){
            $data['cell'] = $request->cell;
        }

        if($request->fax != null){
            $data['fax'] = $request->fax;
        }

        if($request->email != null){
            $data['email'] = $request->email;
        }

        if($request->notes != null){
            $data['notes'] = $request->notes;
        }

        $vendor = Vendor::create($data);
        return redirect()
            ->route('vendor.index')
            ->with('success', 'New Vendor successfully.');
    }

    public function edit(Vendor $vendor)

    {
        $INVOICE_DUE_DATE = GlobalConstants::INVOICE_DUE_DATE;
        $COUNTRY = GlobalConstants::COUNTRY;
        return view('admin.vendor.edit', compact('vendor','INVOICE_DUE_DATE','COUNTRY'));
    }

    public function update(UpdateVendorRequest $request,Vendor $vendor)

    {
        $data = $request->validated();
        if (isset($request->status)) {
            $data['status'] = 'yes';
        }
        else{
            $data['status'] = 'no';
        }

        if($request->invoice_due_date != null){
            $data['invoice_due_date'] = $request->invoice_due_date;
        }

        if($request->address_line_1 != null){
            $data['address_line_1'] = $request->address_line_1;
        }

        if($request->address_line_2 != null){
            $data['address_line_2'] = $request->address_line_2;
        }

        if($request->city != null){
            $data['city'] = $request->city;
        }

        if($request->state != null){
            $data['state'] = $request->state;
        }

        if($request->country_code != null){
            $data['country_code'] = $request->country_code;
        }

        if($request->postal_code != null){
            $data['postal_code'] = $request->postal_code;
        }

        if($request->phone != null){
            $data['phone'] = $request->phone;
        }

        if($request->cell != null){
            $data['cell'] = $request->cell;
        }

        if($request->fax != null){
            $data['fax'] = $request->fax;
        }

        if($request->email != null){
            $data['email'] = $request->email;
        }

        if($request->notes != null){
            $data['notes'] = $request->notes;
        }

        $vendor->update($data);

        return redirect()
        ->route('vendor.index')
        ->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()
            ->route('vendor.index')
            ->with('success', 'Vendor deleted successfully.');
    }

    public function searchbyname() 
    {
        if (request()->expectsJson() && request('q')) {
            $term = request('q');
            $vendorQuery = Vendor::select('id', 'name as text');

            $vendors = $vendorQuery
                            ->where('name', 'LIKE', "%{$term}%")
                            ->get();

            return response($vendors);
        }

        abort(404);
    }
    
    public function getVendorById(){
        if (request()->expectsJson() && request('id')) {
            $id = request('id');
            $vendor = Vendor::select('id', 'name as text')->where('id', $id)->first();
            return response($vendor);
        }
        abort(404);
    }

    public function showImportVendors(){
        return view('admin.import.vendor');
    }
    public function import(Request $request){
        
        Excel::import(new VendorsImport,$request->vendor_file);
        return redirect()->route('vendors_import.index')->with('success','Vendors Imported Successfully.');
    }
}
