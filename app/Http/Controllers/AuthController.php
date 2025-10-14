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

    //Login WEB
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

    //Login APP
    public function loginApp(Request $request)
    {

       /* echo "=== LOGIN DEBUG ===\n";
        echo "Request data: " . json_encode($request->all()) . "\n";
        echo "Usuario recibido: " . $request->usuario . "\n";
        echo "Contraseña recibida: " . $request->contrasena . "\n";
*/
        $request->validate([
            'usuario' => 'required',
            'contrasena' => 'required'
        ]);

        $user = Usuario::where('Usuarios_usuario', $request->usuario)->first();


        if ($user) {
  /*          echo "Usuario encontrado!\n";
            echo "ID: " . ($user->id ?? 'N/A') . "\n";
            echo "Usuario: " . $user->Usuarios_usuario . "\n";
            echo "Hash en BD: " . $user->Usuarios_contrasena . "\n";
    */
            // 4. Debug: Verificar hash
            $hashCheck = Hash::check($request->contrasena, $user->Usuarios_contrasena);
      //      echo "Hash válido: " . ($hashCheck ? 'SÍ' : 'NO') . "\n";

        } else {
        //    echo "Usuario NO encontrado\n";

            // 5. Debug: Ver qué usuarios existen
            $allUsers = Usuario::select('Usuarios_usuario')->limit(5)->get();
          //  echo "Usuarios disponibles: " . json_encode($allUsers->pluck('Usuarios_usuario')) . "\n";
        }



        if (!$user || !Hash::check($request->contrasena, $user->Usuarios_contrasena)) {
          //  echo "Usuario NO encontrado\n";
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

       // $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'id' => $user->usuario_id,
            'usuario' => "juan",
            'roles' => $user->perfiles->pluck('nombre_perfil'),
        ]);
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
