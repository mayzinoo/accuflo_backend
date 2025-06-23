@push('styles')
   <link rel="stylesheet" href="{{ asset('assets/css/managementreport.css') }}">
@endpush('styles')
@extends('layouts.app')

@section('content')
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Periods</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Management Report </li>
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
                     <h3 class="card-title">Performance Summary</h3>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                     <div class="performance-text pie-chart">
                        <ul>
                           <li>
                              <canvas id="on_hand_pie_chart"></canvas>
                              <strong>SGD {{ $on_hand_cost }}</strong><span>On Hand Cost</span>
                           </li>
                           <li><canvas id="used_pie_chart"></canvas>
                              <strong>SGD {{ $used_cost }}</strong><span>Used Cost</span>
                           </li>
                           <li><canvas id="sale_pie_chart"></canvas>
                              <strong>SGD {{ $sale_cost }}</strong><span>Sales</span>
                           </li>
                        </ul>
                     </div>

                  </div>

               </div>
            </div>
            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                     <h3 class="card-title">Loss Leader</h3>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-sm-6">
                           <table class="table">
                              <thead>
                                 <tr>
                                    <th>Item Name</th>
                                    <th>Missing Cost</th>
                                    <th>Missing %</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                        <div class="col-sm-6">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!--Item focus-->
            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                     <h3 class="card-title">Item Focus</h3>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-4">
                           <div class="card-header">
                              <h3 class="card-title">Liquor</h3>
                           </div>
                           <table class="table">
                              <thead>
                                 <tr>
                                    <th>Item Name</th>
                                    <th>Missing %</th>
                                    <th>Missing Cost</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- Additional comments -->
            <div class="col-12">
               <div class="card">
                  <div class="card-header">
                     <h3 class="card-title">Additional Notes / Comments</h3>
                  </div>
                  <div class="card-body">
                     <textarea name="" id="" rows="15" class="form-control" style="resize:none;"></textarea>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div><!-- /.container-fluid -->
   </section>
   <!-- /.content -->
@endsection
@push('script')
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/excanvas.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.time.js"></script>
   <script type="text/javascript" language="javascript"
      src="{{ asset('assets/plugins/flot/plugins/jquery.flot.categories.js') }}"></script>

   <script type="text/javascript">      

      var pie_chart_class = <?php echo json_encode($pie_chart_class); ?>;
      var pie_chart_class_cost = <?php echo json_encode($pie_chart_class_cost); ?>;
      var sale_pie_chart_class = <?php echo json_encode($sale_pie_chart_class); ?>;
      var sale_pie_chart_class_cost = <?php echo json_encode($sale_pie_chart_class_cost); ?>;

      const onhand = {
         labels: pie_chart_class,
         datasets: [{
            data: pie_chart_class_cost,
            backgroundColor: [
               'rgb(84,114,140)',
               'rgb(226,88,86)',
               'rgb(148,184,110)',
               'rgb(133,43,153)',
               'rgb(85,85,85)',
               'rgb(255,184,72)'
            ],
         }]
      };

      const sale = {
         labels: sale_pie_chart_class,
         datasets: [{
            data: sale_pie_chart_class_cost,
            backgroundColor: [
               'rgb(84,114,140)',
               'rgb(226,88,86)',
               'rgb(148,184,110)',
               'rgb(133,43,153)',
               'rgb(85,85,85)',
               'rgb(255,184,72)'
            ],
         }]
      };
      const onhandPieChart = document.getElementById('on_hand_pie_chart');
      const onhandChart = new Chart(
         onhandPieChart, {
            type: 'pie',
            data: onhand
         }
      );
      const usedPieChart = document.getElementById('used_pie_chart');
      const usedChart = new Chart(
         usedPieChart, {
            type: 'pie',
            data: onhand

         }
      );
      const salePieChart = document.getElementById('sale_pie_chart');
      const saleChart = new Chart(
         salePieChart, {
            type: 'pie',
            data: sale

         }
      );
      
   </script>
@endpush
