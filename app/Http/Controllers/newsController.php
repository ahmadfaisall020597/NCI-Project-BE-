<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\news;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    // Modify your query to include the image URL
    $data = $query->paginate($perPage);

    // Map the image URLs to include the full path
    $data->getCollection()->transform(function ($item) {
        $item->image_url = asset('/uploads/files/' . $item->image_url); // Assuming image_url contains the filename
        return $item;
    });

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
            $fileName = 'news_' . time() . '.' . $request->image_url->extension();
            $file = $request->file('image_url');
            $file->move(public_path($customPath), $fileName);
            $news->image_url = url($customPath . $fileName);
        }

        $news->save();

        return response()->json(
            $news,
            200
        );
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
        $isJson = $request->isJson() || $request->ajax();
        $input = $isJson ? $request->json()->all() : $request->only(['title', 'deskripsi', 'date']);

        $validator = Validator::make($input, [
            'title' => 'required|string',
            'deskripsi' => 'required|string',
            'date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = News::findOrFail($id);
        $data->fill($input);

        if ($request->hasFile('image_url')) {
            $imageValidator = Validator::make($request->only('image_url'), [
                'image_url' => 'required|image|max:2048',
            ]);

            if ($imageValidator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Image validation error',
                    'errors' => $imageValidator->errors()
                ], 422);
            }

            $customPath = 'uploads/files/';
            $fileName = 'news_' . time() . '.' . $request->image_url->extension();
            $fullPath = public_path($customPath);

            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }

            $request->file('image_url')->move($fullPath, $fileName);
            $data->image_url = url($customPath . $fileName);
        }

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
