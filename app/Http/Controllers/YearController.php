<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\YearMaster;


class YearController extends Controller
{
    public function index()
    {
        $datas = YearMaster::where('isDelete', 0)->orderBy('id', 'desc')->paginate(10);
        return view('admin.year.index', compact('datas'));
    }

    public function create()
    {
        return view('admin.year.create');
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'year' => 'required|integer',
        //     'prefix' => 'required|string|max:45',
        // ]);
        $request->validate([
            'year' => 'required|integer|unique:year_masters,year,NULL,id,isDelete,0',
            'prefix' => 'required|string|max:45',
        ], [
            'year.unique' => 'This year already exists.',
        ]);

        YearMaster::create([
            'year' => $request->year,
            'prefix' => $request->prefix,
            'iStatus' => 1,
            'isDelete' => 0,
        ]);

        return redirect()->route('year.index')->with('success', 'Year added successfully.');
    }

    public function edit($id)
    {
        $data = YearMaster::findOrFail($id);
        return view('admin.year.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        /*$request->validate([
            'year' => 'required|integer',
            'prefix' => 'required|string|max:45',
        ]);*/
        $request->validate([
            'year' => [
                'required',
                'integer',
                Rule::unique('year_masters', 'year')->ignore($id)->where(function ($query) {
                    $query->where('isDelete', 0);
                }),
            ],
            'prefix' => 'required|string|max:45',
        ], [
            'year.unique' => 'This year already exists.',
        ]);

        $data = YearMaster::findOrFail($id);
        $data->update([
            'year' => $request->year,
            'prefix' => $request->prefix,
        ]);

        return redirect()->route('year.index')->with('success', 'Year updated successfully.');
    }

    public function destroy($id)
    {
        $data = YearMaster::findOrFail($id);
        $data->update(['isDelete' => 1]);
        return redirect()->route('year.index')->with('success', 'Year deleted successfully.');
    }
    
    public function getYear($id)
    {
        $data = YearMaster::findOrFail($id);
        return response()->json($data);
    }
    
    public function toggleStatus($id)
    {
        $data = YearMaster::findOrFail($id);
        $data->iStatus = $data->iStatus ? 0 : 1;
        $data->save();

        return response()->json(['status' => 'success', 'new_status' => $data->iStatus]);
    }

}
