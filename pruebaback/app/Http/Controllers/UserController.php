<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Users::select(
            'users.id',
            'users.first_name',
            'users.last_name',
            'users.username',
            'users.rol_id',
            'r.name'
        )->leftjoin('rol_user as r', 'r.id', 'users.id')->get();
        return $this->successResponse('', $users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validate =  Users::where('username', $request->username)->first();
            if (isset($validate) || $validate != null) {
                return $this->errorResponse('Nombre de usuario ya se encuentra en uso', 500);
            } else {

                $user               = new Users();
                $user->first_name   = $request['firstName'];
                $user->last_name    = $request['lastName'];
                $user->username     = $request['username'];
                $user->password     = Hash::make($request['password']);
                $user->rol_id       = 2;
                $user->save();

                $responseUser = $user->only(['id', 'first_name', 'last_name', 'username', 'rol_id']);
                return $this->successResponse('Cuenta creada con éxito', $responseUser);
            }
        } catch (\Exception $e) {
            return ($e);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function login(Request $request)
    {
        $user = Users::where('username', $request['username'])->first();

        if (isset($user)) {
            if (Hash::check($request['password'], $user->password)) {
                $data = [
                    'id'        => $user->id,
                    'firstName' => $user->first_name,
                    'lastName'  => $user->last_name,
                    'username'  => $user->username,
                    'role'      => $user->rol_id,
                ];

                $key = bin2hex(random_bytes(32));

                $token = JWT::encode($data, $key, 'HS256');

                return $this->successResponse('Inicio de sesión exitoso', [
                    'token' => $token,
                    'data'  => $data,
                ]);
            } else {
                return $this->errorResponse('Contraseña incorrecta, verificar la información', 401);
            }
        } else {
            return $this->errorResponse('Usuario incorrecto, verificar la información', 401);
        }
    }


    public function successResponse($message, $data, $id = 0, $statusCode = Response::HTTP_OK): JsonResponse{
        return response()->json(['message' => $message, 'data' => $data, 'id' => $id], $statusCode);
    }

    public function errorResponse($errorMessage, $statusCode): JsonResponse{
        return response()->json(['error' => $errorMessage, 'error_code' => $statusCode], $statusCode);
    }
}
