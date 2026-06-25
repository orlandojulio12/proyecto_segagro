<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Centro;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('sedes.centro', 'roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users   = $query->paginate(15)->withQueryString();
        $roles   = Role::all();
        $centros = Centro::all();
        $sedes   = Sede::all();

        return view('users.index', compact('users', 'roles', 'centros', 'sedes'));
    }

    public function create()
    {
        $centros = Centro::all();
        $sedes = Sede::all();
        $roles = Role::all();

        return view('users.create', compact('centros', 'sedes', 'roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'address'           => $request->address,
            'phone'             => $request->phone,
            'registration_date' => now(),
            'state'             => 1,
        ]);

        $user->sedes()->attach($request->sede_id);

        // 🔥 ASIGNAR ROL
        $user->assignRole($request->role);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado exitosamente.');
    }

    public function edit($id)
    {
        $user    = User::with('sedes', 'roles')->findOrFail($id);
        $centros = Centro::all();
        $sedes   = Sede::all();
        $roles   = Role::all();

        // If AJAX request (drawer), return JSON
        if (request()->expectsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'id'       => $user->id,
                'name'     => $user->name,
                'email'    => $user->email,
                'address'  => $user->address,
                'phone'    => $user->phone,
                'role'     => $user->roles->first()?->name,
                'sede_id'  => $user->sedes->first()?->id,
                'centro_id'=> $user->sedes->first()?->centro_id,
                'sede_name'=> $user->sedes->first()?->nom_sede,
                'centro_name'=> $user->sedes->first()?->centro?->nom_centro,
            ]);
        }

        return view('users.edit', compact('user', 'centros', 'sedes', 'roles'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'address'  => $request->address,
            'phone'    => $request->phone,
        ]);

        $user->sedes()->sync([$request->sede_id]);

        if ($request->filled('role')) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function show($id)
    {
        $user = User::with('sedes.centro', 'roles')->findOrFail($id);
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
