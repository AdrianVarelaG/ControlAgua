<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Administration
Route::resource("administrations","AdministrationController");
Route::get('administrations.status/{id}', ['as' => 'administrations.status', 'uses' => 'AdministrationController@status']);

//Authorization
Route::resource("authorizations","AuthorizationController");
Route::get('authorizations.status/{id}', ['as' => 'authorizations.status', 'uses' => 'AuthorizationController@status']);

//Citizen
Route::resource("citizens","CitizenController");
Route::get('citizens.invoices/{citizen_id}', ['as' => 'citizens.invoices', 'uses' => 'CitizenController@invoices']);
Route::get('citizens.payments/{citizen_id}', ['as' => 'citizens.payments', 'uses' => 'CitizenController@payments']);
Route::get('citizens.change_view/{view}', ['as' => 'citizens.change_view', 'uses' => 'CitizenController@change_view']);
Route::get('citizens.status/{id}', ['as' => 'citizens.status', 'uses' => 'CitizenController@status']);
Route::get('citizens.balance/{id}/{period}', ['as' => 'citizens.balance', 'uses' => 'CitizenController@balance']);
Route::get('citizens.filter/{name}', ['as' => 'citizens.filter', 'uses' => 'CitizenController@filter']);
Route::get('citizens.rpt_citizens/{filter}', ['as' => 'citizens.rpt_citizens', 'uses' => 'CitizenController@rpt_citizens']);
Route::get('citizens.rpt_citizen_invoices/{id}', ['as' => 'citizens.rpt_citizen_invoices', 'uses' => 'CitizenController@rpt_citizen_invoices']);
Route::get('citizens.rpt_citizen_payments/{id}', ['as' => 'citizens.rpt_citizen_payments', 'uses' => 'CitizenController@rpt_citizen_payments']);


//Company
Route::resource("company","CompanyController");

//*** Combos anidados ***
Route::get('get_municipalities/{id}', 'MunicipalityController@getMunicipalities');

//Contract
Route::resource("contracts","ContractController");
Route::get('contracts.invoices/{contract_id}', ['as' => 'contracts.invoices', 'uses' => 'ContractController@invoices']);
Route::get('contracts.payments/{contract_id}', ['as' => 'contracts.payments', 'uses' => 'ContractController@payments']);
Route::get('contracts.create/{id}', ['as' => 'contracts.create', 'uses' => 'ContractController@create']);
Route::get('contracts.status/{id}', ['as' => 'contracts.status', 'uses' => 'ContractController@status']);
Route::get('contracts.citizen_contracts/{id}', ['as' => 'contracts.citizen_contracts', 'uses' => 'ContractController@citizen_contracts']);
Route::get('contracts.balance/{id}/{period}', ['as' => 'contracts.balance', 'uses' => 'ContractController@balance']);
Route::get('contracts.initial_balance', ['as' => 'contracts.initial_balance', 'uses' => 'ContractController@initial_balance']);
Route::get('contracts.activate/{id}', ['as' => 'contracts.activate', 'uses' => 'ContractController@activate']);
Route::put('contracts.activate/{id}', ['as' => 'contracts.activate', 'uses' => 'ContractController@update_activate']);
Route::get('contracts.filter/{name}', ['as' => 'contracts.filter', 'uses' => 'ContractController@filter']);
Route::get('contracts.rpt_contracts/{filter}', ['as' => 'contracts.rpt_contracts', 'uses' => 'ContractController@rpt_contracts']);
Route::get('citizens.rpt_contract_invoices/{id}', ['as' => 'citizens.rpt_contract_invoices', 'uses' => 'ContractController@rpt_contract_invoices']);
Route::get('citizens.rpt_contract_payments/{id}', ['as' => 'citizens.rpt_contract_payments', 'uses' => 'ContractController@rpt_contract_payments']);

Route::get('initial_balance.filter/{name}', ['as' => 'initial_balance.filter', 'uses' => 'ContractController@initial_balance_filter']);



//Charge
Route::resource("charges","ChargeController");
Route::get('charges.iva', ['as' => 'charges.iva', 'uses' => 'ChargeController@iva']);
Route::get('charges.status/{id}', ['as' => 'charges.status', 'uses' => 'ChargeController@status']);

//Datatables
Route::controller('datatables', 'DatatablesController', [
    'anyData'  => 'datatables.data',
    'getIndex' => 'datatables',
]);

//Discount
Route::resource("discounts","DiscountController");
Route::get('discounts.age', ['as' => 'discounts.age', 'uses' => 'DiscountController@age']);
Route::get('discounts.status/{id}', ['as' => 'discounts.status', 'uses' => 'DiscountController@status']);

//Home
Route::get('/', 'HomeController@index');
Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);

