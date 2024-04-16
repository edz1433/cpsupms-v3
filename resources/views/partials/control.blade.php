@php
    $current_route=request()->route()->getName();
@endphp

<div class="row pt-2 bg-gray rounded">
    <div class="col-sm-10">
        <div>
            <a href="{{ route('dashboard') }}" class="btn btn-app {{$current_route=='dashboard'?'active':''}}">
                <i class="fas fa-th"></i> Dashboard
            </a>

            <a href="{{ route('emp_list') }}" class="btn btn-app {{$current_route=='emp_list'?'active':''}}">
                <i class="fas fa-users"></i> Employees
            </a> 
            @php
                $encryptedId = encrypt(auth()->user()->campus_id);
            @endphp
            <a href="{{ route('viewPayroll', $encryptedId) }}?sid=all" class="btn btn-app {{$current_route == 'viewPayroll' || $current_route == 'storepayroll' || $current_route == 'storepayroll-jo' || $current_route == 'storepayroll-partime-jo' || $current_route == 'storepayroll-partime' || $current_route == 'payslip' ? 'active' : ''}}">
                <i class="fas fa-file-invoice"></i> Payroll
            </a>

            @if(auth()->user()->role != "Payroll Extension")
            <a href="{{ route('officeList') }}" class="btn btn-app {{$current_route=='officeList' || $current_route=='officeEdit' ?'active':''}}">
                <i class="fas fa-building"></i> Offices
            </a>
            @endif

            @if(auth()->user()->role == "Administrator")
            <a href="{{ route('ulist') }}" class="btn btn-app {{$current_route=='ulist' || $current_route=='uEdit'?'active':''}}">
                <i class="fas fa-user"></i> Users
            </a>
            @endif

            <a href="{{ route('settings') }}" class="btn btn-app {{$current_route=='settings' || $current_route=='settingsDeduction' || $current_route=='settingsPayroll' ?'active':''}}">
                <i class="fas fa-cog rotate-gear"></i> Settings
            </a>
            
        </div>
    </div>
    
    <div class="col-sm-2" style="text-align: right;" >
        <div>
            <a href="{{ route('logout') }}" class="btn btn-app pull-right">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </div>
</div>