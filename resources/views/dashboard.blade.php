@push('styles')
   <link rel="stylesheet" href="{{ asset('assets/css/managementreport.css') }}">
   <style>
      .table td,
      .table th {
         padding: 0.45rem;
      }

      @media (min-width: 576px) {
         .modal-dialog {
            max-width: 600px;
            margin: 1.75rem auto;
         }
      }
   </style>
@endpush('styles')
@extends('layouts.app')

@section('content')
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="container-fluid">
         <div class="row mb-2">
            <div class="col-sm-6">
               <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Dashboard </li>
               </ol>
            </div>
         </div>
      </div><!-- /.container-fluid -->
   </section>

   <!-- Main content -->
   <section class="content">
      @if (isset($sessions) && $sessions->count())
         <div class="content">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-md-12">
                     <div class="alert alert-info alert-message mb-0 mt-3">

                        <strong>New inventory data submitted by the app detected!</strong>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="content" style="padding-top:15px;">
            <div class="container-fluid">
               <div class="card">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="card-body">
                           <table class="table table-hover text-nowrap" style="font-size:0.95rem;">
                              <thead>
                                 <th>Device used</th>
                                 <th>Time Sent to Cloud</th>
                                 <th>Number of Counts and Weights</th>
                                 <th>Action</th>
                              </thead>
                              <tbody>
                                 @foreach ($sessions as $session)
                                    <tr>
                                       <td>{{ $session->device }}</td>
                                       <td>{{ $session->created_at }}</td>
                                       <td>{{ $session->row_count }}</td>

                                       <td>
                                          <button type="button" class="btn btn-xs btn-info view" data-toggle="modal"
                                             data-target="#viewModal" data-created-at="{{ $session->created_at }}">Review
                                             <i class="far fa-square" style="padding-left:2px;"></i>
                                             </a>
                                          </button>
                                          <form action="/session/accept" method="post" style="display:inline;">
                                             @csrf
                                             <input type="hidden" name="created_at" value="{{ $session->created_at }}">

                                             <button class="btn btn-xs btn-success" type="submit">Accept
                                                <i class="fas fa-check" style="padding-left:2px;"></i>

                                             </button>
                                          </form>
                                          <form action="/session/reject" method="post" style="display:inline;">
                                             @method('DELETE')
                                             @csrf
                                             <input type="hidden" name="created_at" value="{{ $session->created_at }}">
                                             <button class="btn btn-xs btn-danger" type="submit">Reject
                                                <i class="fa fa-trash" style="padding-left:2px;"></i>

                                             </button>
                                          </form>
                                       </td>
                                    </tr>
                                 @endforeach
                              </tbody>

                           </table>

                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      @endif

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
                                 @foreach ($loss_leader as $key => $loss_leader_item)
                                    @if ($key < 10)
                                       <tr>
                                          <td> {{ $loss_leader_item['name'] }} </td>
                                          <td> {{ number_format($loss_leader_item['percent'], 2) }} %</td>
                                          <td> SGD{{ number_format($loss_leader_item['cost'], 2) }} </td>
                                       </tr>
                                    @endif
                                 @endforeach
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
                        @foreach ($missing_items as $type => $missing)
                           <div class="col-md-4">
                              <div class="card-header">
                                 <h3 class="card-title">{{ $type }}</h3>
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
                                    @foreach ($missing as $item)
                                       <tr>
                                          <td> {{ $item['name'] }} </td>
                                          <td> {{ number_format($item['percent'], 2) }} %</td>
                                          <td> SGD{{ number_format($item['cost'], 2) }} </td>
                                       </tr>
                                    @endforeach
                                 </tbody>
                              </table>
                           </div>
                        @endforeach
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </div><!-- /.container-fluid -->
   </section>
   <!-- /.content -->
   <div class="modal fade" id="viewModal" role="dialog" aria-hidden="true" style="display:none;">
      <form id="deleteForm" action="" method="">
         @csrf

         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title w-100 text-center">
                     <i class="fas fa-cloud"></i> Sync Details
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body text-center" style="padding:0;">
                  <table class="table table-hover text-nowrap" style="font-size:0.9rem;">
                     <thead>
                        <th>Bar code</th>
                        <th>Item Name</th>
                        <th>Size</th>
                        <th>Quantity</th>
                     </thead>
                     <tbody class="sync-details">

                     </tbody>
                  </table>
               </div>
               <div class="modal-footer">

               </div>
            </div>
         </div>
      </form>
   </div>
@endsection


@push('script')
   <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/excanvas.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.time.js"></script>
   <script type="text/javascript" language="javascript"
      src="{{ asset('assets/plugins/flot/plugins/jquery.flot.categories.js') }}"></script>

   <script type="text/javascript">
      var pie_chart_class = <?php echo isset($pie_chart_class) ? json_encode($pie_chart_class) : ''; ?>;
      var pie_chart_class_cost = <?php echo isset($pie_chart_class_cost) ? json_encode($pie_chart_class_cost) : ''; ?>;
      var used_pie_chart_class = <?php echo isset($used_pie_chart_class) ? json_encode($used_pie_chart_class) : ''; ?>;
      var used_pie_chart_class_cost = <?php echo isset($used_pie_chart_class_cost) ? json_encode($used_pie_chart_class_cost) : ''; ?>;
      var sale_pie_chart_class = <?php echo isset($sale_pie_chart_class) ? json_encode($sale_pie_chart_class) : ''; ?>;
      var sale_pie_chart_class_cost = <?php echo isset($sale_pie_chart_class_cost) ? json_encode($sale_pie_chart_class_cost) : ''; ?>;

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

      const used = {
         labels: used_pie_chart_class,
         datasets: [{
            data: used_pie_chart_class_cost,
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
            data: used

         }
      );
      const salePieChart = document.getElementById('sale_pie_chart');
      const saleChart = new Chart(
         salePieChart, {
            type: 'pie',
            data: sale

         }
      );

      $(document).on('click', '.view', function(e) {
         var items = <?php echo isset($items) ? json_encode($items) : ''; ?>;
         var sessions = <?php echo isset($sessionDetails) ? json_encode($sessionDetails) : ''; ?>;
         var createdAt = $(this).data('created-at');
         var sessionsData = sessions[createdAt];

         var html = "";
         $(sessionsData).each((index, session) => {

            var item = items[session.item_package_id];
            var quantity = session.current_period_count ? session.current_period_count :
               session.current_period_weight + " " + session.unit;
            if (item) {
               if (item.package_status.includes("yes")) {

                  if (item.unit_from == item.unit_to) {
                     html += `<tr>
                     <td>${item.package_barcode}</td><td>${item.name}</td><td>${item.countable_unit} ${item.unit_from}</td><td>${quantity}</td>
                     </tr>`;
                  } else {
                     html += `<tr>
                     <td>${item.package_barcode}</td><td>${item.name}</td><td>${item.qty} x ${item.countable_unit} ${item.countable_size}</td><td>${quantity}</td>
                     </tr>`;
                  }
               } else {
                  html += `<tr>
                  <td>${item.package_barcode}</td><td>${item.name}</td><td>${item.countable_unit} ${item.countable_size}</td><td>${quantity}</td>
                  </tr>`;
               }
            } else {
               console.log(session);
            }

         })

         $('.sync-details').html(html);

      })
   </script>
@endpush
