<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\videos;
use Illuminate\Http\Request;

class videosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum', [
            'except' => ['index', 'show', 'indexDashboard']
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $data = videos::paginate($perPage);
        return $data;
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data :D',
            'data' => $data
        ], 200);
    }

    public function indexDashboard()
    {
        $data = Videos::orderBy('date', 'desc') 
            ->take(5)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data :D',
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'url' => 'required|string',
        ]);

        $videos = new videos($request->all());

        $data = $videos->save();

        return response()->json(
            $videos,
            200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = videos::findOrFail($id);
        return $data;
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data :D',
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'url' => 'required|string',
            'date' => 'nullable|date'
        ]);

        $data = Videos::findOrFail($id);
        $data->fill($request->all());
        $data->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil memperbarui data :D',
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = videos::findOrFail($id);

        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menghapus data :(',
            'data' => $data
        ], 200);
    }
}
