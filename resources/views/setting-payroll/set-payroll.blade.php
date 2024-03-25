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
                        <li class="breadcrumb-item">Payroll</li>
                    </ol> 
                </nav>
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <span class="badge badge-secondary p-2 mb-2 w-100" style="font-size: 16px;">Regular</span>
                            <div class="form-group">
                                <div class="form-row">
                                    @foreach($settings as $set)
                                        @if($set->stat == 1)
                                        <div class="col-md-6">
                                            <label for="exampleInputName">{{ $set->label }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">

                                                        @if($set->code != 103)<i class="fas fa-percent"></i>  @else <i class="fas fa-money">&#8369;</i> @endif
                                                    </span>
                                                </div>
                                                <input type="number" id="{{ $set->setid }}" value="{{ $set->code != 103 ? ($set->percent) * 100 : $set->percent }}" class="form-control form-control-sm" onkeyup="updateSetting(this.id, this.value, '{{ $set->code }}')" autocomplete="off">
                                            </div>
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left LastName_error"></span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <span class="badge badge-secondary p-2 mb-2 w-100" style="font-size: 16px;">Job-Order</span>
                            <div class="form-group">
                                <div class="form-row">
                                    @foreach($settings as $set)
                                        @if($set->stat == 4)
                                        <div class="col-md-6">
                                            <label for="exampleInputName">{{ $set->label }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-percent"></i>
                                                    </span>
                                                </div>
                                                <input type="deciaml" id="{{ $set->setid }}" value="{{ ($set->percent) * 100 }}" class="form-control form-control-sm" onkeyup="updateSetting(this.id, this.value, '{{ $set->code }}')" autocomplete="off">
                                            </div>
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left LastName_error"></span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <span class="badge badge-secondary p-2 mb-2 w-100" style="font-size: 16px;">Part-time</span>
                            <div class="form-group">
                                <div class="form-row">
                                    @foreach($settings as $set)
                                        @if($set->stat == 2)
                                        <div class="col-md-6">
                                            <label for="exampleInputName">{{ $set->label }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-percent"></i>
                                                    </span>
                                                </div>
                                                <input type="deciaml" id="{{ $set->setid }}" value="{{ ($set->percent) * 100 }}" class="form-control form-control-sm" onkeyup="updateSetting(this.id, this.value, '{{ $set->code }}')" autocomplete="off">
                                            </div>
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left LastName_error"></span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <span class="badge badge-secondary p-2 mb-2 w-100" style="font-size: 16px;">Partime/Partime</span>
                            <div class="form-group">
                                <div class="form-row">
                                    @foreach($settings as $set)
                                        @if($set->stat == 3)
                                        <div class="col-md-6">
                                            <label for="exampleInputName">{{ $set->label }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-percent"></i>
                                                    </span>
                                                </div>
                                                <input type="deciaml" id="{{ $set->setid }}" value="{{ ($set->percent) * 100 }}" class="form-control form-control-sm" onkeyup="updateSetting(this.id, this.value, '{{ $set->code }}')" autocomplete="off">
                                            </div>
                                            <span id="error" style="color: #FF0000; font-size: 10pt;" class="form-text text-left LastName_error"></span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection