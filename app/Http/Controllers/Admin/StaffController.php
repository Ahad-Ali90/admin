<?php
// app/Http/Controllers/Admin/StaffController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    // Blade SPA
    public function manage()
    {
        return view('admin.staff.manage');
    }

    // JSON: list with filters + pagination
    public function index(Request $request)
    {
        $q = User::query();

        // Optional: restrict to non-customers if you have customers in users table
        // (Remove if not needed.)
        // $q->whereIn('role', ['admin','booking_grabber','driver','porter']);

        if ($s = $request->get('search')) {
            $q->where(function ($qq) use ($s) {
                $qq->where('name','like',"%{$s}%")
                   ->orWhere('email','like',"%{$s}%")
                   ->orWhere('phone','like',"%{$s}%");
            });
        }

        if ($role = $request->get('role')) {
            $q->where('role', $role);
        }
        if ($status = $request->get('status')) {
            $q->where('status', $status);
        }

        $users = $q->orderByDesc('created_at')->paginate(10);

        return response()->json($users);
    }

    public function show($id)
    {
        $u = User::findOrFail($id);
        return response()->json($u);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // password required on create
        $request->validate([
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $data['password'] = Hash::make($request->password);

        $u = User::create($data);

        return response()->json($u, 201);
    }

    public function update(Request $request, $id)
    {
        $u = User::findOrFail($id);
        $data = $this->validateData($request, $u->id);

        // password optional on edit
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required','string','min:8','confirmed'],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $u->update($data);

        return response()->json($u);
    }

    public function destroy($id)
    {
        $u = User::findOrFail($id);

        // Optional guard: prevent self-delete
        if (auth()->id() === $u->id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 422);
        }

        $u->delete();
        return response()->json(['message' => 'Deleted']);
    }

    protected function validateData(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'name'   => ['required','string','max:255'],
            'email'  => [
                'required','email','max:255',
                Rule::unique('users','email')->ignore($ignoreId),
            ],
            'role'   => ['required', Rule::in(['admin','booking_grabber','driver','porter'])],
            'phone'  => ['nullable','string','max:50'],
            'address'=> ['nullable','string','max:1000'],
            'status' => ['required', Rule::in(['active','inactive'])],
        ]);
    }
}
