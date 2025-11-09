<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //tras todos os usuarios cadastrados na tabela
        // $users = User::all();
        $currentPage = $request->get('current_page') ?? 1;
        $regsPerPage = 3;

        $skip = ($currentPage - 1) * $regsPerPage; //Pag 1 = 0 --- Pag 2 = 3

        $users = User::skip($skip)->take($regsPerPage)->orderByDesc('id')->get();
        return response()-> json($users->toResourceCollection(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request) //cadastro do usuario
    {
        $data = $request->validated();

        try {
            $user = new User();
            $user-> fill($data);
            $user->password = Hash::make(123);
            $user-> save();

            return response()->json($user->toResource(), 201);
        } catch (\Exception $ex) {
            return response()-> json([
                'message'=> 'Falha ao inserir usuário!'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id); //procura os usuarios com tal id, senão achar falha
            return response()->json($user->toResource(), 200);
        } catch (\Exception $ex) {
            return response()-> json([
                'message'=> 'Falha ao buscar o usuário!'
            ], 404);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id) //Altera as informações do usuário
    {
        $data = $request->validated();

        try {
            $user = User::findOrFail($id);
            $user-> update($data);

            return response()->json($user->toResource(), 200);
        } catch (\Exception $ex) {
            return response()-> json([
                'message'=> 'Falha ao alterar o usuário!'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) //remove os usuários e caso ja esteja removido aparecerá um erro
    {
        try {
            $removed = User::destroy($id);
            if (!$removed){
                throw new Exception();
            }
            return response()->json(null, 204);
        } catch (\Exception $ex) {
            return response()-> json([
                'message'=> 'Falha ao remover usuário!'
            ], 400);
        }
    }
}
