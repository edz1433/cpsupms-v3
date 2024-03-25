@extends('layouts.master')

@section('body')
<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        <div class="col-lg-12">
            <div class="card card-success card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $empCount }}</h3>
                                    <p>Employees</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <a href="{{ route('emp_list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-3">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>{{ count($offCount) }}</h3>
                                    <p>Offices</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-building"></i>
                                </div>
                                <a href="{{ route('officeList') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ count($campCount) }}</h3>
                                    <p>Campus</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-3">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ count($userCount) }}</h3>
                                    <p>User</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <a href="{{ route('ulist') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                <li><i class="fas fa-square" style="color: #04a45c"></i> Regular</li>
                                <li><i class="fas fa-square" style="color: #3c8cbc"></i> Job-Order</li>
                                <li><i class="fas fa-square" style="color: #f46c54"></i> Part-Time</li>
                                <li><i class="fas fa-square" style="color: #f49c14"></i> Part-Time/Part-Time</li>
                            </ul>
                        </div>
                        <div class="col-md-8">
                            <div class="chart-responsive pt-1">
                                <canvas id="pieChart"
                                        data-reg="{{ $chartEmployee->where('emp_status', 1)->count() }}"
                                        data-jo="{{ $chartEmployee->where('emp_status', 4)->count() }}"
                                        data-pt="{{ $chartEmployee->where('emp_status', 2)->count() }}"
                                        data-ptpt="{{ $chartEmployee->where('partime_rate', '>', 0)->count() }}"
                                        {{-- data-pt="200"
                                        data-ptpt="160" --}}
                                        style="min-height: 500;">
                                </canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="card card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <div class="chart"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                        <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 673px;" width="673" height="250" class="chartjs-render-monitor"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
