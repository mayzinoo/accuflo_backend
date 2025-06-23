<?php

namespace App\Http\Controllers\Admin;

use App\Filters\BatchMixFilter;
use App\GlobalConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBatchMixRequest;
use App\Http\Requests\UpdateBatchMixRequest;
use App\Models\Batchmix;
use App\Models\BatchmixIngredients;
use App\Models\Item;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mockery\Undefined;

class BatchMixController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:create batch mix'],['only' => ['create', 'store']]);
        $this->middleware(['permission:edit batch mix'],['only' =>['edit', 'update']]);
        $this->middleware(['permission:delete batch mix'],['only' => 'destroy']);
        $this->middleware(['permission:list batch mix'],['only' => 'index']);
    }
    public function index(BatchMixFilter $filter)
    {

        //$mixQuery = Batchmix::query();
        //$batchmixs = $mixQuery->filter($filter)->latest('id')->paginate();
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');
        $batchmixs = Batchmix::where([['branch_id', $branch_id], ['period_id', $period_id]])->get();
        $period_status = get_current_period_status();

        $BATCHMIX_WEIGHT_UNIT = GlobalConstants::BATCHMIX_WEIGHT_UNIT;
        $BATCHMIX_VOLUME_UNIT = GlobalConstants::BATCHMIX_VOLUME_UNIT;
        $BATCHMIX_UOM = GlobalConstants::BATCHMIX_UOM;
        $BATCHMIX_UD = GlobalConstants::BATCHMIX_UD;
        return view('admin.batchmix.index', compact('batchmixs', 'BATCHMIX_WEIGHT_UNIT', 'BATCHMIX_VOLUME_UNIT', 'BATCHMIX_UOM', 'BATCHMIX_UD', 'period_status'));
    }

    public function create()
    {
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');
        $BATCHMIX_UOM = GlobalConstants::BATCHMIX_UOM;
        $BATCHMIX_UD = GlobalConstants::BATCHMIX_UD;
        $BATCHMIX_WEIGHT_UNIT = GlobalConstants::BATCHMIX_WEIGHT_UNIT;
        $BATCHMIX_VOLUME_UNIT = GlobalConstants::BATCHMIX_VOLUME_UNIT;
        return view('admin.batchmix.create', compact('BATCHMIX_UOM', 'BATCHMIX_UD', 'BATCHMIX_WEIGHT_UNIT', 'BATCHMIX_VOLUME_UNIT', 'branch_id', 'period_id'));
    }

    public function store(CreateBatchMixRequest $request)
    {
        $data = $request->validated();

        if ($request->inventory_status == 'yes') {
            $data['total_weight'] = $request->total_weight;
            $data['total_weight_id'] = $request->total_weight_id;
            $data['container_weight'] = $request->container_weight;
            $data['container_weight_id'] = $request->container_weight_id;
        }

        if ($request->liquid_status == 'yes') {
            $data['total_volume'] = $request->total_volume;
            $data['total_volume_id'] = $request->total_volume_id;
            $data['density'] = $request->density;
        }

        // save batchmix
        $batchmix = Batchmix::create($data);

        // allowed to have empty ingredients
        $ingredient_dbo = [];
        foreach ($request->ingredients as $ingredient) {
            if (isset($ingredient['item_name']) && $ingredient['qty'] > 0) {
                $item = Item::where('id', '=', $ingredient['item_name'])->firstOrFail();

                $ingredient['item_id'] = $item->id;
                $ingredient['item_name'] = $item->name;
                $ingredient_dbo[] = $ingredient;
            }
        }

        // save ingredients
        $batchmix->ingredients()->createMany($ingredient_dbo);

        return redirect()
            ->route('batchmix.index')
            ->with('success', 'Batch Mix created successfully.');
    }

    public function edit($id)
    {
        $BATCHMIX_UOM = GlobalConstants::BATCHMIX_UOM;
        $BATCHMIX_UD = GlobalConstants::BATCHMIX_UD;
        $BATCHMIX_WEIGHT_UNIT = GlobalConstants::BATCHMIX_WEIGHT_UNIT;
        $BATCHMIX_VOLUME_UNIT = GlobalConstants::BATCHMIX_VOLUME_UNIT;
        $batchmix = Batchmix::where('id', $id)->first();
        $ingredients = BatchmixIngredients::where('batchmix_id', $id)->get();
        return view('admin.batchmix.edit', compact('BATCHMIX_UOM', 'BATCHMIX_UD', 'BATCHMIX_WEIGHT_UNIT', 'BATCHMIX_VOLUME_UNIT', 'batchmix', 'ingredients'));
    }

    public function update(UpdateBatchMixRequest $request, $id)
    {
        $data = $request->validated();
        $batchmix = Batchmix::with('ingredients')->find($id);

        if ($request->inventory_status == 'yes') {
            $data['total_weight'] = $request->total_weight;
            $data['total_weight_id'] = $request->total_weight_id;
            $data['container_weight'] = $request->container_weight;
            $data['container_weight_id'] = $request->container_weight_id;
        }

        if ($request->liquid_status == 'yes') {
            $data['total_volume'] = $request->total_volume;
            $data['total_volume_id'] = $request->total_volume_id;
            $data['density'] = $request->density;
        }
        // save batchmix
        $batchmix->update($data);

        // allowed to have empty ingredients
        $ingredients_remove = [];
        $remove = Arr::pluck($request->ingredients, 'id');

        $remove = BatchmixIngredients::where('batchmix_id', $batchmix->id)
            ->whereNotIn('id', $remove)->delete();

        foreach ($request->ingredients as $ingredient) {
            if (isset($ingredient['item_name']) && $ingredient['qty'] > 0) {
                $ingredient_dto = array();
                $item = Item::find($ingredient['item_name']);

                $ingredient_dbo = $batchmix->ingredients->find($ingredient['id']);

                if (empty($ingredient_dbo) || $ingredient_dbo->item_id != $ingredient['item_name']) {
                    $ingredient_dto['item_id'] = $item->id;
                    $ingredient_dto['item_name'] = $item->name;
                }

                if (empty($ingredient_dbo) || $ingredient_dbo->qty != $ingredient['qty']) {
                    $ingredient_dto['qty'] = $ingredient['qty'];
                }

                if (empty($ingredient_dbo) || $ingredient_dbo->uom != $ingredient['uom']) {
                    $ingredient_dto['uom'] = $ingredient['uom'];
                }

                if ($ingredient_dbo)
                    $ingredient_dbo->update($ingredient_dto);
                else {
                    $ingredient_dto["batchmix_id"] = $batchmix->id;
                    BatchmixIngredients::create($ingredient_dto);
                }
            }
        }

        return redirect()
            ->route('batchmix.index')
            ->with('success', 'Batch Mix updated successfully.');
    }

    public function destroy(Batchmix $batchmix)
    {
        $batchmix->delete();

        return redirect()
            ->route('batchmix.index')
            ->with('success', 'Batch Mix deleted successfully.');
    }
}
