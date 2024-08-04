<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/postulante/home';

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
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users','regex:/^[a-zA-Z0-9._%+-]+@hotmail\.com$/i'        ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'numero_documento' => ['required', 'string','min:8'],
            'tipo_documento' => ['required', 'string', 'in:DNI,Pasaporte,CE'],
            'apellidos' => ['required', 'string', 'max:50'],
            'fecha_nacimiento' => ['required', 'date'],
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
    return DB::transaction(function () use ($data) {
        $user = User::create([
            "name" => $data["name"],
            'email' => $data["email"],
            'password' => Hash::make($data["password"]),
            'role' => 'postulante'
        ]);

        $user->postulante()->create([
            'numero_documento' => $data["numero_documento"],
            'tipo_documento' => $data["tipo_documento"],
            'nombres' => $data["name"],
            'apellidos' => $data["apellidos"],
            'fecha_nacimiento' => $data["fecha_nacimiento"]
        ]);

        return $user;
    });

}
}
    