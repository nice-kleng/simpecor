<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mitras = Mitra::orderBy('created_at', 'asc')->get();
        return view('admin.mitra', compact('mitras'));
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
            'nama_mitra' => 'required',
            'nama_pemilik' => 'required',
            'alamat' => 'required',
            'email' => 'required|email|unique:users,email',
            'telp' => 'required',
            'npwp' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Create user account
            $user = User::create([
                'name' => $request->nama_mitra,
                'email' => $request->email,
                'password' => Hash::make('password'), // Generate random password
                'role' => 'mitra'
            ]);

            // Create mitra with user_id
            $mitra = new Mitra($request->all());
            $mitra->user_id = $user->id;
            $mitra->save();

            DB::commit();
            return redirect()->route('mitra.index')->with('success', 'Mitra berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("message: {$e->getMessage()} file: {$e->getFile()} line: {$e->getLine()}");
            return redirect()->back()->with('error', 'Gagal menambahkan mitra');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Mitra $mitra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mitra $mitra)
    {
        return response()->json($mitra);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mitra $mitra)
    {
        $request->validate([
            'nama_mitra' => 'required',
            'alamat' => 'required',
            'email' => 'required|email|unique:users,email,' . $mitra->user_id,
            'telp' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Update user data
            $mitra->user->update([
                'name' => $request->nama_mitra,
                'email' => $request->email,
            ]);

            // Update mitra data
            $mitra->update($request->all());

            DB::commit();
            return redirect()->route('mitra.index')->with('success', 'Mitra berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui mitra');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mitra $mitra)
    {
        try {
            DB::beginTransaction();

            // Delete associated user
            $mitra->user->delete();
            // Mitra will be automatically deleted due to cascade delete

            DB::commit();
            return redirect()->route('mitra.index')->with('success', 'Mitra berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('mitra.index')->with('success', 'Gagal menghapus mitra');
        }
    }
}
