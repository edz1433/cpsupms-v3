@extends('layouts.master')

@section('body')
<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        <div class="col-lg-2">
            <div class="card card-success card-outline">
                <div class="card-body">
                    <ul class="nav nav-pills flex-column">
                        <li class="nav-item">
                            @foreach($camp as $c)
                                <a href="{{ route('viewPayroll', encrypt($c->id)) }}?sid=all" class="nav-link2 @if($c->id == $campId) active @endif" style="color: #000; @if(auth()->user()->role == "Payroll Extension" && $c->id != $campId) pointer-events: none; cursor: default;   @endif">
                                    {{ $c->campus_abbr }}
                                </a>
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-10">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                            data-target="#modal-addpayroll">
                            <i class="fas fa-plus"></i> Add New
                        </button>
                    </h3> 
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    @if($campId == 1)
                        <div class="card-tools mr-3">   
                            <div class="input-group">    
                                <select class="form-control select2" id="statusall" required>
                                    <option value="all">All</option>
                                    @foreach($statusall as $stat)
                                        <option value="{{ $stat->id }}" @if($stat->id == $sid) selected @endif>{{ $stat->status_name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Campus</th>
                                    <th>Employee Status</th>
                                    <th>Type</th>
                                    <th>Fund</th>
                                    <th>Created by.</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $idd = 1; @endphp
                                @foreach ($pays as $p)
                                    @php
                                        $dstart = date('M. d, Y', strtotime($p->payroll_dateStart));
                                        $dend = date('M. d, Y', strtotime($p->payroll_dateEnd));

                                        if($p->stat_id == 1){
                                            $routes = "storepayroll";
                                        }elseif($p->stat_id == 2){
                                            $routes = "storepayroll-partime";
                                        }elseif($p->stat_id == 3){
                                            $routes = "storepayroll-partime-jo";
                                        }elseif($p->stat_id == 4){
                                            $routes = "storepayroll-jo";
                                        }
                                    @endphp
                                    <tr id="tr-{{ $p->id }}">
                                        <td>{{ $idd++ }}</td>
                                        <td>{{ $p->campus_name }}</td>
                                        <td>{{ $p->status_name }}</td>
                                        <td>{{ ($p->jo_type == 2) ? 'Daily Basis' : 'Monthly Basis'}}</td>
                                        <td>{{ $p->fund }}</td>
                                        <td>{{ $p->fname }} {{ $p->lname }}</td>
                                        <td>{{ $dstart }} TO {{ $dend }}</td>
                                        <td>
                                            @if($p->stat_id == 1)
                                            <a href="{{ route('payslip', ['payrollID' => $p->id]) }}" class="btn btn-info btn-sm" title="Payslip">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>                                                    
                                            @else
                                            <a href="#"
                                                class="btn btn-secondary btn-sm" title="Payslip">
                                                <i class="fas fa-file-invoice"></i>
                                            </a>
                                            @endif
                                            <a href="{{ route($routes, ['payrollID' => $p->id, 'statID' => $p->stat_id, 'offID' => 'All']) }}@if($p->stat_id != 1)?s=1 @endif" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </a>
                                            <button type="button" value="{{ $p->id }}" class="btn btn-danger btn-sm payroll-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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

