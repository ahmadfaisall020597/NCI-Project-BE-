<?php

namespace App\Http\Controllers;

use App\Models\pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelatihanController extends Controller
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
        $query = pelatihan::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $data = $query->paginate($perPage);

        $data->getCollection()->transform(function ($item) {
            // $item->image_kemendikbud_ristek = asset('/uploads/files' . $item->image_kemendikbud_ristek); // Assuming image_url contains the filename
            // $item->image_logo_nci = asset('/uploads/files' . $item->image_logo_nci); // Assuming image_url contains the filename
            // $item->image_logo_mitra = asset('/uploads/files' . $item->image_logo_mitra); // Assuming image_url contains the filename
            // $item->image_spanduk_pelatihan = asset('/uploads/files' . $item->image_spanduk_pelatihan); // Assuming image_url contains the filename
            if (!filter_var($item->image_kemendikbud_ristek, FILTER_VALIDATE_URL)) {
                $item->image_kemendikbud_ristek = asset($item->image_kemendikbud_ristek);
            }
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
        $data = pelatihan::orderBy('date', 'desc')
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
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'image_kemendikbud_ristek' => 'required|image|max:2048',
            'image_logo_nci' => 'required|image|max:2048',
            'image_logo_mitra' => 'required|image|max:2048',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|array', // Ubah validasi menjadi array
            'persyaratan.*' => 'string', // Setiap elemen dalam array harus berupa string
            'image_spanduk_pelatihan' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'duration' => 'required|string',
            'location' => 'required|string',
            'biaya' => 'required|string',
            'url_daftar' => 'required|string',
            'output' => 'required|string',
        ]);

        // Inisiasi objek pelatihan dengan semua data request
        $pelatihan = new pelatihan($request->except(['image_kemendikbud_ristek', 'image_logo_nci', 'image_logo_mitra', 'image_spanduk_pelatihan']));

        // Ubah 'persyaratan' array menjadi JSON sebelum menyimpannya
        $pelatihan->persyaratan = json_encode($request->persyaratan);

        if ($request->file('image_kemendikbud_ristek')) {
            $customPath = 'uploads/files/';
            $fileName = 'pelatihan_kemendikbud_' . time() . '.' . $request->image_kemendikbud_ristek->extension();
            $file = $request->file('image_kemendikbud_ristek');
            $file->move(public_path($customPath), $fileName);
            $pelatihan->image_kemendikbud_ristek = url($customPath . $fileName);
        }
        if ($request->file('image_logo_nci')) {
            $customPath = 'uploads/files/';
            $fileName = 'pelatihan_logo_nci_' . time() . '.' . $request->image_logo_nci->extension();
            $file = $request->file('image_logo_nci');
            $file->move(public_path($customPath), $fileName);
            $pelatihan->image_logo_nci = url($customPath . $fileName);
        }
        if ($request->file('image_logo_mitra')) {
            $customPath = 'uploads/files/';
            $fileName = 'pelatihan_logo_mitra_' . time() . '.' . $request->image_logo_mitra->extension();
            $file = $request->file('image_logo_mitra');
            $file->move(public_path($customPath), $fileName);
            $pelatihan->image_logo_mitra = url($customPath . $fileName);
        }
        if ($request->file('image_spanduk_pelatihan')) {
            $customPath = 'uploads/files/';
            $fileName = 'pelatihan_spanduk_' . time() . '.' . $request->image_spanduk_pelatihan->extension();
            $file = $request->file('image_spanduk_pelatihan');
            $file->move(public_path($customPath), $fileName);
            $pelatihan->image_spanduk_pelatihan = url($customPath . $fileName);
        }

        // Simpan data ke database
        $pelatihan->save();

        return response()->json($pelatihan, 200);
    }

    public function updatePelatihan(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|array',
            'persyaratan.*' => 'string',
            'duration' => 'required|string',
            'location' => 'required|string',
            'biaya' => 'required|numeric',
            'url_daftar' => 'required|string',
            'output' => 'required|string',
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = pelatihan::findOrFail($id);
        $data->fill($request->except(
            'image_kemendikbud_ristek',
            'image_logo_nci',
            'image_logo_mitra',
            'image_spanduk_pelatihan',
        ));

        $customPath = 'uploads/files';
        $fullPath = public_path($customPath);

        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        if ($request->hasFile('image_kemendikbud_ristek')) {
            $image_kemendikbud_ristek = $request->file('image_kemendikbud_ristek');
            $fileName = 'pelatihan_kemendikbud_' . time() . '.' . $image_kemendikbud_ristek->extension();
            $image_kemendikbud_ristek->move($fullPath, $fileName);
            $data->image_kemendikbud_ristek = url($customPath . '/' . $fileName);
        }

        if ($request->hasFile('image_logo_nci')) {
            $image_logo_nci = $request->file('image_logo_nci');
            $fileName = 'pelatihan_logo_nci_' . time() . '.' . $image_logo_nci->extension();
            $image_logo_nci->move($fullPath, $fileName);
            $data->image_logo_nci = url($customPath . '/' . $fileName);
        }

        if ($request->hasFile('image_logo_mitra')) {
            $image_logo_mitra = $request->file('image_logo_mitra');
            $fileName = 'pelatihan_logo_mitra_' . time() . '.' . $image_logo_mitra->extension();
            $image_logo_mitra->move($fullPath, $fileName);
            $data->image_logo_mitra = url($customPath . '/' . $fileName);
        }

        if ($request->hasFile('image_spanduk_pelatihan')) {
            $image_spanduk_pelatihan = $request->file('image_spanduk_pelatihan');
            $fileName = 'pelatihan_spanduk_' . time() . '.' . $image_spanduk_pelatihan->extension();
            $image_spanduk_pelatihan->move($fullPath, $fileName);
            $data->image_spanduk_pelatihan = url($customPath . '/' . $fileName);
        }
        $data->save();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $data
        ], 200);
    }

    public function destroy($id)
    {
        $data = pelatihan::findOrFail($id);
        $customPath = 'uploads/files';
        if ($data->image_kemendikbud_ristek && file_exists(public_path($customPath) . '/' . $data->image_kemendikbud_ristek)) {
            unlink(public_path($customPath) . '/' . $data->image_kemendikbud_ristek);
        }
        if ($data->image_logo_nci && file_exists(public_path($customPath) . '/' . $data->image_logo_nci)) {
            unlink(public_path($customPath) . '/' . $data->image_logo_nci);
        }
        if ($data->image_logo_mitra && file_exists(public_path($customPath) . '/' . $data->image_logo_mitra)) {
            unlink(public_path($customPath) . '/' . $data->image_logo_mitra);
        }
        if ($data->image_spanduk_pelatihan && file_exists(public_path($customPath) . '/' . $data->image_spanduk_pelatihan)) {
            unlink(public_path($customPath) . '/' . $data->image_spanduk_pelatihan);
        }
        $data->delete();
        return response()->json([
            'status' => true,
            'message' => 'Berhasil menghapus data :(',
            'data' => $data
        ], 200);
    }
}
