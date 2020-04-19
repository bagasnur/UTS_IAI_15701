<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use App\User;

class UserController extends Controller
{
   /**
    * Create a new controller instance.
    *
    * @return void
    */
   public function __construct()
   {
      //
   }

   public static function getUserLogin(Request $request)
   {
      $credentials = JWT::decode($request->token, env('JWT_SECRET'), ['HS256']);
      $user = User::find($credentials->sub);
      return $user;
   }

   public function index()
   {
      $result = User::all();

      if ($result) {
         $data['code'] = 200;
         $data['result'] = $result;
      } else {
         $data['code'] = 500;
         $data['result'] = 'Error';
      }
      return response()->json($data);
   }

   public function store(Request $request)
   {
      $result = User::create([
         'name' => $request->name,
         'email' => $request->email,
         'password' => app('hash')->make($request->password)
      ]);

      if ($result) {
         $data['code'] = 200;
         $data['status'] = "Data Recorded";
         $data['result'] = $result;
      } else {
         $data['code'] = 500;
         $data['result'] = 'Error';
      }
      return response($result);
   }

   public function update(Request $request)
   {
      $usersaatini = $this->getUserLogin($request);
      $result = User::where('id', $usersaatini->id)->first();
      $result->name = $request->input('name');
      $result->email = $request->input('email');
      $result->password = app('hash')->make($request->password);
      $result->save();

      if ($result) {
         $data['code'] = 200;
         $data['status'] = "Data Updated";
         $data['result'] = $result;
      } else {
         $data['code'] = 500;
         $data['result'] = 'Error';
      }
      return response()->json($data);
   }
}
