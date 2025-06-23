@extends('layouts.app')

@section('content')
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">

            <div class="col-sm-6" style="display: flex;">
               <div>
                  <i class="fas fa-exchange-alt fa-2x"
                     style="color: #1c75bc;
                        border: #1c75bc solid 4px;
                        border-radius: 10px;
                        padding: 10px;"></i>
               </div>
               <h5 style="margin-left: 15px;margin-top: 15px"> Inventory Breakdown </h5>
               <form action="" id="generate">
                  <div style="margin-left: 30px;margin-top: 15px;">
                     <a class="btn btn-sm btn-default" onclick="generate()">
                        Generate
                     </a>
                     <input type="hidden" name="generate" value='1'>
                  </div>
               </form>
            </div>

            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right" style="margin-top:15px;">
                  {{-- <form action="{{ route('report-inventory-excel') }}" method="POST">
                            @csrf
                            <li style="margin-right: 5px;">
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-excel"></i> Save to Excel
                                </button>
                            </li>
                        </form>
                        <form action="{{ route('report-inventory-pdf') }}" method="POST">
                            @csrf
                            <li>
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-pdf"></i> Save to PDF
                                </button>
                            </li>
                        </form> --}}
               </ol>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </section>

   <!-- Main content -->
   <section class="content">
      <div class="container-fluid">
         <!-- /.row -->
         <div class="row">
            <div class="col-12">
               <table class="table table-sm table-borderless table-responsive text-nowrap">
                  <thead
                     style="color: #000000;
                            background-color: #f2f2f2;border-bottom: 2px solid #ddd !important;
                            border-top: 1px solid #ddd !important;">
                     <tr>
                        @if (count($classes))
                           <th style="padding: .3rem 0.9rem; !important">Item Name</th>
                           <th style="padding: .3rem 0.9rem; !important">Unit</th>
                           <th style="padding: .3rem 0.9rem; !important">Size</th>
                           <th style="padding: .3rem 0.9rem; !important">Bin No</th>
                           <th style="padding: .3rem 0.9rem; !important">All Previous</th>
                           <th style="padding: .3rem 0.9rem; !important">Purchases</th>
                           <th style="padding: .3rem 0.9rem; !important">All Opened</th>
                           <th style="padding: .3rem 0.9rem; !important">All Unopened</th>
                           <th style="padding: .3rem 0.9rem; !important">All Total OH</th>
                           <th style="padding: .3rem 0.9rem; !important">Onhand Unit Cost</th>
                           <th style="padding: .3rem 0.9rem; !important">All OH Cost</th>
                           <th style="padding: .3rem 0.9rem; !important">All Used</th>
                           <th style="padding: .3rem 0.9rem; !important">All Used Cost</th>
                        @endif
                        @foreach ($stations as $station)
                           <th style="padding: .3rem 0.9rem; !important">{{ $station->name }} Prev </th>
                           <th style="padding: .3rem 0.9rem; !important">{{ $station->name }} POH Cost</th>
                           <th style="padding: .3rem 0.9rem; !important">{{ $station->name }} In</th>
                           <th style="padding: .3rem 0.9rem; !important">{{ $station->name }} Opened</th>
                           <th style="padding: .3rem 0.9rem; !important">{{ $station->name }} Unopened</th>
                           <th style="padding: .3rem 0.9rem; !important">{{ $station->name }} Total OH</th>
                           <th style="padding: .3rem 0.9rem; !important">{{ $station->name }} Total OH Cost</th>
                           <th style="padding: .3rem 0.9rem; !important">{{ $station->name }} Out</th>
                           <th style="padding: .3rem 0.9rem; !important">{{ $station->name }} Used</th>
                           <th style="padding: .3rem 0.9rem; !important">{{ $station->name }} Used Cost</th>
                        @endforeach
                     </tr>
                  </thead>
                  <tbody>
                     @foreach ($classes as $key => $class)
                        <tr>
                           @php
                              $class_name = \App\Models\Classes::where('id', $class->class_id)->get();
                           @endphp
                           <td style="font-size: 110%;padding: .3rem 0.9rem; !important">
                              {{ $class_name->count() ? $class_name[0]->name : '' }}
                              </span></td>
                        <tr>
                           @php
                              $categories_data = \App\Models\Category::whereIn('id', $categories)
                                  ->where('class_id', $class->class_id)
                                  ->get();
                              
                           @endphp
                           @foreach ($categories_data as $index => $category)
                           @php
                           $sub_total_all_on_hand_cost = 0; 
                           @endphp
                        <tr>
                           <td style="font-size: 110%;padding: .3rem 0.9rem; !important">{{ $category->name }}:</td>
                        </tr>
                        @php
                           $fullcounts_data = get_fullcount1($items, $category->id);
                           $weights_data = get_weight($remaining_weight_item_id, $category->id);
                           
                        @endphp

                        @if ($fullcounts_data->count())
                           @foreach ($fullcounts_data as $index => $fullcount)
                              @php
                                 $all_previous = get_all_previous($last_period_id, $fullcount->item_id, $fullcount->size, $fullcount->package_id);
                                 $all_opened = get_total_opened($fullcount->item_id, $fullcount->size, $period_id, $fullcount->package_id);
                                 $all_unopened = get_total_unopened($fullcount->item_id, $fullcount->size, $period_id);
                                 $total_OH = $all_opened + $all_unopened;
                                 $on_hand_unit_cost = number_format($fullcount->unit_price, 2, '.', ',');
                                 $all_on_hand_cost = $total_OH * $on_hand_unit_cost;
                                 $purchased_count = get_total_purchased($fullcount->item_id, $fullcount->package_id);
                                 $all_used = $purchased_count - $total_OH;
                                 $all_used_cost = $all_used * $on_hand_unit_cost;
                                 
                                 //to show in total register_shutdown_function
                                 $sub_total_all_on_hand_cost += $all_on_hand_cost;
                              @endphp
                              <tr>
                                 <td><span
                                       style="margin-left: 20px;padding: .3rem 0.9rem; !important">{{ $fullcount->item_name }}</span>
                                 </td>
                                 <td style="padding: .3rem 0.9rem; !important">{{ get_unit($fullcount->package_id) }}</td>

                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $fullcount->size }}</td>
                                 <td style="padding: .3rem 0.9rem; !important"></td>
                                 <td style="padding: .3rem 0.9rem; !important">{{ $all_previous == 0 ? '' : $all_previous }}
                                 </td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $purchased_count == 0 ? '' : $purchased_count }}</td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $all_opened == 0 ? '' : $all_opened }}</td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $all_unopened == 0 ? '' : $all_unopened }} </td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $total_OH ? $total_OH : '' }} </td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $on_hand_unit_cost == 0 ? '' : 'SGD' . '' . $on_hand_unit_cost }}</td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $all_on_hand_cost == 0 ? '' : 'SGD' . '' . $all_on_hand_cost }}
                                 </td>
                                 <td
                                    style="{{ $all_used < 0 ? 'color: red;padding: .3rem 0.9rem; !important' : 'padding: .3rem 0.9rem; !important' }}">
                                    {{ $all_used == 0 ? '' : $all_used }}
                                 </td>
                                 <td
                                    style="{{ $all_used_cost < 0 ? 'color: red;padding: .3rem 0.9rem; !important' : 'padding: .3rem 0.9rem; !important' }}">
                                    {{ $all_used_cost == 0 ? '' : ($all_used_cost > 0 ? 'SGD' . '' . $all_used_cost : '-SGD' . '' . abs($all_used_cost)) }}
                                 </td>

                              </tr>
                           @endforeach
                           
                        @endif
                        @if ($weights_data->count())
                           @foreach ($weights_data as $index => $weight)
                              @php
                                 $all_previous = get_all_previous($last_period_id, $weight->item_id, $weight->size, $weight->package_id);
                                 $all_opened = get_total_unopened($weight->item_id, $weight->size, $period_id);
                                 $all_unopened = 0; //sice data only exist in weight not in fullcount;
                                 $total_OH = $all_opened + $all_unopened;
                                 $on_hand_unit_cost = number_format($weight->unit_price, 2, '.', ',');
                                 $all_on_hand_cost = $total_OH * $on_hand_unit_cost;
                                 $purchased_count = get_total_purchased($weight->item_id, $weight->package_id);
                                 $all_used = $purchased_count - $total_OH;
                                 $all_used_cost = $all_used * $on_hand_unit_cost;

                                 $sub_total_all_on_hand_cost += $all_on_hand_cost;
                              @endphp
                              <tr>
                                 <td><span
                                       style="margin-left: 20px;padding: .3rem 0.9rem; !important">{{ $weight->item_name }}</span>
                                 </td>
                                 <td style="padding: .3rem 0.9rem; !important">{{ get_unit($fullcount->package_id) }}</td>

                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $weight->size }} weight</td>
                                 <td style="padding: .3rem 0.9rem; !important"></td>
                                 <td style="padding: .3rem 0.9rem; !important">{{ $all_previous == 0 ? '' : $all_previous }}
                                 </td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $purchased_count == 0 ? '' : $purchased_count }}</td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $all_opened == 0 ? '' : $all_opened }}</td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                 </td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $total_OH ? $total_OH : '' }}</td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $on_hand_unit_cost == 0 ? '' : 'SGD' . '' . $on_hand_unit_cost }}</td>
                                 <td style="padding: .3rem 0.9rem; !important">
                                    {{ $all_on_hand_cost == 0 ? '' : 'SGD' . '' . $all_on_hand_cost }}
                                 </td>
                                 <td
                                    style="{{ $all_used < 0 ? 'color: red;padding: .3rem 0.9rem; !important' : 'padding: .3rem 0.9rem; !important' }}">
                                    {{ $all_used == 0 ? '' : $all_used }}
                                 </td>
                                 <td
                                    style="{{ $all_used_cost < 0 ? 'color: red;padding: .3rem 0.9rem; !important' : 'padding: .3rem 0.9rem; !important' }}">
                                    {{ $all_used_cost == 0 ? '' : ($all_used_cost > 0 ? 'SGD' . '' . $all_used_cost : '-SGD' . '' . abs($all_used_cost)) }}
                                 </td>

                              </tr>
                           @endforeach
                           
                        @endif
                        <tr
                              style="border-bottom: thin solid black !important;
                                                border-top-color: black !important;padding-top: 8px;
                                                background-color: #e7e7e7 !important;">
                              <td colspan="10" style="font-size: 110%;font-weight: bold";>Total {{ $category->name }}
                              </td>
                              <td>{{$sub_total_all_on_hand_cost}}</td>
                     </tr>  
                     @endforeach
                     
                     <tr
                        style="border-bottom: thin solid black !important;
                                                border-top-color: black !important;padding-top: 8px;
                                                background-color: #e7e7e7 !important;">
                        <td colspan="10" style="font-size: 110%;font-weight: bold";>Total
                           {{ $class_name->count() ? $class_name[0]->name : '' }}</td>
                     </tr>
                     @endforeach
                     
                  </tbody>

               </table>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </section>
   <!-- /.content -->
@endsection
@push('script')
   <script type="text/javascript">
      function generate() {
         
         $("#generate").submit();
      }
   </script>
@endpush
