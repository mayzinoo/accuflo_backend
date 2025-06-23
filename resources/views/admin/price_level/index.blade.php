@extends('layouts.app')

@section('content')
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h5>Price Levels</h5>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Price Levels</li>
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

               <div class="card">
                  <div class="card-header">
                     <h3 class="card-title">Price Levels List</h3>
                     <div class="card-tools">
                     </div>
                  </div>
                  <!-- /.card-header -->

                  <!-- /.card-body -->


                  <div class="card-body">

                     <div class="col-md-6 mb-3">
                        <form action="" id="change-station">
                           <select name="station_id" class="select2" id="station_id" data-id="0"
                              onchange="getPriceData(this)" style="width:100%;">
                              @foreach ($stations as $station)
                                 <option value="{{ $station->id }}" {{ $station->id == $station_id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                 </option>
                              @endforeach
                           </select>
                        </form>
                     </div>
                     <form class="form-horizontal" action="{{ route('price_level.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="station_id_copy" value="{{ $station_id }}">
                        <input type="hidden" name="client_id" value="{{$client_id}}">
                        <input type="hidden" name="period_id" value="{{$period_id}}">
                        <table class="table text-nowrap" style="max-width:48%;margin-left: 10px;">
                           <thead>
                              <tr>
                                 <th>#</th>
                                 <th width="70">Price Level</th>
                                 <th width="150">Type</th>
                                 <th></th>
                              </tr>
                           </thead>
                           <tbody class="price-tbody">
                              <!-- @if ($price_level_datas->count() == 0)
                                 <tr>
                                    <td>1</td>
                                    <td>
                                       <input name="price_level[]" type="text" value="Regular" class="form-control"
                                          style="padding-top: 3px;padding-bottom: 3px;width:202px;" data-id=0 onkeyup="submitPriceLevel(this)" required/>
                                    </td>
                                    <td>
                                       <select name="type[]" class="select2" data-id="0" style="width: 100%;"
                                       onchange="submitType(this)">
                                          @foreach ($types as $key => $type)
                                             <option value="{{ $key }}">
                                                {{ $type }}
                                             </option>
                                          @endforeach
                                       </select>
                                    </td>
                                    <td></td>
                                    <input type="hidden" name="id[]" value="">
                                 </tr>
                              @endif -->

                              @foreach ($price_level_datas as $index => $price_data)
                                 <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                       <input type="text" name="price_level[]" class="form-control"
                                          value="{{ $price_data->level }}" data-id="{{$price_data->id}}"
                                          style="padding-top: 3px;padding-bottom: 3px;width:202px;" onkeyup="submitPriceLevel(this)"/>
                                    </td>
                                    <td>
                                       <select name="type[]" class="select2" id="default_type_{{$index}}" data-id="{{$price_data->id}}"
                                          style="width:100%;" onchange="submitType(this)">
                                          @foreach ($types as $key => $type)
                                             <option value="{{ $key }}"
                                                {{ $price_data->type == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                             </option>
                                          @endforeach
                                       </select>
                                    </td>
                                    <td>
                                       @if ($price_level_datas->count() > 1)
                                          <a href="{{ route('pricelevel.delete', $price_data->id) }}"
                                             class="btn btn-sm btn-danger" style="width: 74px;margin-top:4px;">
                                             DELETE </a>
                                       @endif
                                    </td>
                                    <input type="hidden" name="id[]" value="{{ $price_data->id }}">
                                 </tr>
                              @endforeach


                              <tr>
                                 <td></td>
                                 <td><input type="text" name="price_level[]" class="form-control" value=""
                                       style="padding-top: 3px;padding-bottom: 3px;width:202px;" required /></td>
                                 <td><select class="select2" name="type[]" id="type" data-id="0"
                                       style="width:100%;">
                                       @foreach ($types as $key => $type)
                                          <option value="{{ $key }}">{{ $type }}
                                          </option>
                                       @endforeach
                                    </select></td>
                                 <td>
                                    <button type="submit" class="btn btn-sm btn-primary" style="width: 74px;margin-top:4px;">
                                       Add </button>
                                 </td>
                                 <input type="hidden" name="id[]" value="">
                              </tr>
                           </tbody>
                        </table>
                     </form>
                  </div>


               </div>
               <!-- /.card -->
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </section>
@endsection
@push('script')
   <script type="text/javascript">
      var types = <?php echo json_encode(GlobalConstants::PRICE_LEVEL_TYPE); ?>;
      $(document).ready(function(){
         $('.select2').each(function(i,ele){
         $(ele).select2();
      })
      })
      
      function getPriceData(ele) {
         var stationId = ele.value;

         $("#change-station").submit();
      }
      function submitType(ele){
         priceDataId=$(ele).data('id');
         type=ele.value;
         data={
            "_token" : "<?php echo(csrf_token())?>",
            "price_data_id" : priceDataId,
            "type"   : type
         }
         $.ajax({
            url: "/ajax/price_level/update",

            method: "POST",
            data: data,
           
         })
      }
      function submitPriceLevel(ele){
         priceDataId=$(ele).data('id');
         price_level=ele.value;
         data={
            "_token" : "<?php echo(csrf_token())?>",
            "price_data_id" : priceDataId,
            "price_level"   : price_level
         }
         $.ajax({
            url: "/ajax/price_level/update",
            method: "POST",
            data: data,
            
         })
        
      }
    
   </script>
@endpush
