<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule; // Import Rule
use Illuminate\Http\Request;
use App\Models\Alternative;


class AlternativeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alternatives = Alternative::orderBy('id')->paginate(10); // Menampilkan 10 data per halaman
        return view('alternatives.index', compact('alternatives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('alternatives.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:alternatives,name',
        ]);

        Alternative::create($request->only('name'));

        return redirect()->route('alternatives.index')
                         ->with('success', 'Alternatif berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alternative $alternative) // Menggunakan Route Model Binding
    {
         // Jika Anda ingin halaman detail, buat view 'alternatives.show'
         // return view('alternatives.show', compact('alternative'));

         // Atau redirect ke edit atau index
         return redirect()->route('alternatives.edit', $alternative->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alternative $alternative) // Menggunakan Route Model Binding
    {
        return view('alternatives.edit', compact('alternative'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alternative $alternative) // Menggunakan Route Model Binding
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('alternatives')->ignore($alternative->id), // Nama harus unik, kecuali untuk dirinya sendiri
            ],
        ]);

        $alternative->update($request->only('name'));

        return redirect()->route('alternatives.index')
                         ->with('success', 'Alternatif berhasil diperbarui.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alternative $alternative) // Menggunakan Route Model Binding
    {
        try {
            // Hapus juga skor terkait jika diperlukan (jika tidak menggunakan onDelete('cascade'))
            // $alternative->scores()->delete(); // Hati-hati jika relasi tidak diset cascade

            $alternative->delete();
            return redirect()->route('alternatives.index')
                             ->with('success', 'Alternatif berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
             // Tangani error jika ada constraint foreign key (misalnya jika skor masih ada dan tidak cascade)
             return redirect()->route('alternatives.index')
                             ->with('error', 'Gagal menghapus alternatif. Mungkin masih digunakan dalam data skor.');
        }
    }

}
