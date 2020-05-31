<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Output\ConsoleOutput;

class UsuarioController extends Controller
{

    protected $jwt;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    /*public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'senha' => 'required'
        ]);

        $credencial = $request->only('email', 'senha');

        $output = new ConsoleOutput();
        $teste = password_hash($request->senha, PASSWORD_BCRYPT);
        $output->writeln($credencial);
        $output->writeln($teste);
        $output->writeln(Auth::attempt($credencial) );

        if (!$token = $this->jwt->attempt($credencial)) {
            return response()->json(['Usuário não encontrado'], 404);
        }

        return response()->json(compact('token'));
    }
    */

    public function get()
    {
        return response()->json(Usuario::all());
    }

    public function post(Request $request)
    {

        $this->validate($request, [
            'usuario' => 'required|min:5|max:40',
            'email' => 'required|email|unique:usuarios,email',
            'senha' => 'required'
        ]);

        $usuario = new Usuario();
        $usuario->email = $request->email;
        $usuario->usuario = $request->usuario;
        $usuario->senha = Hash::make($request->senha);
        $usuario->save();

        return response()->json($usuario);
    }

    public function findOne($id)
    {
        return response()->json(Usuario::find($id));
    }

    public function put($id, Request $request)
    {
        $usuario = Usuario::find($id);

        $this->validate($request, [
            'email' => 'email|unique:usuarios,email',
        ]);

        if ($request->has('email')) {
            $usuario->email = $request->email;
        }

        if ($request->has('usuario')) {
            $usuario->usuario = $request->usuario;
        }
        
        if ($request->has('senha')) {
            $usuario->senha = Hash::make($request->senha);
        }

        $usuario->save();

        return response()->json($usuario);
    }


    public function delete($id)
    {
        $usuario = Usuario::find($id);

        $usuario->delete();

        return response()->json("Deletado com sucesso", 200);
    }
}
