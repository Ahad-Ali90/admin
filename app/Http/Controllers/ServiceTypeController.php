<?php
// app/Http/Controllers/Admin/ServiceTypeController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceTypeController extends Controller
{
    public function manage()
    {
        return view('admin.service_types.manage');
    }

    public function index(Request $request)
    {
        $q = ServiceType::query();

        if ($s = $request->get('search')) {
            $q->where('name', 'like', "%{$s}%");
        }
     
        $data = $q->orderByDesc('updated_at')->paginate(10);
        return response()->json($data);
    }

    public function show($id)
    {
        return response()->json(ServiceType::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $row  = ServiceType::create($data);
        return response()->json($row, 201);
    }

    public function update(Request $request, $id)
    {
        $row  = ServiceType::findOrFail($id);
        $data = $this->validateData($request, $row->id);
        $row->update($data);
        return response()->json($row);
    }

    public function destroy($id)
    {
        $row = ServiceType::findOrFail($id);
        $row->delete();
        return response()->json(['message' => 'Deleted']);
    }

    protected function validateData(Request $request, $ignoreId = null): array
    {

        return $request->validate([
            'name' => [ 'required','string','max:100', ],
            'price' => ['required','numeric','min:0'],
        ]);
    }
}
