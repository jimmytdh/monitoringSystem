<?php

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

Route::get('/','HomeCtrl@index');

Route::get('/logout','LoginCtrl@logoutUser');
Route::get('/login','LoginCtrl@index')->middleware('isLogin');
Route::post('/login/validate','LoginCtrl@validateLogin');

//Patients
Route::get('/patients','PatientCtrl@index');
Route::post('/patients/save','PatientCtrl@save');
Route::post('/patients/search','PatientCtrl@search');
Route::get('/patients/delete/{id}','PatientCtrl@delete');
Route::get('/patients/history/{id}','PatientCtrl@history');
Route::get('/patients/{id}','PatientCtrl@edit');
Route::post('/patients/{id}','PatientCtrl@update');
//End Patients

//Admit
Route::get('/admitted','AdmitCtrl@index');
Route::post('/admitted/search','AdmitCtrl@search');
Route::post('/admitted/save/{id}','AdmitCtrl@save');
Route::get('/admitted/services/remove/{id}','AdmitCtrl@removeService');
Route::get('/admitted/services/{id}','AdmitCtrl@services');
Route::post('/admitted/services/{id}','AdmitCtrl@availServices');
Route::get('/admitted/services/{pat_id}/{service}','AdmitCtrl@showServices');
//end Admit

//eSOA
Route::get('/soa/{id}','SoaCtrl@index');
Route::post('/soa/update/{id}','SoaCtrl@update');
Route::get('/soa/print/{id}','SoaCtrl@printSoa');
//end eSOA

//LIBRARIES

//Morbid
Route::get('/library/comorbid','ComorbidCtrl@index');
Route::post('/library/comorbid/save','ComorbidCtrl@save');
Route::post('/library/comorbid/search','ComorbidCtrl@search');
Route::get('/library/comorbid/delete/{id}','ComorbidCtrl@delete');
Route::get('/library/comorbid/{id}','ComorbidCtrl@edit');
Route::post('/library/comorbid/{id}','ComorbidCtrl@update');
//end morbid

//Brgy
Route::get('/library/brgy','BrgyCtrl@index');
Route::post('/library/brgy/save','BrgyCtrl@save');
Route::post('/library/brgy/search','BrgyCtrl@search');
Route::get('/library/brgy/delete/{id}','BrgyCtrl@delete');
Route::get('/library/brgy/{id}','BrgyCtrl@edit');
Route::post('/library/brgy/{id}','BrgyCtrl@update');
//end Brgy

//Charges
Route::get('/library/charges','ChargeCtrl@index');
Route::post('/library/charges/save','ChargeCtrl@save');
Route::post('/library/charges/search','ChargeCtrl@search');
Route::get('/library/charges/delete/{id}','ChargeCtrl@delete');
Route::get('/library/charges/{id}','ChargeCtrl@edit');
Route::post('/library/charges/{id}','ChargeCtrl@update');
//end charges

//Services
Route::get('/library/services','ServicesCtrl@index');
Route::post('/library/services/save','ServicesCtrl@save');
Route::post('/library/services/search','ServicesCtrl@search');
Route::get('/library/services/delete/{id}','ServicesCtrl@delete');
Route::get('/library/services/{id}','ServicesCtrl@edit');
Route::post('/library/services/{id}','ServicesCtrl@update');
//end Services

//chart
Route::get('/home/chart','HomeCtrl@chart');
//end chart


//Reports
Route::get('/report','ReportCtrl@index');
Route::post('/report','ReportCtrl@search');
Route::get('/report/reset','ReportCtrl@reset');
//End Reports

//Library
Route::get('/library/muncity/list/{code}','LibraryCtrl@getMuncityList');
Route::get('/library/brgy/list/{code}','LibraryCtrl@getBrgyList');
Route::get('/library/loading','LibraryCtrl@loadingPage');
Route::get('/reset','LibraryCtrl@resetAll');
//Route::get('/fix','LibraryCtrl@fixConsultation');
//End Library



