<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Customers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Mail;

use Session;

class CustomersController extends Controller
{
    public function index(){
        return view('customer.register');
    }

    public function list(){
        $customers = DB::select("SELECT * FROM customers ORDER BY created_at DESC");
        return view('customer.customers')->with(['customers' => $customers]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'cityregion' => 'required',
            'gender' => 'required',
            'distance' => 'required',
            'tsize' => 'required'
        ]);
        $customeremail = DB::select("SELECT * FROM customers WHERE email = '$request->email'");
        $customerphone = DB::select("SELECT * FROM customers WHERE phone = '$request->phone'");
        if($customeremail){
            Session::flash('erroremail', 'User Already registered!');    
            return redirect()->back()->with('error', 'The Message');
        }
        if($customerphone){
            Session::flash('errorphone', 'User Already registered!');    
            return redirect()->back()->with('error', 'The Message');
        }
        $storecustomer = Customers::create(['fullname' => $request->name,'phone' => $request->phone,'email' => $request->email,'cityregion' => $request->cityregion,'gender' => $request->gender,'distance' => $request->distance,'tshirtsize' => $request->tsize]);
        // if ($exception instanceof \PDOException) {
        //     return Redirect::back()->with('msg', 'The Message');
        // }
        Session::flash('message', 'You have successfully registered for Tehama Marathon 2022!');
        // send mail from here 
        $namefro = 'Teknokona';
        $data = array('name'=>$request->name, 'email' => $request->email,'from' => $namefro);
        Mail::send(['text'=>'payments'], $data, function($message)use ($request) {
            $message->to($request->email, $request->name)->subject('TEHAMA Half Marathon Payment Procedures');
            $message->from('info@ictc.go.tz','Tehama Marathon');
        });
        return redirect('/payments')->with( ['name' => $request->name] );
    }
}