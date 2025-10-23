<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    // Single-page UI (Blade below)
    public function manage()
    {
        return view('admin.vehicle.manage');
    }

    // JSON list (+ basic filters)
    public function index(Request $request)
    {
        $q = Vehicle::query();

        if ($s = $request->get('search')) {
            $q->where(function($qq) use ($s){
                $qq->where('registration_number', 'like', "%{$s}%")
                   ->orWhere('make', 'like', "%{$s}%")
                   ->orWhere('model', 'like', "%{$s}%")
                   ->orWhere('color', 'like', "%{$s}%");
            });
        }

        if ($type = $request->get('vehicle_type')) {
            $q->where('vehicle_type', $type);
        }

        if ($status = $request->get('status')) {
            $q->where('status', $status);
        }

        $vehicles = $q->orderByDesc('created_at')->paginate(10);

        return response()->json($vehicles);
    }

    public function show($id)
    {
        $v = Vehicle::findOrFail($id);
        return response()->json($v);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $v = Vehicle::create($data);
        return response()->json($v, 201);
    }

    public function update(Request $request, $id)
    {
        $v = Vehicle::findOrFail($id);
        $data = $this->validateData($request, $v->id);
        $v->update($data);
        return response()->json($v);
    }

    public function destroy($id)
    {
        $v = Vehicle::findOrFail($id);
        $v->delete();
        return response()->json(['message' => 'Deleted']);
    }

    protected function validateData(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'registration_number' => [
                'required','string','max:50',
                Rule::unique('vehicles','registration_number')->ignore($ignoreId),
            ],
            'make'  => ['required','string','max:100'],
            'model' => ['required','string','max:100'],
            'year'  => ['required','integer','between:1950,'.(date('Y')+1)],
            'vehicle_type' => ['required', Rule::in(['van','truck','lorry','car'])],
            'color' => ['nullable','string','max:50'],
            'capacity_cubic_meters' => ['nullable','integer','min:0'],
            'max_weight_kg'         => ['nullable','integer','min:0'],

            'status' => ['required', Rule::in(['available','in_use','maintenance','retired'])],
            'mot_expiry_date'        => ['required','date'],
            'insurance_expiry_date'  => ['required','date'],
            'last_service_date'      => ['nullable','date'],
            'next_service_due'       => ['nullable','date'],
            'mileage'                => ['required','integer','min:0'],

            'purchase_price'   => ['nullable','numeric','min:0'],
            'monthly_insurance'=> ['nullable','numeric','min:0'],
            'monthly_finance'  => ['nullable','numeric','min:0'],

            'notes' => ['nullable','string'],
        ]);
    }
}
