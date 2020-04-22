<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $dob = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return response()->json([
            'data' => 'success'
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // Log::emergency(print_r($data, true));
        
        $rules = [
            'user.telephone' => ['required', 'string', 'max:255', 'unique:users,telephone', 'regex:/^(?:\+62|\(0\d{2,3}\)|0)\s?(?:361|8[17]\s?\d?)?(?:[ -]?\d{3,4}){2,3}$/'],
            'user.firstname' => ['required', 'string', 'max:255'],
            'user.lastname'  => ['required', 'string', 'max:255'],
            'user.email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            // 'user.password'  => ['required', 'string', 'min:8', 'confirmed'],
        ];
        
        $attributeLabels = [
            'user.telephone' => 'Mobile number', 
            'user.firstname' => 'First name', 
            'user.lastname'  => 'Last name', 
            'user.email'     => 'Email',
            'dob'           => 'Date of birth',
            'user.gender'     => 'Date of gender',
        ];

        // combine dob input and create date format
        if($data['user']['dob']['year'] || $data['user']['dob']['month'] || $data['user']['dob']['date']) {
            $this->dob = $data['user']['dob']['year'].'-'.$data['user']['dob']['month'].'-'.$data['user']['dob']['date']; 
            $data['dob'] = $this->dob;
            $rules['dob'] = ['date'];
        }

        return Validator::make($data, $rules)->setAttributeNames($attributeLabels);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Log::emergency(print_r($data, true));

        return User::create([
            'telephone' => $data['user']['telephone'],
            'firstname' => $data['user']['firstname'],
            'lastname'  => $data['user']['lastname'],
            'email'     => $data['user']['email'],
            'gender'    => $data['user']['gender'],
            'dob'       => $this->dob,
            'password'  => Hash::make(Str::random(10)),
        ]);
    }
}
