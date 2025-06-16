<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- PASTIKAN BARIS INI ADA
use App\Models\Criterion;
use Illuminate\Validation\Rule; // Import Rule
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CriterionController extends Controller
{
    public function __construct()
    {
        // Check if user is admin for all methods
        if (!Auth::check() || Auth::user()->name !== 'Admin') {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criteria = Criterion::orderBy('id')->paginate(10); // Menampilkan 10 data per halaman
        return view('criteria.index', compact('criteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('criteria.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:criteria,code',
            'type' => ['required', Rule::in(['benefit', 'cost'])], // Validasi enum
            'weight' => 'required|numeric|min:0', // Bobot tidak boleh negatif
        ]);

        // Optional: Validasi total bobot jika diperlukan
        // $totalWeight = Criterion::sum('weight') + $request->weight;
        // if ($totalWeight > 1) { // Asumsi normalisasi bobot ke 1
        //     return back()->withErrors(['weight' => 'Total bobot kriteria tidak boleh melebihi 1.'])->withInput();
        // }

        Criterion::create($request->all()); // Pastikan $fillable di model sudah sesuai

        return redirect()->route('criteria.index')
                         ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Criterion $criterion)
    {
        return redirect()->route('criteria.edit', $criterion->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Criterion $criterion)
    {
        return view('criteria.edit', compact('criterion'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Criterion $criterion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('criteria')->ignore($criterion->id), // Kode harus unik, kecuali untuk dirinya sendiri
            ],
            'type' => ['required', Rule::in(['benefit', 'cost'])],
            'weight' => 'required|numeric|min:0',
        ]);

        // Optional: Validasi total bobot saat update
        // $otherWeights = Criterion::where('id', '!=', $criterion->id)->sum('weight');
        // $totalWeight = $otherWeights + $request->weight;
        // if ($totalWeight > 1) { // Asumsi normalisasi bobot ke 1
        //     return back()->withErrors(['weight' => 'Total bobot kriteria tidak boleh melebihi 1.'])->withInput();
        // }

        $criterion->update($request->all());

        return redirect()->route('criteria.index')
                         ->with('success', 'Kriteria berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criterion $criterion)
    {
        // Pengecekan apakah kriteria masih digunakan dalam tabel scores
        if ($criterion->scores()->exists()) {
             return redirect()->route('criteria.index')
                              ->with('error', 'Gagal menghapus kriteria. Kriteria ini masih digunakan dalam data skor.');
        }

        try {
            $criterion->delete();
            return redirect()->route('criteria.index')
                             ->with('success', 'Kriteria berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('criteria.index')
                             ->with('error', 'Terjadi kesalahan saat menghapus kriteria: ' . $e->getMessage());
        }
    }
}
