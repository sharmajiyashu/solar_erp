<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">

    <div class="navbar-header" style="height: 6.45rem !important">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">


                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <img src="{{ url('public/logo.jpg') }}" alt="Logo" style="max-height: 50px; width: auto;">
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i
                        class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i
                        class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc"
                        data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">


            <li class="nav-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">Dashboard</span>
                </a>
            </li>

            @can('enquiries view')
                <li class="nav-item {{ \Str::is('admin.enquiries*', request()->route()->getName()) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('admin.enquiries.index') }}">
                        <i data-feather="file-text"></i>
                        <span class="menu-title text-truncate">Enquiry Management</span>
                    </a>
                </li>
            @endcan

            @canany(['leads get-own', 'leads get-all', 'leads create'])
                <li class="nav-item">

                    <a class="d-flex align-items-center" href="#">
                        <i data-feather="briefcase"></i>
                        <span class="menu-title text-truncate">Lead Management</span>
                    </a>

                    <ul class="menu-content">

                        {{-- Create Lead --}}
                        {{-- @can('leads create')
                            <li class="{{ Request::routeIs('admin.leads.create') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.leads.create') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item">Create Leads</span>
                                </a>
                            </li>
                        @endcan --}}


                            <li class="{{ Request::routeIs('admin.leads.index') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.leads.index') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item">Lead Listing</span>
                                </a>
                            </li>


                        {{-- Site Visit --}}
                        @can('site_visits schedule')
                            <li class="{{ Request::routeIs('admin.leads.site_visit') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.leads.site_visit') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate">Site Visit</span>
                                </a>
                            </li>
                        @endcan


                        {{-- Quotation --}}
                        @can('quotations view')
                            <li class="{{ Request::routeIs('admin.leads.quotation') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.leads.quotation') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate">Quotation</span>
                                </a>
                            </li>
                        @endcan


                        {{-- Document --}}
                        @can('document_management view')
                            <li class="{{ Request::routeIs('admin.leads.document') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.leads.document') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate">Document</span>
                                </a>
                            </li>
                        @endcan


                        {{-- Backend --}}
                        @can('backend_management view')
                            <li class="{{ Request::routeIs('admin.leads.backend') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.leads.backend') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate">Backend</span>
                                </a>
                            </li>
                        @endcan


                        {{-- Procure / Procurement --}}
                        @can('procurement_management view')
                            <li class="{{ Request::routeIs('admin.leads.procurement') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.leads.procurement') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate">Procurement</span>
                                </a>
                            </li>
                        @endcan


                        {{-- Installation --}}
                        @can('installation_management view')
                            <li class="{{ Request::routeIs('admin.leads.installation') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.leads.installation') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate">Installation</span>
                                </a>
                            </li>
                        @endcan


                        {{-- Verification --}}
                        @can('verification_management view')
                            <li class="{{ Request::routeIs('admin.leads.verification') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.leads.verification') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate">Verification</span>
                                </a>
                            </li>
                        @endcan


                        {{-- Completed --}}
                        @can('project_completion view')
                            <li class="{{ Request::routeIs('admin.leads.completed') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.leads.completed') }}">
                                    <i data-feather="circle"></i>
                                    <span class="menu-item text-truncate">Completed</span>
                                </a>
                            </li>
                        @endcan

                    </ul>

                </li>
            @endcanany


            @can('roles_permissions view')

                <li class=" nav-item ">

                    <a class="d-flex align-items-center" href="#"> <i data-feather="shield"></i><span
                            class="menu-title text-truncate" data-i18n="Invoice">Role & Permissions</span>
                    </a>

                    <ul class="menu-content">

                        @can('roles_permissions view')
                            <li
                                class="nav-item {{ Request::routeIs('admin.roles.index', 'admin.roles.create', 'admin.roles.edit', 'admin.roles.show', 'admin.roles.set_permissions') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.roles.index') }}"><i
                                        data-feather="circle"></i><span class="menu-title text-truncate"
                                        data-i18n="File Manager">Role</span></a>
                            </li>
                        @endcan


                        @can('users view')
                            <li
                                class="nav-item {{ Request::routeIs('admin.admin_users.index', 'admin.admin_users.create', 'admin.admin_users.edit', 'admin.admin_users.show', 'admin.admin_users.set_permissions') ? 'active' : '' }}">
                                <a class="d-flex align-items-center" href="{{ route('admin.admin_users.index') }}"><i
                                        data-feather="circle"></i><span class="menu-title text-truncate"
                                        data-i18n="File Manager">Admin User</span></a>
                            </li>
                        @endcan

                    </ul>
                </li>

            @endcan


            <li class="nav-item {{ Request::routeIs('admin.attendance.index') ? 'active' : '' }}">
                <a class="d-flex align-items-center" href="{{ route('admin.attendance.index') }}">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">Attendance</span>
                </a>
            </li>


            @can('reports view')
            <li class="nav-item">
                <a class="d-flex align-items-center" href="javascript:void(0)">
                    <i data-feather="bar-chart-2"></i>
                    <span class="menu-title text-truncate">Reports</span>
                </a>
                <ul class="menu-content">
                    <li class="{{ Request::routeIs('admin.reports.index') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.reports.index') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">Enquiry & Leads</span>
                        </a>
                    </li>
                    <li class="{{ Request::routeIs('admin.reports.attendance') ? 'active' : '' }}">
                        <a class="d-flex align-items-center" href="{{ route('admin.reports.attendance') }}">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">Attendance</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            <li class="nav-item">
                <a data-bs-toggle="modal" data-bs-target="#logout" class="d-flex align-items-center" href="#">
                    <i data-feather="power"></i>
                    <span class="menu-title text-truncate">Logout</span>
                </a>
            </li>


        </ul>



    </div>
</div>




<div class="modal fade modal-danger text-start" id="logout" tabindex="-1" aria-labelledby="logoutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to log out? This action will end your current session.
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.logout') }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-danger">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- END: Main Menu-->
