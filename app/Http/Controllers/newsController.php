<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\news;
use Illuminate\Http\Request;

class newsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum', [
        //     'except' => ['index', 'show']
        // ]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = news::all();
        return $data;
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
            $fileName ='news' . time() . '.' . $request->image_url->extension();
            $file = $request->file('image_url');
            $file->move(public_path($customPath), $fileName);
            $news->image_url = $fileName;
        }

        dd($news);
        $data = $news->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menyimpan data :D'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
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
        ]);

        $data = news::findOrFail($id);
        if ($request->image_url != null) {
            if ($request->image_url != $data->image_url) {
                dd($request->image_url);

                $customPath = 'uploads/files/';

                $fileName = $userid . '_attactment_' . time() . '.' . $request->image_url->extension();
                $file = $request->file('image_url');
                $file->move(public_path($customPath), $fileName);
                $data->image_url = $fileName;
            }
        }

        $data->fill($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
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
