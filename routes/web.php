<?php

use App\Http\Controllers\Admin\AppController;
use App\Http\Controllers\Admin\BatchMixController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UpdatePasswordController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\FinalizePeriodController;
use App\Http\Controllers\Admin\FullCountController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\QualityController;
use App\Http\Controllers\Admin\StationController;
use App\Http\Controllers\Admin\WeightController;
use App\Http\Controllers\Admin\RecipeController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PriceLevelController;
use App\Http\Controllers\Admin\ReportBatchMixController;
use App\Http\Controllers\Admin\ReportInventoryBreakdownController;
use App\Http\Controllers\Admin\ReportInventoryController;
use App\Http\Controllers\Admin\ReportInventorySummaryController;
use App\Http\Controllers\Admin\ReportManagementController;
use App\Http\Controllers\Admin\ReportRecipeController;
use App\Http\Controllers\Admin\ReportSaleController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ImportInventoryController;
use App\Http\Controllers\Admin\ReportPurchaseSummaryController;
use App\Http\Controllers\Admin\ReportVarianceController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\AppDownloadController;
use App\Http\Controllers\Admin\AppVersionController;
use App\Models\PriceLevel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::middleware(['auth'])->group(function ()
{
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/session/accept',[DashboardController::class,'accept']);
    Route::delete('/session/reject',[DashboardController::class,'reject']);

    /*** User ***/
    Route::resource('users', UserController::class);
    Route::get('/admin/password/update',[UpdatePasswordController::class,'index'])->name('admin.password.change');
    Route::post('/admin/password/update',[UpdatePasswordController::class,'update'])->name('admin.password.update');

    /*** Class ***/
    Route::resource('class', ClassController::class);
    Route::post('/class/check-category',[ClassController::class,'checkCategory'])->name('check-category');
    Route::post('/class/check-quality',[ClassController::class,'checkQuality'])->name('check-quality');
    Route::post('/class/check-type',[ClassController::class,'checkType'])->name('check-type');
    Route::get('classes/searchbyname', [ClassController::class, 'searchbyname'])->name('classes.searchbyname');

    Route::resource('companies', CompanyController::class);

    Route::resource('branches', BranchController::class);

    Route::resource('roles', RoleController::class);    
    Route::resource('permissions', PermissionController::class);

    Route::resource('category', CategoryController::class);

    Route::resource('quality', QualityController::class);

    Route::resource('station', StationController::class);

    Route::resource('section', SectionController::class);
    
    Route::resource('location', LocationController::class);
    
    /*** Item ***/
    Route::resource('item', ItemController::class);
    Route::get('item/{item_id}/create-size',[ItemController::class,'createSize'])->name('create-size');
    Route::post('item/{item_id}/store-size',[ItemController::class,'storeSize'])->name('store-size');
    Route::get('item/{item_id}/edit-size/{item_size_id}',[ItemController::class,'editSize']);
    Route::post('item/{item_id}/update-size',[ItemController::class,'updateSize'])->name('update-size');
    Route::delete('item/{item_id}/delete-size/{item_size_id}',[ItemController::class,'deleteSize'])->name('delete-size');
    //Route::post('ajax/item/checkSizePkg',[ItemController::class,'checkSizePackage'])->name('check-size-pkg');
    Route::get('ajax/item/fetchall', [ItemController::class, 'ajaxFetchAll']);
    Route::get('items/searchbyname', [ItemController::class, 'searchbyname'])->name('items.searchbyname');
    Route::get('items/searchbybarcode', [ItemController::class, 'searchbybarcode'])->name('items.searchbybarcode');
    Route::get('ajax/items/getbyid', [ItemController::class, 'getItemById'])->name('items.getbyid');
    Route::get('ajax/items/get-item-sizes-packages',[ItemController::class,'getItemSizesPackages'])->name('item.getItemSizesPackages');

    /*** FullCount ***/
    Route::resource('fullcount', FullCountController::class);
    Route::post('ajax/full-counts/store-period-count',[FullCountController::class,'storePeriodCount'])->name('fullcounts.storePeriodCount');
    Route::post('ajax/full-counts/update-period-count',[FullCountController::class,'updatePeriodCount'])->name('fullcounts.updatePeriodCount');

    /*** Weight ***/
    Route::resource('weight', WeightController::class);
    Route::post('weight/check-location',[WeightController::class,'checkLocation'])->name('check-location');
    Route::post('ajax/weights/store-weight',[WeightController::class,'storeWeight'])->name('weight.storeWeight');
    Route::post('ajax/weights/update-weight',[WeightController::class,'updateWeight'])->name('weight.updateWeight');

    /*** Period ***/
    Route::resource('periods', FinalizePeriodController::class)->middleware(['permission:finalize period']);
    Route::get('available-period-dates', [FinalizePeriodController::class, 'availablePeriodDates'])->name('periods.availablePeriodDates');
    Route::get('period-dates-by-userid', [FinalizePeriodController::class, 'periodDatesByUserId'])->name('periods.periodDatesByUserId');
    Route::post('ajax/periods/last-access-period-id',[FinalizePeriodController::class,'lastAccessPeriodId'])->name('periods.lastAccessPeriodId');
    
    /*** Invoices ***/
    Route::resource('invoices', InvoiceController::class);
    Route::post('ajax/invoices',[InvoiceController::class,'updateFieldByOne'])->name('invoices.upadatefieldbyone');
    
    /*** Sales ***/
    Route::resource('sales', RecipeController::class);
    Route::post('sales/update-price', [RecipeController::class, 'updatePrice'])->name('update-price');
    Route::post('sales/update-recipe-sales', [RecipeController::class, 'updateRecipeSales'])->name('recipe-sales.update-recipe-sales');
    Route::post('sales/check-station',[RecipeController::class,'checkStation'])->name('check-station');
    Route::get('import/sales',[RecipeController::class,'showImportSales'])->name('sales_import.index');
    Route::post('import/sales',[RecipeController::class,'importSales'])->name('sales_import');
    // Route::get('/ajax/{station_id}/get/price_levels',[RecipeController::class,'getPriceLevel']);
    Route::get('/ajax/{station_id}/get/price_level',[RecipeController::class,'getPriceLevel']);
    Route::post('ajax/recipe/parsedata',[RecipeController::class, 'parseData'])->name('recipe.parseData');
    Route::post('ajax/recipe/importdata',[RecipeController::class, 'importData'])->name('recipe.importData');

    /*** batchmix ***/
    Route::resource('batchmix', BatchMixController::class);    

    /*** Vendor ***/
    Route::resource('vendor', VendorController::class);
    Route::get('vendors/searchbyname', [VendorController::class, 'searchbyname'])->name('vendors.searchbyname');
    Route::get('ajax/vendors/getbyid', [VendorController::class, 'getVendorById'])->name('vendors.getbyid');
    Route::get('import/vendors',[VendorController::class,'showImportVendors'])->name('vendors_import.index');
    Route::post('import/vendors',[VendorController::class,'import'])->name('vendors_import');

    /*** Session ***/
    Route::post('ajax/session/store', [SessionController::class,'store']);
    Route::post('ajax/customers/last-access-customer-id',[UserController::class,'lastAccessCustomerId'])->name('users.lastAccessCustomerId');

    /*** Price Level ***/
    Route::resource('price_level', PriceLevelController::class);
    Route::post('ajax/price_level/update',[PriceLevelController::class,'updateOrCreate']);
    Route::get('ajax/{price_level_id}/get/price_level',[PriceLevelController::class,'getPriceLevels'])->name('pricelevel.getPriceLevels');
    Route::get('ajax/price_level/{price_level_id}/delete',[PriceLevelController::class,'delete'])->name('pricelevel.delete');
  
    /*** Management Report ***/
    Route::resource('report_mgmt', ReportManagementController::class);

    /*** BatchMix Report ***/
    Route::get('report_batchmix/exportExcel/', [ReportBatchMixController::class, 'exportExcel']);
    Route::get('report_batchmix/exportPDF/', [ReportBatchMixController::class, 'exportPDF']);
    Route::resource('report_batchmix', ReportBatchMixController::class);

    /*** Recipe Report ***/
    Route::post('report_recipe/exportExcel/', [ReportRecipeController::class, 'exportExcel'])->name('report-recipe-excel');
    Route::post('report_recipe/exportPDF/', [ReportRecipeController::class, 'exportPDF'])->name('report-recipe-pdf');
    Route::resource('report_recipe', ReportRecipeController::class);

    /*** Sale Report ***/
    Route::post('report_sale/exportExcel/', [ReportSaleController::class, 'exportExcel'])->name('report-sale-excel');
    Route::post('/report_sale/exportPDF/', [ReportSaleController::class, 'exportPDF'])->name('report-sale-pdf');
    Route::resource('report_sale', ReportSaleController::class);

    /*** Inventory Report ***/
    Route::resource('report_inventory', ReportInventoryController::class);
    Route::post('report_inventory/exportExcel/', [ReportInventoryController::class, 'exportExcel'])->name('report-inventory-excel');
    Route::post('report_inventory/exportPDF/', [ReportInventoryController::class, 'exportPDF'])->name('report-inventory-pdf');

    /*** Inventory Breakdown Report ***/
    Route::resource('report_inventory_breakdown', ReportInventoryBreakdownController::class);

    /*** Inventory Summary Report ***/
    Route::resource('report_inventory_summary', ReportInventorySummaryController::class);
    Route::post('report_inventory_summary/exportExcel/', [ReportInventorySummaryController::class,'exportExcel'])->name('report-inventory-summary-excel');
    
    /*** Purchase Report ***/
    Route::resource('report_purchase_summary', ReportPurchaseSummaryController::class);
    Route::post('report_purchase_summary/exportExcel/', [ReportPurchaseSummaryController::class,'exportExcel'])->name('report-purchase-summary-excel');

    /*** Variance Report ***/
    Route::resource('report_variance', ReportVarianceController::class);
    Route::post('report_variance/exportExcel/', [ReportVarianceController::class,'exportExcel'])->name('report-variance-excel');
    Route::post('report_variance/exportPDF/', [ReportVarianceController::class,'exportPDF'])->name('report-variance-pdf');    

    /*** Inventory Import ***/
    Route::get('import/inventories',[ImportInventoryController::class,'index'])->name('inventory-upload.index');
    Route::post('import/inventories',[ImportInventoryController::class,'import'])->name('inventory-upload');
 
});
    /*** Mobile App Download***/
    Route::get('/app/download',[AppDownloadController::class,'show']);