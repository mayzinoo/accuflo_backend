<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Filters\BranchFilter;
use App\Models\Branch;
use App\Http\Requests\CreateBranchRequest;
use App\Models\Company;
use App\Models\Period;
use App\GlobalConstants;
use Carbon\Carbon;
use Auth;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create branch'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit branch'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete branch'],['only' => 'destroy']);
        $this->middleware(['permission:list branch'],['only' => 'index']);
    }
    public function index(BranchFilter $filter)
    {
        $branchQuery = Branch::query();

        $branches = $branchQuery->filter($filter)->latest('id')->paginate();

        return view('admin.branch.index', compact('branches'));
    }
    public function create()
    {
        if(Auth::user()->role == 'client'){
            $companies = Company::where('id', Auth::user()->company_id)->get();
        }else{
            $companies = Company::all();
        }
        return view('admin.branch.create', compact('companies'));
    }
    public function store(CreateBranchRequest $request)
    {
        $data = $request->validated();
        $branch = Branch::create($data);

        /* create period data */
        if(isset($data['period_end_date']) && ($data['period_end_date'])){
            $period['branch_id'] = $branch->id;
            $period['user_id'] = auth()->user()->id;
            $period['start_date'] = Carbon::now()->format('Y-m-d H:i:s');
            $period['end_date'] = $data['period_end_date'];
            $period['status'] = GlobalConstants::PERIOD_STATUS['open'];
            Period::create($period);
        }

        return redirect()
        ->route('branches.index')
        ->with('success', 'branch created successfully.');
    }
    public function edit(Branch $branch){
        if(Auth::user()->role == 'client'){
            $companies = Company::where('id', Auth::user()->company_id)->get();
        }else{
            $companies = Company::all();
        }
        return view('admin.branch.edit', compact('branch', 'companies'));
    }
    public function update(CreateBranchRequest $request,Branch $branch){
        $data = $request->validated();
        $branch->update($data);

        return redirect()
        ->route('branches.index')
        ->with('success', 'branch updated successfully.');
    }
    public function destroy(Branch $branch){
        $branch->delete();

        return redirect()
        ->route('branches.index')
        ->with('success', 'branch removed successfully.');

    }
}
