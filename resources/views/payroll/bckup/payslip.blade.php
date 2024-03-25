@extends('layouts.master')

@section('body')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        min-height: 200px; /* Adjust the height as needed */
        overflow-y: auto;
    }
</style>
<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        <div class="col-lg-12">
            <div class="card card-success card-outline">
                <div class="card-header">
                 
                    <div class="card-tools">
                        <ol class="breadcrumb float-md-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                @php
                                    $encryptedId = encrypt(1);
                                @endphp
                                <li class="breadcrumb-item"><a href="{{ route('viewPayroll', $encryptedId) }}">Payroll</a></li>
                            <li class="breadcrumb-item active">Payslip</li>
                        </ol>   
                    </div>
                </div>

                <div class="card-body">
                    <div>
                        <form target="_blank" action="{{ route('payslip_gen') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label>Employee</label>
                                        <input type="hidden" name="payrol_ID" value="{{ $payrollID }}">
                                        <select id="employeeSelect" name="emp_ID[]" class="select2 select2-textarea select2-searchable" multiple="multiple" data-placeholder="Select a State" style="width: 100%;">
                                            @foreach($payslip as $pay)
                                                <option value="{{ $pay->empid }}">{{ $pay->lname }} {{ $pay->fname }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1 mt-4 pt-2">
                                    <div class="form-group">
                                        <button type="submit" name="btn-submit" class="btn btn-primary p-3 w-100">
                                            <i class="fas fa-file-pdf"></i> GENERATE
                                        </button>
                                    </div>
                                    <div class="form-group">
                                        <a id="clearBtn" class="btn btn-danger p-3 w-100">
                                            <i class="fas fa-times"></i> CLEAR
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
@include('payroll.modals')
<!-- /End Modal -->


@endsection
