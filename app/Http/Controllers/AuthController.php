<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
 public function showLoginForm()
{
    return view('auth.login');  // O la vista que uses para el login
}

    public function login(Request $request)
    {
        $credentials = $request->only('Usuarios_usuario', 'Usuarios_contrasena');

        $user = Usuario::where('Usuarios_usuario', $credentials['Usuarios_usuario'])->first();

        if ($user && $user->Usuarios_contrasena === $credentials['Usuarios_contrasena']) {
            Auth::login($user);
            return redirect()->route('inicio');
        }

        return back()->withErrors(['Usuarios_usuario' => 'Credenciales inválidas.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

   /* public function showRegister()
    {
        $personas = Persona::all();
        return view('auth.register', compact('personas'));
    }*/

    public function showRegister()
{
    return view('auth.register');
}

   public function register(Request $request)
{
    $request->validate([
        // Validación de datos de persona
        'nombre_persona' => 'required|string|max:45',
        'apellido_persona' => 'required|string|max:45',
        'direccion_persona' => 'required|string|max:200',
        'celular_principal_persona' => 'required|string|max:45',
        'celular_secundario_persona' => 'nullable|string|max:45',
        'observacion' => 'nullable|string|max:200',
        'cedula' => 'required|string|max:45|unique:personas,cedula',

        // Validación de usuario
        'Usuarios_usuario' => 'required|string|max:45|unique:usuarios,Usuarios_usuario',
        'Usuarios_contrasena' => 'required|string|min:6|confirmed',
    ]);

    // Crear persona
    $persona = Persona::create([
        'nombre_persona' => $request->nombre_persona,
        'apellido_persona' => $request->apellido_persona,
        'direccion_persona' => $request->direccion_persona,
        'celular_principal_persona' => $request->celular_principal_persona,
        'celular_secundario_persona' => $request->celular_secundario_persona,
        'observacion' => $request->observacion,
        'cedula' => $request->cedula,
    ]);

    // Crear usuario
    $usuario = Usuario::create([
        'Usuarios_usuario' => $request->Usuarios_usuario,
        'Usuarios_contrasena' => Hash::make($request->Usuarios_contrasena),
        'Personas_idPersonas' => $persona->idPersonas,
    ]);

    // Autenticar al usuario y redirigir
    auth()->login($usuario);

    return redirect()->route('inicio')->with('success', 'Registro exitoso. ¡Bienvenido!');
}
}