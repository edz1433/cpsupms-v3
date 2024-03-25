<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\AdditionalController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModifyController;
use App\Http\Controllers\SettingsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

//login
Route::get('/login',[LoginAuthController::class,'getLogin'])->name('getLogin');
Route::post('/login',[LoginAuthController::class,'postLogin'])->name('postLogin');

Route::group(['middleware'=>['login_auth']],function(){
    
    Route::get('/dashboard',[MasterController::class,'dashboard'])->name('dashboard');

    //User
    Route::get('/users/ulist',[UserController::class,'ulist'])->name('ulist');
    Route::post('/users/uCreate',[UserController::class,'uCreate'])->name('uCreate');
    Route::get('/users/uEdit/{id}',[UserController::class,'uEdit'])->name('uEdit');
    Route::post('/users/uUpdate',[UserController::class,'uUpdate'])->name('uUpdate');
    Route::get('/users/uDelete/{id}',[UserController::class,'uDelete'])->name('uDelete');

    //Employee
    Route::get('/emp/emplist',[EmployeeController::class,'emp_list'])->name('emp_list');
    Route::post('/empCreate',[EmployeeController::class,'empCreate'])->name('empCreate');
    Route::get('/empEdit/{id}',[EmployeeController::class,'empEdit'])->name('empEdit');
    Route::post('/empUpdate',[EmployeeController::class,'empUpdate'])->name('empUpdate');
    Route::get('/empEditRate/{id}',[EmployeeController::class,'empEditRate'])->name('empEditRate');
    Route::get('/empDelete/{id}',[EmployeeController::class,'empDelete'])->name('empDelete');
    Route::post('/empPartimeRate',[EmployeeController::class,'empPartimeRate'])->name('empPartimeRate');

    //Payroll Page
    Route::get('/payroll/viewPayroll/{campId}', [PayrollController::class, 'viewPayroll'])->name('viewPayroll');
    Route::get('/payslip/{payrollID}',[PayrollController::class,'payslip'])->name('payslip');
    Route::post('/payslip/generate/',[PayrollController::class,'payslipGen'])->name('payslip_gen');
    Route::post('/payroll/createPayroll',[PayrollController::class,'createPayroll'])->name('createPayroll');
    Route::get('/deletePayroll/{id}',[PayrollController::class,'deletePayroll'])->name('deletePayroll');
    Route::get('/deletePayrollFiles/{id}',[PayrollController::class,'deletePayrollFiles'])->name('deletePayrollFiles');
    Route::post('/update-code',[PayrollController::class,'updateCode'])->name('update-code');
    Route::get('/storepayroll/{payrollID}/{statID}/{offID}',[PayrollController::class,'storepayroll'])->name('storepayroll');
    Route::get('/storepayroll-jo/{payrollID}/{statID}/{offID}',[PayrollController::class,'storepayroll'])->name('storepayroll-jo');
    Route::get('/storepayroll-partime/{payrollID}/{statID}/{offID}',[PayrollController::class,'storepayroll'])->name('storepayroll-partime-jo');
    Route::get('/storepayroll-full-partime/{payrollID}/{statID}/{offID}',[PayrollController::class,'storepayroll'])->name('storepayroll-partime');
    Route::get('/pdf/{payrollID}/{statID}/{pid}/{offid}',[PayrollController::class,'showPdf'])->name('pdf');
    Route::get('/pdf/Transmittal/{payrollID}/{statID}/{pid}/{offid}',[PayrollController::class,'showPdf'])->name('transmittal');
    Route::get('/pdf/Remiitance/{col}/{payrollID}',[PayrollController::class,'pdfRemittance'])->name('remittance');
    Route::post('/import/{payrollID}/{statID}', [ImportController::class, 'importPayrolls'])->name('import');
    Route::post('/importPayrollsTwo/{payrollID}/{statID}', [ImportController::class, 'importPayrollsTwo'])->name('importPayrollsTwo');
    Route::post('/deductions-edit', [DeductionController::class, 'deductionsEdit'])->name('deductions-edit');
    Route::post('/deductions-update', [DeductionController::class, 'deductionsUpdate'])->name('deductions-update');
    Route::post('/additional-update', [DeductionController::class, 'additionalUpdate'])->name('additional-update');
    Route::get('/saltypepUp/{id}/{val}', [PayrollController::class, 'saltypepUp'])->name('saltypepUp');
    Route::get('/statupdate/{id}/{val}', [PayrollController::class, 'statUpdate'])->name('statUpdate');
    Route::post('/voucher-create', [PayrollController::class, 'voucherCreate'])->name('voucherCreate');
    Route::get('/pdf/voucher/{id}', [PayrollController::class, 'pdfVoucher'])->name('pdfVoucher');
    
    Route::post('/modify/show', [ModifyController::class, 'modifyShow'])->name('modifyShow');
    Route::post('/modify/update', [ModifyController::class, 'modifyUpdate'])->name('modifyUpdate');

    //Office
    Route::get('/office/list',[OfficeController::class,'officeList'])->name('officeList');
    Route::post('/office/officeCreate', [OfficeController::class, 'officeCreate'])->name('officeCreate');
    Route::get('/office/officeEdit/{id}',[OfficeController::class,'officeEdit'])->name('officeEdit');
    Route::post('/office/officeUpdate',[OfficeController::class,'officeUpdate'])->name('officeUpdate');
    Route::get('/office/officeDelete{id}',[OfficeController::class,'officeDelete'])->name('officeDelete');

    //Employee 
    Route::get('/emp/emplist',[EmployeeController::class,'emp_list'])->name('emp_list');
    Route::get('/logout',[MasterController::class,'logout'])->name('logout');

    //settings
    Route::get('/setting-account',[SettingsController::class,'settingAccount'])->name('settings');
    Route::get('/setting-payroll',[SettingsController::class,'settingPayroll'])->name('settingsPayroll');
    Route::get('/update-percent',[SettingsController::class,'updatePercent'])->name('updatePercent');

});



