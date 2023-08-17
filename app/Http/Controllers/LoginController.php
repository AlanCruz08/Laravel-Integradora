<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use App\Mail\VerifyMail;
use Illuminate\Support\Facades\Mail;

class loginController extends Controller
{
    protected $reglasLogin = [
        'email'     => 'required|string|max:60',
        'password'  => 'required|string|max:60',
    ];

    protected $reglasRegister = [
        'name'      => 'required|string|max:60',
        'email'     => 'required|string|max:60',
        'password'  => 'required|string|max:60',
    ];

    protected $reglasValidate = [
        'id'        => 'required | numeric'
    ];

    protected $reglasCorreo = [
        'email'        => 'required | email'
    ];

    protected $reglasVerificacion = [
        'name'          => 'required|string|max:60',
        'email'         => 'required | email',
        'password'      => 'required|string|max:60',
        'codigo'        => 'required | numeric',
        
    ];

    public function login(Request $request)
    {

        $validacion = Validator::make($request->all(), $this->reglasLogin);

        if ($validacion->fails())
            return response()->json([
                'msg' => 'Error en las validaciones',
                'data' => $validacion->errors(),
                'status' => '422'
            ], 422);

        $user = User::where('email', $request->email)->first();

        if (!$user)
            return response()->json([
                'msg' => 'Usuario no encontrado',
                'data' => 'error',
                'status' => '404'
            ], 404);

        if (!Hash::check($request->password, $user->password))
            return response()->json([
                'msg' => 'Contraseña incorrecta',
                'data' => 'error',
                'status' => '401'
            ], 401);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    public function register(Request $request)
    {
        $validacion = Validator::make($request->all(), $this->reglasRegister);

        if ($validacion->fails())
            return response()->json([
                'msg' => 'Error en las validaciones',
                'data' => $validacion->errors(),
                'status' => '422'
            ], 422);

        $user = User::where('email', $request->email)->first();

        if ($user)
            return response()->json([
                'msg' => 'Usuario ya existente',
                'data' => $user,
                'status' => '422'
            ], 422);

        $email = $request->email;
        $correoEnviado = $this->enviarCorreo($email);

        if ($correoEnviado->status() != 201)
            return response()->json([
                'msg' => 'Error al enviar el correo',
                'data' => null,
                'status' => '422'
            ], 422);

        return response()->json([
            'msg' => $correoEnviado->original['msg'],
            'data' => $correoEnviado->original['data'],
            'status' => $correoEnviado->status()
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'msg' => 'Sesión cerrada',
            'status' => 'success'
        ], 200);
    }

    public function validar(Request $request)
    {
        $accessToken = $request->bearerToken();


        if (!$accessToken) {
            return response()->json([
                'msg' => 'Token no enviado',
                'data' => null,
                'status' => 404
            ], 404);
        }

        $token = PersonalAccessToken::findToken($accessToken);
        $user = $token->tokenable;
        $id = $user->id;

        if (!$token || $token->revoked) {
            return response()->json([
                'msg' => 'Token no encontrado o revocado',
                'data' => [false],
                'status' => 401
            ], 401);
        }

        // Obtener el token almacenado en el Local Storage del cliente
        $token_local = $request->bearerToken();
        $tokens_base_datos = $user->tokens;
        // return response()->json(['tokens' => $tokens_base_datos]);

        foreach ($tokens_base_datos as $token) {
            if ($token->token == $token_local) {
                return response()->json([
                    'msg' => 'El token pertenece al usuario'
                ], 200);
            }
        }

        return response()->json([
            'msg' => 'Token válido',
            'data' => true,
            'status' => 200
        ], 200);
    }


    public function getUserData(Request $request)
    {
        // Obtener el usuario autenticado
        $user = $request->user();

        // Verificar si el usuario existe
        if (!$user) {
            return response()->json([
                'msg' => 'Usuario no encontrado',
                'data' => null,
                'status' => 404
            ], 404);
        }

        // Devolver los datos del usuario en formato JSON
        return response()->json([
            'msg' => 'Datos del usuario',
            'data' => $user,
            'status' => 200
        ], 200);
    }

    public function enviarCorreo(string $email)
    {
        $emailExist = DB::table('verify_email')->where('email', $email)->first();
        if ($emailExist)
            return response()->json([
                'msg' => 'Correo ya enviado',
                'data' => $email,
                'status' => 200
            ], 200);

        $number = rand(1000, 9999);

        DB::table('verify_email')->insert([
            'codigo' => $number,
            'email' => $email
        ]);

        Mail::to($email)->send(new VerifyMail($number));

        return response()->json([
            'msg' => 'Correo enviado',
            'data' => $email,
            'status' => 201
        ], 201);
    }

    public function verificacion(Request $request)
    {
        $validacion = Validator::make($request->all(), $this->reglasVerificacion);

        if ($validacion->fails())
            return response()->json([
                'msg' => 'Error en las validaciones',
                'data' => $validacion->errors(),
                'status' => '422'
            ], 422);

        $codigo = $request->codigo;
        $email = $request->email;
        $relation = DB::table('verify_email')->where('email', $email)->where('codigo', $codigo)->first();

        if (!$relation)
            return response()->json([
                'msg' => 'Codigo no valido',
                'data' => null,
                'status' => 404
            ], 404);

        $verify = DB::table('verify_email')->where('email', $email)->where('codigo', $codigo)->update(['verificado' => true]);

        if (!$verify)
            return response()->json([
                'msg' => 'Error al verificar',
                'data' => null,
                'status' => 404
            ], 404);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
}
