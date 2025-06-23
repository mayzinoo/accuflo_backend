<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Vendor;
use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Filters\InvoiceFilter;
use App\GlobalConstants;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create invoice'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit invoice'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete invoice'],['only' => 'destroy']);
        $this->middleware(['permission:list invoice'],['only' => 'index']);
    }
    public function index(InvoiceFilter $filter)
    {
        $period_status = get_current_period_status();
        $invoiceQuery = Invoice::query();
        $invoices = $invoiceQuery->filter($filter)->where('branch_id', session()->get('branch_id'))->where('period_id', session()->get('period_id'))->latest('id')->paginate();
        return view('admin.invoices.index', compact('invoices', 'period_status'));
    }
    public function create()
    {
        return view('admin.invoices.create');
    }
    public function store(CreateInvoiceRequest $request){
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $data['branch_id'] = session()->get('branch_id');
        $data['period_id'] = session()->get('period_id');
        Invoice::create($data);
        return redirect()
        ->route('invoices.index')
        ->with('success', 'Invoice created successfully.');
    }
    public function edit(Invoice $invoice)
    {
        $units = GlobalConstants::UNITS;
        $period_status = get_current_period_status();
        return view('admin.invoices.edit', compact('units','invoice','period_status'));
    }
    public function update(UpdateInvoiceRequest $request, $id)
    {
        $invoice = Invoice::find($id);
        $invoice->update(['total_quantity' => $request['total_quantity'], 'total_cost' => (int)$request['total_cost']]);
        InvoiceDetails::where('invoice_id', $id)->delete();
        if($request['item_id'] !== null){
            foreach($request['item_id'] as $index=>$data){
                $arr_invoice_details = [];
                $arr_invoice_details['invoice_id'] = $id;
                $arr_invoice_details['item_id'] = $request['item_id'][$index];
                $arr_invoice_details['purchased_quantity'] = (int)$request['quantity'][$index];
                $arr_invoice_details['purchase_package'] = $request['unit'][$index];
                $arr_invoice_details['unit_price'] = $request['unit_price'][$index];
                $arr_invoice_details['extended_price'] = $request['extended_price'][$index];
                InvoiceDetails::create($arr_invoice_details);
            }
        }
        return redirect()->route('invoices.index')
                         ->with('success', 'Invoice updated successfully.');
    }
    public function destroy(Invoice $invoice){
        InvoiceDetails::where('invoice_id', $invoice->id)->delete();
        $invoice->delete();
        return redirect()->route('invoices.index')
                        ->with('success', 'Invoice removed successfully.');

    }
    public function updateFieldByOne(Request $request)
    {
        $invoice = Invoice::find($request->id);
        switch($request->field_name){
            case "vendor_id":
                $invoice->update(['vendor_id' => $request['vendor_id']]);
                return response([ 'status' => 'success']);
            break;
            case "invoice_number":
                $invoice->update(['invoice_number' => $request['invoice_number']]);
                return response([ 'status' => 'success']);
            break;
            case "invoice_delivery_date":
                $invoice->update(['invoice_delivery_date' => $request['invoice_delivery_date']]);
                return response([ 'status' => 'success']);
            break;
            case "invoice_due_date":
                $invoice->update(['invoice_due_date' => $request['invoice_due_date']]);
                return response([ 'status' => 'success']);
            break;
            default:
            return response([ 'status' => 'fail']);
        }
    }
}
