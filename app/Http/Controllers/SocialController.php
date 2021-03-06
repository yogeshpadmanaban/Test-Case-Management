<?php
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use Socialite;
use App\User;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
 
    public function callback($provider)
    {       
      $getInfo = Socialite::driver($provider)->user();
      $user = $this->createUser($getInfo,$provider);

      $result=User::where('email', $user['email'])->first();
      if($result['email']){
        auth()->login($user);
        return redirect()->to('/section');
      } 
      else{
        return redirect()->to('/login')->				
        withErrors('Email-id does not exist');
      }       
 
    }

    function createUser($getInfo,$provider){
      $user = User::where('provider_id', $getInfo->id)->first();
      if (!$user) {
        $user = User::create([
        'name'     => $getInfo->name,
        'email'    => $getInfo->email,
        'provider' => $provider,
        'provider_id' => $getInfo->id
        ]);
      }
      return $user;
   }
}
