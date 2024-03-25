<div class="col-lg-2">
    <div class="card card-success card-outline p-1">
        <h5 class="card-title" style="font-size: 17pt"></h5>
        <ul class="nav nav-pills nav-sidebar nav-compact flex-column">
            <li class="nav-item mb-1">
                <a href="{{ route('settings') }}" class="nav-link2 {{ request()->is('setting-account') ? 'active' : '' }}" id="ppeButton">
                    <i class="fas fa-id-card"></i>
                    <span class="ml-2">Account</span>
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="{{ route('settingsPayroll') }}" class="nav-link2 {{ request()->is('setting-payroll') ? 'active' : '' }}" id="trashButton">
                    <i class="fas fa-money-check"></i>
                    <span class="ml-2">Payroll</span>
                </a>
            </li>
        </ul>                     
    </div>
</div>