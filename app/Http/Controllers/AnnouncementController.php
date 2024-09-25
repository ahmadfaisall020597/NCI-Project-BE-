<?php

namespace App\Http\Controllers;

use App\Models\announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', [
            'except' => ['index', 'show', 'indexAnnouncement']
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $query = Announcement::query();
        if ($search) {
            $query->where('deskripsi', 'like', "%{$search}%");
        }
        $data = $query->paginate($perPage);
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data!',
            'data' => $data
        ], 200);
    }

    public function indexAnnouncement()
    {
        $data = announcement::orderBy('date', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data!',
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
            'deskripsi' => 'required|string'
        ]);
        $announcement = new announcement($request->all());

        $data = $announcement->save();

        return response()->json(
            $announcement,
            200
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Announcement::findOrFail($id);
        return $data;
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data!',
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'deskripsi' => 'required|string',
            'date' => 'nullable|date'
        ]);

        $data = announcement::findOrFail($id);
        $data->fill($request->all());
        $data->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil memperbarui data!',
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = announcement::findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menghapus data!',
            'data' => $data
        ], 200);
    }
}
