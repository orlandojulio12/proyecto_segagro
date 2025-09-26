<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Centro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('centros')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $centros = Centro::all(); // Obtener todos los centros
        return view('users.create', compact('centros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|unique:users',
            'password'  => 'required|string|min:6',
            'address'   => 'nullable|string|max:225',
            'phone'     => 'nullable|string|max:20',
            'centro_id' => 'required|exists:centros,id',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'address'           => $request->address,
            'phone'             => $request->phone,
            'registration_date' => now(),
            'state'             => 1,
        ]);

        $user->centros()->attach($request->centro_id);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit($id)
    {
        $user = User::with('centros')->findOrFail($id);
        $centros = Centro::all(); // para mostrar en el modal

        return view('users.edit', compact('user', 'centros'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|unique:users,email,' . $id,
            'password'  => 'nullable|string|min:6',
            'address'   => 'nullable|string|max:225',
            'phone'     => 'nullable|string|max:20',
            'centro_id' => 'required|exists:centros,id',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'address'  => $request->address,
            'phone'    => $request->phone,
        ]);

        $user->centros()->sync([$request->centro_id]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function show($id)
    {
        $user = User::with('centros')->findOrFail($id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->update(['state' => 0]);

        return redirect()->route('users.index')
            ->with('success', 'Usuario desactivado correctamente.');
    }
}
