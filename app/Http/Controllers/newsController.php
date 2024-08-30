<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\news;
use Illuminate\Http\Request;

class newsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', [
            'except' => ['index', 'show', 'viewsDashboard']
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $query = news::query();
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }
        $data = $query->paginate($perPage);
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data :D',
            'data' => $data
        ], 200);
    }

    public function viewsDashboard()
    {
        $data = news::orderBy('date', 'desc')
            ->take(10)
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
            'deskripsi' => 'required|string',
            'image_url' => 'required|image|max:2048',
        ]);
        $news = new news($request->all());

        if ($request->file('image_url')) {
            $customPath = 'uploads/files/';
            $fileName = 'news' . time() . '.' . $request->image_url->extension();
            $file = $request->file('image_url');
            $file->move(public_path($customPath), $fileName);
            $news->image_url = $fileName;
        }


        $data = $news->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menyimpan data :D'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = news::findOrFail($id);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'deskripsi' => 'required|string',
            'date' => 'nullable|date'
        ]);

        $data = news::findOrFail($id);

        if ($request->hasFile('image_url')) {
            $customPath = 'uploads/files/';

            $fileName = $id . '_attachment_' . time() . '.' . $request->image_url->extension();

            $request->file('image_url')->move(public_path($customPath), $fileName);

            $data->image_url = $customPath . $fileName;
        }

        $data->fill($request->except(['image_url']));
        $data->save();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = news::findOrFail($id);
        $customPath = 'uploads/files/';
        if ($data->image_url && file_exists(public_path($customPath) . '/' . $data->image_url)) {
            unlink(public_path($customPath) . '/' . $data->image_url);
        }
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menghapus data :(',
            'data' => $data
        ], 200);
    }
}
