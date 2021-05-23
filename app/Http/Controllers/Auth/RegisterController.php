<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\BrgyCodes;
use App\Models\ReferralCodes;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'refCode' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $list = BrgyCodes::where(['bCode' => $data['refCode']],['enabled' => 1])->first();

        if($list) {
            //ang ginawang account ay barangay/regular code
            $brgy_id = $list->brgy_id;
            $adminType = $list->adminType;

            $list = BrgyCodes::where(['bCode' => $data['refCode']],['enabled' => 1])
            ->update([
                'enabled' => 0,
            ]);

            return User::create([
                'brgy_id' => $brgy_id,
                'enabled' => 1,
                'isAdmin' => $adminType,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
        else {
            //company

            $list = ReferralCodes::where('refCode', $data['refCode'])
            ->where('enabled', 1)
            ->first();

            $company_id = $list->company_id;
            $adminType = 0;

            $list = ReferralCodes::where('refCode', $data['refCode'])
            ->where('enabled', 1)
            ->update([
                'enabled' => 0,
            ]);

            return User::create([
                'brgy_id' => NULL,
                'company_id' => $company_id,
                'enabled' => 1,
                'isAdmin' => $adminType,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
    }
}
