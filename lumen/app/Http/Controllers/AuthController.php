<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\ServerRequest;
use Laravel\Passport\Client;

class AuthController extends Controller
{

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    protected function getToken($inputs)
    {
        $client = Client::where('password_client',1)->first();

        $myRequest = new ServerRequest('POST','');
        
        $myRequest = $myRequest->withParsedBody([
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $inputs['email'],
            'password' => $inputs['password'],
            'scope' => '*',
        ]);

        try {
            $response = \Laminas\Diactoros\Response\ArraySerializer::toArray(app('\Dusterio\LumenPassport\Http\Controllers\AccessTokenController')->issueToken($myRequest));
            $body = json_decode($response['body']);
            $body->username = $inputs['email'];
        } catch (\Exception $e) {
            return response()->json(['errors'=>'Erro ao obter token'],500);
        }

        return response()->json(['success'=>true,'data'=>$body],201);
    }

    public function register(Request $request)
    {
        $inputs = $request->all();
        $validator = $this->validator($inputs);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],422);
        }

        if(!$this->create($inputs)){
            return response()->json(['errors'=>'Erro ao criar usuário'],500);
        }

        return $this->getToken($inputs);
    }

    public function login(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:30|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],422);
        }

        return $this->getToken($inputs);
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
            'name' => 'required|string|max:255|fullName',
            'cpf' => 'required|cpf',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:30|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
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
        $fullName = trim($data['name']);
        $fullName = preg_replace('/\s+/', ' ', $fullName);

        $cpf = preg_replace('/[^0-9]/is', '', $data['cpf']);
        return User::create([
            'name' => $fullName,
            'cpf' => $cpf,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
