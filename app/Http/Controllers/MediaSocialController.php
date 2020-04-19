<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;
use App\User;
use App\MediaSocial;

class MediaSocialController extends Controller
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

   public function shows(Request $request)
   {
      $usersaatini = $this->getUserLogin($request);
      $result = MediaSocial::all()->where('user_id', $usersaatini->id);

      if ($result) {
         $data['code'] = 200;
         $data['result'] = $result;
      } else {
         $data['code'] = 500;
         $data['result'] = 'Error';
      }
      return response()->json($data);
   }

   public function show(Request $request, $id)
   {
      $usersaatini = $this->getUserLogin($request);

      $result = MediaSocial::where('id', $id)->first();
      if ($result->user_id == $usersaatini->id) {
         if ($result) {
            $data['code'] = 200;
            $data['result'] = $result;
         } else {
            $data['code'] = 500;
            $data['result'] = 'Error';
         }
         return response()->json($data);
      } else {
         return "Maaf, Anda tidak punya akses";
      }
   }

   public function store(Request $request)
   {
      $usersaatini = $this->getUserLogin($request);
      $result = MediaSocial::create([
         'user_id' => $usersaatini->id,
         'social_media' => $request->sosmed,
         'username' => $request->username
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

   public function update(Request $request, $id)
   {
      $usersaatini = $this->getUserLogin($request);

      $result = MediaSocial::where('id', $id)->first();
      $result->social_media = $request->input('sosmed');
      $result->username = $request->input('username');
      $result->save();

      if ($result->user_id == $usersaatini->id) {
         if ($result) {
            $data['code'] = 200;
            $data['status'] = "Data Updated";
            $data['result'] = $result;
         } else {
            $data['code'] = 500;
            $data['result'] = 'Error';
         }
         return response()->json($data);
      } else {
         return "Maaf, Anda tidak punya akses";
      }
   }

   public function destroy(Request $request, $id)
   {
      $usersaatini = $this->getUserLogin($request);

      $result = MediaSocial::find($id);
      if ($result->user_id == $usersaatini->id) {
         if ($result->delete()) {
            $data['code'] = 200;
            $data['result'] = "Data Social Media: ".$result->social_media." berhasil dihapus.";
         } else {
            $data['code'] = 500;
            $data['result'] = 'Error';
         }
         return response()->json($data);
      } else {
         return "Maaf, Anda tidak punya akses";
      }
   }
}
