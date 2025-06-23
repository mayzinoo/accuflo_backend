<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color : #3a4651">
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mb-3 d-flex" style="padding-bottom: 0.8rem !important;margin-top: 0.5rem !important;">
            <div class="image">
                <img src="{{ asset('images/logo.png') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Accuflo</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                <li class="nav-item menu-open">
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/" class="nav-link {{ active_path('dashboard') }}">
                                <i class="fa fa-user nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview mt-2 {{ expand_treeview(['fullcount.*', 'weight.*']) }}">
                            <a class="nav-link {{ active_treeview(['fullcount.*', 'weight.*']) }}" href="#">
                                <i class="fas fa-glass-martini nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>
                                    Inventory
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="margin-left: 8px;">
                                @can('list full count')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('fullcount.*') }}"
                                        href="{{ route('fullcount.index') }}">
                                        <i class="fa fa-barcode nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Full Counts
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('list weight')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('weight.*') }}"
                                        href="{{ route('weight.index') }}">
                                        <i class="fas fa-tachometer-alt nav-icon"
                                            style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Weights
                                        </p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        <li class="nav-item has-treeview mt-2 {{ expand_treeview(['invoices.*', 'vendor.*']) }}">
                            <a class="nav-link {{ active_treeview(['invoices.*', 'vendor.*']) }}" href="#">
                                <i class="far fa-file-alt nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>
                                    Purchases
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="margin-left: 8px;">
                                @can('list invoice')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('invoices.*') }}"
                                        href="{{ route('invoices.index') }}">
                                        <i class="fas fa-money-check nav-icon"
                                            style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Invoices
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('list vendor')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('vendor.*') }}"
                                        href="{{ route('vendor.index') }}">
                                        <i class="fas fa-truck nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Vendor Setup
                                        </p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        <li class="nav-item has-treeview mt-2 {{ expand_treeview(['sales.*', 'price_level.*','sales_import.index']) }}">
                            <a class="nav-link {{ active_treeview(['sales.*', 'price_level.*','sales_import.index']) }}" href="#">
                                <i class="fa fa-dollar-sign nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>
                                    Sales & Recipes
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="margin-left: 8px;">
                                @can('list sales')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('sales.*') }}"
                                        href="{{ route('sales.index') }}">
                                        <i class="fas fa-money-bill-alt" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Sales & Recipes Setup
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('sales upload via file')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('sales_import.index') }}"
                                        href="{{ route('sales_import.index') }}">
                                        <i class="fas fa-upload" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Import Sales via File
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('price_level.*') }}"
                                        href="{{ route('price_level.index') }}">
                                        <i class="fas fa-ruble-sign" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Price Level Setup
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li
                            class="nav-item has-treeview mt-2 {{ expand_treeview(['report_mgmt.*', 'report_variance.*', 'report_batchmix.*', 'report_sale.*', 'report_recipe.*', 'report_inventory.*', 'report_inventory_breakdown.*', 'report_inventory_summary.*', 'report_purchase_summary.*']) }}">
                            <a class="nav-link {{ active_treeview(['report_mgmt.*', 'report_variance.*', 'report_batchmix.*', 'report_sale.*', 'report_recipe.*', 'report_inventory.*', 'report_inventory_breakdown.*', 'report_inventory_summary.*', 'report_purchase_summary.*']) }}"
                                href="#">
                                <i class="fas fa-file nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>
                                    Report
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="margin-left: 8px;">
                                @can('variance')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('report_variance.*') }}"
                                        href="{{ route('report_variance.index') }}">
                                        <i class="far fa-file nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Variance
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('batch mix')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('report_batchmix.*') }}"
                                        href="{{ route('report_batchmix.index') }}">
                                        <i class="far fa-file nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Batch Mix
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('sales')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('report_sale.*') }}"
                                        href="{{ route('report_sale.index') }}">
                                        <i class="far fa-file nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Sales
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('recipes')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('report_recipe.*') }}"
                                        href="{{ route('report_recipe.index') }}">
                                        <i class="far fa-file nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Recipes
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('invoice summary')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('report_inventory_summary.*') }}"
                                        href="{{ route('report_inventory_summary.index') }}">
                                        <i class="fa fa-truck nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Invoice Summary
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('purchase summary')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('report_purchase_summary.*') }}"
                                        href="{{ route('report_purchase_summary.index') }}">
                                        <i class="fas fa-calendar-alt nav-icon"
                                            style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Purchase Summary
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('inventory')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('report_inventory.*') }}"
                                        href="{{ route('report_inventory.index') }}">
                                        <i class="far fa-file nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Inventory
                                        </p>
                                    </a>
                                </li> 
                                @endcan
                                @can('inventory breakdown')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('report_inventory_breakdown.*') }}"
                                        href="{{ route('report_inventory_breakdown.index') }}">
                                        <i class="far fa-file nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Inventory Breakdown
                                        </p>
                                    </a>
                                </li> 
                                @endcan
                            </ul>
                        </li>
                        @can('finalize period')
                        <li class="nav-item">
                            <a href="{{ route('periods.index') }}" class="nav-link {{ active_path('period.*') }}">
                                <i class="fas fa-lock nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>Finalize Period</p>
                            </a>
                        </li>
                        @endcan
                        @can('list item')
                        <li class="nav-item">
                            <a href="{{ route('item.index') }}" class="nav-link {{ active_path('item.*') }}">
                                <i class="fa fa-beer nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>Item Setup</p>
                            </a>
                        </li>
                        @endcan
                        @can('list batch mix')
                        <li class="nav-item">
                            <a href="{{ route('batchmix.index') }}"
                                class="nav-link {{ active_path('batchmix.*') }}">
                                <i class="fas fa-utensils nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>Batch Mix</p>
                            </a>
                        </li>
                        @endcan
                        @can('list station')
                        <li class="nav-item">
                            <a href="{{ route('location.index') }}"
                                class="nav-link {{ active_path('location.*') }}">
                                <i class="fa fa-building nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>Location Setup</p>
                            </a>
                        </li>
                        @endcan
                        <li
                            class="nav-item has-treeview mt-2 {{ expand_treeview(['class.*', 'category.*', 'quality.*']) }}">
                            <a class="nav-link {{ active_treeview(['class.*', 'category.*', 'quality.*']) }}"
                                href="#">
                                <i class="fa fa-cogs nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>
                                    Classification
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="margin-left: 8px;">
                                @can('list class')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('class.*') }}"
                                        href="{{ route('class.index') }}">
                                        <i class="fa fa-bars nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Classes
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('list category')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('category.*') }}"
                                        href="{{ route('category.index') }}">
                                        <i class="fa fa-tag nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Categories
                                        </p>
                                    </a>
                                </li>
                                @endcan
                                @can('list quality')
                                <li class="nav-item mt-2">
                                    <a class="nav-link {{ active_path('quality.*') }}"
                                        href="{{ route('quality.index') }}">
                                        <i class="fa fa-star nav-icon" style="font-size: 0.9rem !important;"></i>
                                        <p>
                                            Qualities
                                        </p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @can('list user')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ active_path('users.*') }}">
                                <i class="fa fa-user nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        @endcan
                        @can('list role')
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ active_path('roles.*') }}">
                                <i class="fa fa-tasks nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>Roles</p>
                            </a>
                        </li>
                        @endcan
                        @can('list company')
                        <li class="nav-item">
                            <a href="{{ route('companies.index') }}" class="nav-link {{ active_path('companies.*') }}">
                                <i class="fa fa-building nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>Companies</p>
                            </a>
                        </li>
                        @endcan
                        @can('list branch')
                        <li class="nav-item">
                            <a href="{{ route('branches.index') }}" class="nav-link {{ active_path('branches.*') }}">
                                <i class="fa fa-building nav-icon" style="font-size: 0.9rem !important;"></i>
                                <p>Branches</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
