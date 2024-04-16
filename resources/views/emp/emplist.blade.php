@extends('layouts.master')

@section('body')
<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modal-employee">
                                        <i class="fas fa-user-plus"></i> Add Employee
                                    </button>
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="example1">
                                        <thead>
                                            <tr> 
                                                <th>NO.</th>
                                                <th>Full Name</th>
                                                <th>Emp_ID</th> 
                                                <th>Position</th>
                                                @if(auth()->user()->role !== "Payroll Extension")
                                                <th>SG-Step</th>
                                                @endif
                                                <th>Campus</th>
                                                <th>Employee Status</th>
                                                <th>Department/Office</th>
                                                <th>Salary Rate</th>
                                                <th>Partime Rate</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $cnt = 1; @endphp
                                            @foreach ($employees as $emp)
                                            <tr id="tr-{{ $cnt++ }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $emp->lname }}, {{ $emp->fname }} {{ $emp->mname }}</td>
                                                <td>{{ $emp->emp_ID }}</td>
                                                <td>{{ $emp->position }}</td>
                                                @if(auth()->user()->role !== "Payroll Extension")
                                                <td>{{ $emp->sg_step }}</td>
                                                @endif
                                                <td>{{ $emp->campus_name }}</td>
                                                <td>
                                                    @if($emp->partime_rate > 0)
                                                        {{ $emp->status_name }} And <br>
                                                        Part-time/Part-time
                                                    @elseif($emp->emp_status == 2)
                                                        {{ $emp->status_name }} ({{ $emp->qualification }})
                                                    @else
                                                        {{ $emp->status_name }}
                                                    @endif
                                                </td>
                                                <td>{{ $emp->office_name }}</td>
                                                <td>{{ number_format($emp->emp_salary, 2) }}</td>
                                                <td>{{ number_format($emp->partime_rate, 2) }}</td>
                                                <td>
                                                    <div class='d-flex align-items-center'>
                                                        <input id="{{ $emp->empid }}" type='checkbox' class='form-control form-control-sm checkbox-partime ' title='part-time' @if($emp->emp_status != 4)disabled @elseif($emp->emp_status==4 && $emp->partime_rate>0) checked @else  @endif> 
                                                        <button class='btn btn-info btn-sm employee_edit mr-1' style='width: 50px;' value="{{ $emp->empid }}">
                                                            <i class='fas fa-exclamation-circle'></i>
                                                        </button>
                                                        <button type='button' class='btn btn-danger btn-sm employee_delete' style='width: 50px;' value="{{ $emp->empid }}">
                                                            <i class='fas fa-trash'></i>
                                                        </button>
                                                    </div>
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
    </div>
</div>
 <!-- Modal -->
 @include('emp.modals')
 <!-- /End Modal -->
@endsection