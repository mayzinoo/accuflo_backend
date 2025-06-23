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
                        <li class="breadcrumb-item active">Periods</li>
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
                            <h3 class="card-title">List of Audit Periods</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <div class="row">
                                <div class="col-md-12 p-3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width:24%"> Date Range </th>
                                                <th style="width:12%"> Status </th>
                                                <th style="width:60%"> Action </th>
                                                <th style="width:4%">
                                                 Delete <br/> Period 
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($periods)
                                                @foreach($periods as $period)
                                                    @if($period->start_date && $period->end_date)
                                                        <tr>
                                                            <td @if($period->status) class="change-period" @endif data-id="{{ $period->id }}" data-start_date="{{ optional($period->start_date)->toFormattedDateString() }}" data-end_date="{{ optional($period->end_date)->toFormattedDateString() }}" style="cursor:pointer">
                                                                {{ date_range($period->start_date, $period->end_date) }}
                                                            </td>
                                                            <td>
                                                                @if($period->status == $period_status['open'])
                                                                Open &nbsp;&nbsp;<i class="fa fa-unlock"></i>
                                                                @else
                                                                Closed &nbsp;&nbsp;<i class="fa fa-lock"></i>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($period->id == $can_closed_period->id)
                                                                <a href="JavaScript:void(0);" data-id="{{ $period->id }}" class="close_period" style="color:#000">
                                                                    @if($period->id == $max_period->id)
                                                                    <span>
                                                                        You can close this period to create a new period.&nbsp;Click here to proceed 
                                                                        &nbsp;&nbsp;<i class="fa fa-chevron-right"></i>
                                                                    </span>
                                                                    @else
                                                                    <span>
                                                                        You can close this period.&nbsp;Click here to proceed 
                                                                        &nbsp;&nbsp;<i class="fa fa-chevron-right"></i>
                                                                    </span>
                                                                    @endif
                                                                </a>
                                                                @elseif($period->id == $reopen_period->id)
                                                                <a href="JavaScript:void(0);" data-id="{{ $period->id }}" class="reopen_period" style="color:#000">
                                                                <span>You can re-open this period by clicking here to proceed</span>
                                                                &nbsp;&nbsp;<i class="fa fa-chevron-right"></i>
                                                                </a>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if($period->id != $max_period->id)
                                                                <a href="#" class="remove_period" data-id="{{ $period->id }}">
                                                                    <i class="fa fa-trash" style="color:red;cursor:pointer;"></i>
                                                                </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    @include('includes.close-period-modal')
    @include('includes.remove-period-modal')
    @include('includes.change-date-period-modal')
@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function () {
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
        });

        $(".close_period").click(function(){
            $('.old_period_id').val($(this).data('id'))
            $('#closePeriodModal').modal('show')
        });
        $('.reopen_period').on('click', function(){
            let url = "{{ route('periods.update', ':periodid') }}"
            url = url.replace(":periodid", $(this).data('id'))
            $.ajax({
                url : url,
                type : "PATCH",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "action_type": 'reopen'
                },
                success: function(response){
                    location.reload();
                }
            })
        })
        $('.remove_period').click(function(){
            let url = "{{ route('periods.destroy', ':periodid') }}"
            url = url.replace(":periodid", $(this).data('id'))
            $("#removePeriodForm").attr('action', url)
            $('#removePeriodModal').modal('show')
        })
        $('.change-period').click(function(){
            let url = "{{ route('periods.update', ':periodid') }}"
            url = url.replace(":periodid", $(this).data('id'))
            $("#frm-change-date-period").attr("action", url)
            let header_text = `Change Date for ${$(this).data('start_date')} to ${$(this).data('end_date')}`
            $("#change_date_title").html(header_text)
            $.ajax({
                url : "{{ route('periods.availablePeriodDates') }}",
                data: {
                    id : $(this).data('id')
                }, 
                success: function(response){
                    const max_date = response.end_date ? new Date(response.end_date) : null
                    const min_date = response.start_date ? new Date(response.start_date) : null
                    $(".datepicker").datepicker("option","maxDate", max_date);
                    $(".datepicker").datepicker("option","minDate", min_date);
                    $(".datepicker").datepicker("option","defaultDate", min_date);
                    $("#start_date").val('')
                    $("#end_date").val('')
                    $('#change-date-period-modal').modal('show')
                }
            })
        })
    });
</script>
@endpush
