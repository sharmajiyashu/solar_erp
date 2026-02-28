


@extends('admin.layouts.app')

@section('content')

<style>
    .error{
        color:#a93c3d !important;
        font-weight: 500;
    }
    /* input {
        text-transform: uppercase;
    } */
</style>

 <!-- BEGIN: Content-->
 <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Role</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles</a>
                                    </li>
                                    <li class="breadcrumb-item active">Set Permission
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

            {{-- @if ($errors->any())
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="alert-body">
                                            {{$error}}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endforeach
            @endif --}}

                <!-- Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header" >
                                    <div class="col-md-12" style="text-align: center">
                                        <h1 class="card-title" > {{ $role->name }} <input type="checkbox" onclick="toggleCheck(this)" name="all_check" id="all_check"></h1>
                                        
                                    </div>
                                </div>

                                <script>
                                    function toggleCheck(this_value) {
                                             // Select all checkboxes with the class "checkbox-group"
                                             var checkboxes = document.querySelectorAll('.city_checkbox');

                                             // Loop through each checkbox
                                             checkboxes.forEach(function(checkbox) {

                                                 if(checkbox.disabled != true){
                                                     // Toggle the checked property
                                                     if(this_value.checked){
                                                         checkbox.checked = true
                                                     }else{
                                                         checkbox.checked = false
                                                     }
                                                 }
                                                 
                                                 
                                             });
                                         }
                                 </script>
                                <div class="card-body">
                                    <form class="form" action="{{ route('admin.roles.update_permission') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    
                                        <div class="row">
                                            

                                            <table class="table mb-2">
                                                @foreach (config('role_permissions') as $module_name => $pemissions)
                                                <tr>
                                                    <th class="capital" style="background-color: bisque;text-align:center;text-transform: uppercase;"> {{ str_replace('_', ' ', $module_name) }}</th>
                                                    <td>
                                                        
                                                        @foreach ($pemissions as $item)
                                                                
                                                            <span style="margin-left: 20px">  
                                                                
                                                                <input type="checkbox" name="permissions[]" class="form-check-input city_checkbox" id="customSwitch3" 

                                                                <?php 
                                                                    $permission_name = $module_name . ' ' . $item;
                                                                    $role = \Spatie\Permission\Models\Role::find($role->id); // Replace $role_id with the actual variable holding the role ID
                                                                ?>
                                                                    {{-- @checked(true) --}}
                                                                @if($role && $role->hasPermissionTo($permission_name)) checked @endif 

                                                                 value="{{ $permission_name }}" />
                                                                <label class="form-check-label mb-50 capital" for="customSwitch3" style="text-transform: uppercase;">{{ str_replace('_', ' ', $item) }}</label>
                                                                        
                                                            </span>
                                                        @endforeach
                                                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                    </td>
                                                </tr>    
                                                @endforeach
                                            </table>
                                             

                                            <div class="col-12 mb-1" style="text-align: center">
                                                <button type="submit" class="btn btn-primary me-1">Submit</button>
                                            
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic Floating Label Form section end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
    
@endsection