<?php

namespace App\Http\Controllers;

use App\Models\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function index(Request $request)
    {
        $query = Source::query();
        
        if($request->has('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }

        return response()->json($query->paginate(10));
    }

    public function show(Source $source)
    {
        return response()->json($source);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $source = Source::create($validated);

        return response()->json([
            'message' => 'Source successfully created',
            'data' => $source
        ], 200);
    }

    public function update(Request $request, Source $source)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $source->update($validated);

        return response()->json([
            'message' => 'Source successfully updated',
            'data' => $source
        ], 200);
    }

    public function destroy(Source $source)
    {
        $source->delete();

        return response()->json([
            'message' => 'news source succesfully deleted'
        ], 200);
    }
}
