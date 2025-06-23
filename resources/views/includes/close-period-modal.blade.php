<div class="modal fade" id="closePeriodModal" role="dialog" aria-hidden="true">
    <form id="closePeriodForm" action="{{ route('periods.store') }}" method="POST">
        @csrf
        <input type="hidden" name="old_period_id" class="old_period_id" value=""/>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 text-center">
                        Close Period
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    Are you sure you want to proceed?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"> Cancle </button>
                    <button type="submit" class="btn btn-sm btn-danger"> Sure</button>
                </div>
            </div>
        </div>
    </form>
</div>
@push('script')
<script type="text/javascript">
</script>
@endpush