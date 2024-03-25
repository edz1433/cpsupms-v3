@extends('layouts.master')

@section('body')
@php
    $current_route=request()->route()->getName();
@endphp
<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        @include('setting-account.submenu')
        <div class="col-lg-10">
            <div class="card card-success card-outline">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href=""><i class="fas fa-dashboard"></i> Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('settings') }}">Settings</a></li>
                        <li class="breadcrumb-item">Account</li>
                    </ol> 
                </nav>
                <div class="card-body">
                    <div class="row">
                        @if(auth()->user()->role != "Staff")
                        <div class="col-12">
                            <form class="form-horizontal" action="{{ route('uUpdate') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <label for="exampleInputName">Last Name:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="module" value="account">
                                                <input type="hidden" name="uid" value="{{ $accounts->uid }}">
                                                <input type="text" name="LastName" value="{{ $accounts->lname }}" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Last Name" class="form-control" autocomplete="off">
                                            </div>
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left LastName_error"></span>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <label for="exampleInputName">First Name:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="FirstName" value="{{ $accounts->fname }}" oninput="this.value = this.value.toUpperCase()" placeholder="Enter First Name" class="form-control" autocomplete="off">
                                            </div>    
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left FirstName_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="exampleInputName">Middle Name:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="MiddleName" value="{{ $accounts->mname }}" oninput="this.value = this.value.toUpperCase()" placeholder="Enter Middle Name" class="form-control" autocomplete="off">
                                            </div>
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left MiddleName_error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-4">
                                            <label for="exampleInputName">Username:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                </div>
                                                <input type="text" name="Username" value="{{ $accounts->username }}" placeholder="Enter Username" class="form-control" autocomplete="off">
                                            </div>    
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left Username_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="exampleInputName">Password:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-lock"></i>
                                                    </span>
                                                </div>
                                                <input type="password" name="Password" placeholder="*****" class="form-control" autocomplete="off">
                                            </div>    
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left Password_error"></span>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="exampleInputName">Campus:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-map-marker"></i>
                                                    </span>
                                                </div>
                                                <select class="form-control select2bs4" name="CampusName" @if(auth()->user()->role !== "Administrator") disabled @endif>
                                                    <option value=""> --- Select Here --- </option>
                                                    @if(auth()->user()->role == "Administrator")
                                                        @foreach ($campus as $cp)
                                                            <option value="{{ $cp->id }}" @if($cp->id == $accounts->campus_id) selected @endif>{{ $cp->campus_name }}</option>
                                                        @endforeach
                                                    @else
                                                        <option value="{{ $campus->id }}"  @if($campus->id == $accounts->campus_id) selected @endif>{{ $campus->campus_name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left CampusName_error"></span>
                                       </div>

                                        <div class="col-md-4">
                                            <label for="exampleInputName">Role:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-info-circle"></i>
                                                    </span>
                                                </div>
                                                <select class="form-control select_camp" name="Role" @if(auth()->user()->role !== "Administrator") disabled @endif> 
                                                    @if(auth()->user()->role == "Administrator") 
                                                    <option value="Administrator" @if($accounts->role == "Administrator") selected @endif>Administrator</option>
                                                    <option value="Payroll Administrator" @if($accounts->role == "Payroll Administrator") selected @endif>Payroll Administrator</option>
                                                    <option value="Payroll Extension" @if($accounts->role == "Payroll Extension") selected @endif>Payroll Extension</option> 
                                                    @elseif(auth()->user()->role !== "Administrator")
                                                    <option value="Payroll Administrator">Payroll Administrator</option>
                                                    @else
                                                    <option value="Payroll Extension">Payroll Extension</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left Role_error"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <button type="submit" name="btn-submit" class="btn btn-success">
                                                <i class="fas fa-save"></i> Save
                                            </button>
                                        </div>
                                    </div>
                                </div>   
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection