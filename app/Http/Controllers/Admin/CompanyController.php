<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Filters\CompanyFilter;
use App\Models\Company;
use App\Http\Requests\CreateCompanyRequest;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create company'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit company'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete company'],['only' => 'destroy']);
        $this->middleware(['permission:list company'],['only' => 'index']);
    }
    public function index(CompanyFilter $filter)
    {
        $companyQuery = Company::query();

        $companies = $companyQuery->filter($filter)->latest('id')->paginate();

        return view('admin.company.index', compact('companies'));
    }
    public function create()
    {
        return view('admin.company.create');
    }
    public function store(CreateCompanyRequest $request)
    {
        $data = $request->validated();
        Company::create($data);

        return redirect()
        ->route('companies.index')
        ->with('success', 'company created successfully.');
    }
    public function edit(Company $company){
        return view('admin.company.edit', compact('company'));
    }
    public function update(CreateCompanyRequest $request,Company $company){
        $data = $request->validated();
        $company->update($data);

        return redirect()
        ->route('companies.index')
        ->with('success', 'company updated successfully.');
    }
    public function destroy(Company $company){
        $company->delete();

        return redirect()
        ->route('companies.index')
        ->with('success', 'company removed successfully.');

    }
}
