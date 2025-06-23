@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endpush
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #3a4651 !important;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" style="color: #fff !important; margin-top: 1px;" data-widget="pushmenu" href="#"
                role="button">
                <i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item">
            <div class="dropdown" style="border-right:1px solid #fff">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="customerMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #3a4651; border:none; border-radius: 0; broder-right: 1px solid #fff">
                    Select Branch
                </button>
                <div class="dropdown-menu customer-menu mt-2" aria-labelledby="customerMenuButton" style="max-height:80vh;overflow-y:auto">
                    @php 
                        $branches = get_branches();
                    @endphp 
                    @foreach($branches as $branch)
                        <a class="dropdown-item" href="#" id="{{ 'branch_'.$branch->id }}" data-id="{{ $branch->id }}" data-name="{{ $branch->name }}">
                            <i class="fas fa-glass-martini" style="font-size: 0.7rem;"></i>&nbsp;&nbsp;
                            {{ $branch->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </li>
        <li class="nav-item">
            <div class="dropdown" style="border-right:1px solid #fff">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="periodMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color: #3a4651; border:none; border-radius: 0; broder-right: 1px solid #fff">
                    Create Period
                </button>
                <div class="dropdown-menu period-menu mt-2" aria-labelledby="periodMenuButton" style="max-height:80vh;overflow-y:auto">
                </div>
            </div>
        </li>
    </ul>

    <!-- Right navbar links -->
    {{--     <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" style="color: #fff !important" data-widget="control-sidebar" data-slide="true"
                href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul> --}}
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown"
                aria-haspopup="true" style="color: #fff !important">
                <i class="fas fa-user"></i> <span style="padding-left: 5px"> {{ auth()->user()->name }} </span>
            </a>

            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownProfile">
                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt nav-icon"
                        style="font-size: 0.9rem !important; margin-right: 3px;"></i>
                    <span>{{ __('Logout') }}</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a class="dropdown-item" href="{{ route('admin.password.change') }}">
                    <i class="fas fa-key nav-icon" style="font-size: 0.9rem !important;margin-right: 3px;"></i>
                    <span>Update password</span>
                </a>
            </div>
        </li>
    </ul>
</nav>
@push('script')
    <script type="text/javascript">
        $(document).ready(function(){
            if( window.localStorage ){
                if( !localStorage.getItem('firstLoad')){
                    localStorage['firstLoad'] = true;
                    window.location.reload();
                }  
                else{
                    localStorage.removeItem('firstLoad');
                }
            }
        })
        let selected_branch_id = "{{ selected_branch_id() }}"
        
        if(selected_branch_id){
            store_session_customer(selected_branch_id)
        }

        function store_session_customer(branch_id){
            let data = { branch_id : branch_id }
            data = JSON.stringify(data)
            $.ajax({
                url : "/ajax/session/store",
                method : "post",
                data : {
                    "_token": "{{ csrf_token() }}",
                    data
                },
                success : function(response){
                    change_customer_option(response.data.branch_id)
                    get_periods_by_customer_id(response.data.branch_id)
                }
            })
        }

        function change_customer_option(branch_id){
            $.ajax({
                url : "/ajax/customers/last-access-customer-id",
                method: 'post',
                data : {
                    "_token": "{{ csrf_token() }}",
                    "last_access_customer_id" : branch_id
                },
                success: function(response){
                    let selector = $("#branch_"+ branch_id);
                    let name = $(selector).data('name');
                    let replace_text = `<i class="fa fa-building"></i>&nbsp;&nbsp;${name}`
                    $('#customerMenuButton').html(replace_text)
                }
            })
        }

        $('.customer-menu a').click(function(){
            window.location.href = document.location.origin+'?branch_id='+$(this).data('id')
        });

        function get_periods_by_customer_id(user_id){
            $.ajax({
                url : "{{ route('periods.periodDatesByUserId') }}",
                method : "get",
                data : { user_id : user_id },
                success : function(response){
                    $('.period-menu').html(response.html)
                    store_period_session(response.latest_period_id)
                }
            })
        }

        $(document).on('click','.period-menu a', function(){
            store_period_session($(this).data('id'))
            window.location.href = window.location.origin + window.location.pathname;
        })

        function store_period_session(period_id){
            let data = { period_id : period_id }
            data = JSON.stringify(data)
            $.ajax({
                url : "/ajax/session/store",
                method : "post",
                data : {
                    "_token": "{{ csrf_token() }}",
                    data
                },
                success : function(response){
                    change_period_option(response.data.period_id)
                }
            })
        }

        function change_period_option(session_period_id){
            $.ajax({
                url : "/ajax/periods/last-access-period-id",
                method: 'post',
                data : {
                    "_token": "{{ csrf_token() }}",
                    "last_access_period_id" : session_period_id
                },
                success: function(response){
                    let selector = $("#period_"+ session_period_id);
                    let status = $(selector).data('status');
                    let last_period = $(selector).data('last_period');
                    if(status == 0){
                        last_period = "<i class='fa fa-lock'></i>&nbsp;&nbsp;" + last_period;
                    }else{
                        last_period = "<i class='fa fa-unlock'></i>&nbsp;&nbsp;" + last_period;
                    }
                    $('#periodMenuButton').html(last_period)
                }
            })
        }

    </script>
@endpush