//Img
Route::get('user_avatar/{id}', 'ImgController@showUserAvatar');
Route::get('authorization_avatar/{id}', 'ImgController@showAuthorizationAvatar');
Route::get('citizen_avatar/{id}', 'ImgController@showCitizenAvatar');
Route::get('inspector_avatar/{id}', 'ImgController@showInspectorAvatar');
Route::get('company_logo/{id}', 'ImgController@showCompanyLogo');

//Inspector
Route::resource("inspectors","InspectorController");
Route::get('inspectors.status/{id}', ['as' => 'inspectors.status', 'uses' => 'InspectorController@status']);

//Invoice
Route::resource("invoices","InvoiceController");
Route::get('invoices.change_period', ['as' => 'invoices.change_period', 'uses' => 'InvoiceController@change_period']);
Route::get('invoices.report_period', ['as' => 'invoices.report_period', 'uses' => 'InvoiceController@report_period']);
Route::get('invoices.print_invoices', ['as' => 'invoices.print_invoices', 'uses' => 'InvoiceController@print_invoices']);
Route::get('invoices.routines', ['as' => 'invoices.routines', 'uses' => 'InvoiceController@routines']);
Route::get('invoices.reverse_routine/{year}/{month}', ['as' => 'invoices.reverse_routine', 'uses' => 'InvoiceController@reverse_routine']);

//PDF Reports Controller
Route::get('invoices.inovice_pdf/{id}', ['as' => 'invoices.invoice_pdf', 'uses' => 'PDFController@invoice_pdf']);
Route::post('invoices.invoices_pdf', ['as' => 'invoices.invoices_pdf', 'uses' => 'PDFController@invoices_pdf']);
Route::get('payments.print_voucher/{id}', ['as' => 'payments.print_voucher', 'uses' => 'PDFController@print_voucher']);
Route::get('user_manual/{document}', ['as' => 'user_manual', 'uses' => 'PDFController@user_manual']);

//Login
Route::auth();

//Municipality
Route::resource("municipalities","MunicipalityController");
Route::get('municipalities.change_state/{state_id}', ['as' => 'municipalities.change_state', 'uses' => 'MunicipalityController@change_state']);
Route::get('municipalities.status/{id}', ['as' => 'municipalities.status', 'uses' => 'MunicipalityController@status']);

//Payments
Route::resource("payments","PaymentController");
Route::get('payments.preview/{id}', ['as' => 'payments.preview', 'uses' => 'PaymentController@preview']);
Route::get('payments.change_period', ['as' => 'payments.change_period', 'uses' => 'PaymentController@change_period']);
Route::post('payments.payment_future', ['as' => 'payments.payment_future', 'uses' => 'PaymentController@payment_future']);
Route::get('payments.create/{id}', ['as' => 'payments.create', 'uses' => 'PaymentController@create']);
Route::get('payments.contracts_debt', ['as' => 'payments.contracts_debt', 'uses' => 'PaymentController@contracts_debt']);
Route::get('payments.contracts_solvent', ['as' => 'payments.contracts_solvent', 'uses' => 'PaymentController@contracts_solvent']);
Route::get('payments.future/{id}', ['as' => 'payments.future', 'uses' => 'PaymentController@future']);
Route::get('payments.folio/{id}', ['as' => 'payments.folio', 'uses' => 'PaymentController@folio']);
Route::get('payments.report_period', ['as' => 'payments.report_period', 'uses' => 'PaymentController@report_period']);

//Profile Controller
Route::resource('profiles',"ProfileController");

//RateType
Route::resource("rate_types","RateTypeController");
Route::get('rate_types.status/{id}', ['as' => 'rate_types.status', 'uses' => 'RateTypeController@status']);

//Rate
Route::resource("rates","RateController");
Route::get('rates.flat_rate', ['as' => 'rates.flat_rate', 'uses' => 'RateController@flat_rate']);
Route::get('rates.status/{id}', ['as' => 'rates.status', 'uses' => 'RateController@status']);

//Readings
Route::resource("readings","ReadingController");
Route::get('get_last_reading/{id}', 'ReadingController@getLastReading');

//Setting
Route::resource("settings","SettingController");

//State
Route::resource("states","StateController");
Route::get('states.status/{id}', ['as' => 'states.status', 'uses' => 'StateController@status']);

//UploadFile
Route::get('uploadfile','UploadFileController@index');
Route::post('uploadfile','UploadFileController@showUploadFile');
Route::get('uploadfile.test_date', ['as' => 'uploadfile.test_date', 'uses' => 'UploadFileController@test_date']);

//User
Route::resource("users","UserController");
Route::get('users.status/{id}', ['as' => 'users.status', 'uses' => 'UserController@status']);

