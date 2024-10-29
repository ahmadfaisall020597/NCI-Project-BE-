<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\slideShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facedes\Validator;

class slideShowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', [
            'except' => ['index', 'show', 'viewsDashboard']
        ]);
    }

    /**
     * Display a listing of the resources
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);
        $query = slideShow::query();

        if($search) {
            $query->where('title', 'like', "%{$search}")
        }

        // Modify your query to include the image URL
        $data = $query->paginate($perPage);

        // Map the image URLs to include the full path
        $data->getCollection()->transform(function ($item) {
            if(!filter_var($item->image_url, FILTER_VALIDATE_URL)) {
                $item->image_url = asset('uploads/files/' . $item->image_url);
            }
            return $item;
        });

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data :D',
            'data' => $data
        ], 200)
    }

    public function viewsDashboard()
    {
        $data = slideShow::orderBy('date', 'desc')
            ->take(10)
            ->get()
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data :D',
            'data' => $data
        ], 200)
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'deskripsi' => 'required|string',
            'image_url' => 'required|image|max:2048'
        ]);

        $slideShow = new slideShow($request->all())

        if($request->file('image_url')) {
            $customPath = 'uploads/files/';
            $fileName = 'slideShow_' . time() . '.' . $request->image_url->extension();
            $file = $request->file('image_url');
            $file->move(public_path($customPath), $fileName);
            $slideShow->image_url = url($customPath . $fileName);
        }

        $slideShow->save();

        return response()->json(
            $slideShow.
            200
        );
    }

    /**
     * Display the specified resource .
     */
    public function show($id)
    {
        $data = slideShow::findOrFail($id)
        return $data;
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data :D',
            'data' => $data
        ], 200)
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage
     */

    /**
     * Update the specified resource in storage
     */

    public function updateSlideShow(Request $request, $id)
    {
        $validator = Validator::make($request->all(), {
            'title' => 'required|string',
            'deskripsi' => 'required|string',
            'date' => 'nullable|date',
        });

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422)
        }

        $data = slideShow::findOrFail($id)
        $data->fill($request->all());

        if($request->hasFile('image_url')) {
            $imageValidator = Validator::make($request->only('image_url'), [
                'image_url' => 'required|image|max:2048'
            ])

            if($imageValidator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Image validation error',
                    'errors' => $imageValidator->errors()
                ], 422)
            }

            $customPath = 'uploads/files/';
            $fileName = 'slideShow_' . time() . '.' . $request->image_url->extension();
            $fullPath = public_path($customPath)

            if(!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true)
            }

            $request->file('image_url')->move($fullPath, $fileName);
            $data->image_url = url($customPath . $fileName);

            $data->save();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => $data
            ], 200)
        }
    }
}
