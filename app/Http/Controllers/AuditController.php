<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('user')->latest();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $audits = $query->paginate(20)->withQueryString();

        $users = User::select('id', 'name')->orderBy('name')->get();

        $modelTypes = Audit::distinct()->pluck('auditable_type')
            ->map(fn($type) => class_basename($type))
            ->unique()
            ->sort()
            ->values();

        return view('audit.index', compact('audits', 'users', 'modelTypes'));
    }
}
