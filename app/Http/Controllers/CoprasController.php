<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Untuk transaksi jika diperlukan

class CoprasController extends Controller
{
    public function inputScores()
    {
        $alternatives = Alternative::orderBy('id')->get();
        $criteria = Criterion::orderBy('id')->get();
        $scores = [];

        // Ambil skor yang sudah ada untuk ditampilkan di form
        foreach ($alternatives as $alternative) {
            foreach ($criteria as $criterion) {
                $score = Score::where('alternative_id', $alternative->id)
                              ->where('criterion_id', $criterion->id)
                              ->first();
                $scores[$alternative->id][$criterion->id] = $score ? $score->value : null;
            }
        }

        return view('copras.input_scores', compact('alternatives', 'criteria', 'scores'));
    }

    public function saveScores(Request $request)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*.*' => 'required|numeric|min:0', // Asumsi nilai tidak negatif
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->scores as $alternative_id => $criteria_scores) {
                foreach ($criteria_scores as $criterion_id => $value) {
                    Score::updateOrCreate(
                        ['alternative_id' => $alternative_id, 'criterion_id' => $criterion_id],
                        ['value' => $value]
                    );
                }
            }
            DB::commit();
            return redirect()->route('copras.inputScores')->with('success', 'Nilai berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage())->withInput();
        }
    }

    public function calculateAndShowResults()
    {
        $alternatives = Alternative::with('scores')->orderBy('id')->get();
        $criteria = Criterion::orderBy('id')->get();

        if ($alternatives->isEmpty() || $criteria->isEmpty() || Score::count() == 0) {
            return view('copras.results', [
                'alternatives' => $alternatives,
                'criteria' => $criteria,
                'results' => []
            ])->with('error', 'Data alternatif, kriteria, atau nilai belum lengkap.');
        }
        
        // Cek apakah semua alternatif memiliki nilai untuk semua kriteria
        foreach ($alternatives as $alt) {
            if ($alt->scores->count() < $criteria->count()) {
                 return view('copras.results', [
                    'alternatives' => $alternatives,
                    'criteria' => $criteria,
                    'results' => []
                ])->with('error', 'Tidak semua alternatif memiliki nilai untuk semua kriteria. Silakan lengkapi input nilai.');
            }
        }


        // 1. Matriks Keputusan (X_ij)
        $decisionMatrix = [];
        foreach ($alternatives as $alt) {
            foreach ($criteria as $crit) {
                $score = $alt->scores->where('criterion_id', $crit->id)->first();
                $decisionMatrix[$alt->id][$crit->id] = $score ? $score->value : 0;
            }
        }

        // 2. Normalisasi Matriks Keputusan (R_ij)
        // r_ij = x_ij / sum(x_kj for all alternatives k on criterion j)
        $normalizedMatrix = [];
        $sumPerCriterion = [];
        foreach ($criteria as $crit) {
            $sumPerCriterion[$crit->id] = 0;
            foreach ($alternatives as $alt) {
                $sumPerCriterion[$crit->id] += $decisionMatrix[$alt->id][$crit->id];
            }
        }

        foreach ($alternatives as $alt) {
            foreach ($criteria as $crit) {
                if ($sumPerCriterion[$crit->id] == 0) { // Hindari pembagian dengan nol
                    $normalizedMatrix[$alt->id][$crit->id] = 0;
                } else {
                    $normalizedMatrix[$alt->id][$crit->id] = $decisionMatrix[$alt->id][$crit->id] / $sumPerCriterion[$crit->id];
                }
            }
        }

        // 3. Normalisasi Terbobot (D_ij)
        // d_ij = w_j * r_ij
        $weightedNormalizedMatrix = [];
        foreach ($alternatives as $alt) {
            foreach ($criteria as $crit) {
                $weightedNormalizedMatrix[$alt->id][$crit->id] = $normalizedMatrix[$alt->id][$crit->id] * $crit->weight;
            }
        }

        // 4. Hitung Jumlah Indeks Benefit (P_i) dan Cost (R_i)
        $sumP = []; // Benefit
        $sumR = []; // Cost
        foreach ($alternatives as $alt) {
            $sumP[$alt->id] = 0;
            $sumR[$alt->id] = 0;
            foreach ($criteria as $crit) {
                if ($crit->type == 'benefit') {
                    $sumP[$alt->id] += $weightedNormalizedMatrix[$alt->id][$crit->id];
                } else { // cost
                    $sumR[$alt->id] += $weightedNormalizedMatrix[$alt->id][$crit->id];
                }
            }
        }
        
        // 5. Hitung Nilai Signifikansi Relatif (Q_i)
        // Q_i = P_i + ( (sum_total_R_k_values) / (R_i * sum_total_inverse_R_k_values) )
        // dimana sum_total_R_k_values = Σ R_k (untuk semua alternatif k)
        // dan sum_total_inverse_R_k_values = Σ (1/R_k) (untuk semua alternatif k)
        $Q_values = [];
        $total_R_sum = array_sum($sumR);
        $total_inverse_R_sum = 0;
        foreach ($alternatives as $alt) {
            if ($sumR[$alt->id] > 0) { // Hindari pembagian dengan nol
                $total_inverse_R_sum += (1 / $sumR[$alt->id]);
            }
        }
        
        foreach ($alternatives as $alt) {
            $Pi = $sumP[$alt->id];
            $Ri = $sumR[$alt->id];
            
            if ($Ri == 0) { // Jika Ri adalah 0, maka kontribusi dari bagian cost adalah maksimal (atau dihandle khusus)
                // Jika R_i = 0, artinya costnya sangat kecil (ideal).
                // Dalam kasus ini, bagian kedua formula COPRAS bisa menjadi tak terhingga jika tidak hati-hati.
                // Salah satu pendekatan: Jika Ri = 0, maka suku kedua dianggap 0 (karena tidak ada cost yang perlu diminimalkan relatif)
                // atau kita asumsikan R_i memiliki nilai sangat kecil agar pembagian valid.
                // Untuk simplisitas, jika Ri = 0, kita hanya pakai Pi. Ini perlu dikaji lebih dalam untuk kasus nyata.
                // Atau, jika total_inverse_R_sum juga nol (semua R adalah nol), maka Q_i = P_i.
                if ($total_inverse_R_sum == 0) { // semua R adalah 0
                    $Q_values[$alt->id] = $Pi;
                } else {
                    // Jika hanya R_i ini yang nol, ini situasi khusus.
                    // Bisa jadi kontribusi cost dianggap sangat baik.
                    // Untuk menghindari error, kita bisa set Q_i hanya P_i,
                    // atau memberikan nilai yang sangat besar pada bagian kedua.
                    // Untuk tutorial, jika R_i=0 dan ada R lain yang tidak nol, ini anomali.
                    // Mari kita asumsikan R_i tidak akan 0 jika ada R lain yang tidak 0.
                    // Jika R_i adalah 0 dan cost adalah 'lebih baik kecil', maka ini yang terbaik.
                    // Simplifikasi: jika Ri = 0, maka bagian cost tidak menambah Q (karena sudah optimal).
                    // Ini perlu justifikasi teoritis yang kuat.
                    // Untuk perhitungan praktis, kita butuh Ri > 0.
                    // Kita akan set $Q_values[$alt->id] = $Pi; jika $Ri == 0
                    // Atau, jika $Ri == 0, maka suku ( $total_R_sum / ($Ri * $total_inverse_R_sum) ) menjadi tidak valid.
                    // Jika $Ri=0 (tidak ada cost), maka $Q_i = $Pi. Ini adalah asumsi yang wajar.
                     $Q_values[$alt->id] = $Pi;
                }
            } elseif ($total_inverse_R_sum == 0) { // Ri > 0 tapi semua R lain 0 (tidak mungkin jika total_R_sum > 0)
                 $Q_values[$alt->id] = $Pi; // Seharusnya tidak terjadi jika total_R_sum > 0
            }
             else {
                 $Q_values[$alt->id] = $Pi + ($total_R_sum / ($Ri * $total_inverse_R_sum));
            }
        }

        // 6. Hitung Utilitas Kuantitatif (N_i)
        // N_i = (Q_i / max(Q_j)) * 100%
        $N_values = [];
        $max_Q = 0;
        if (!empty($Q_values)) {
            $max_Q = max($Q_values);
        }

        foreach ($alternatives as $alt) {
            if ($max_Q == 0) { // Hindari pembagian dengan nol jika semua Q adalah 0
                $N_values[$alt->id] = 0;
            } else {
                $N_values[$alt->id] = ($Q_values[$alt->id] / $max_Q) * 100;
            }
        }

        // 7. Peringkat
        $results = [];
        foreach ($alternatives as $alt) {
            $results[] = [
                'alternative_id' => $alt->id,
                'alternative_name' => $alt->name,
                'Q' => $Q_values[$alt->id],
                'N' => $N_values[$alt->id],
            ];
        }

        // Urutkan berdasarkan N_i tertinggi
        usort($results, function ($a, $b) {
            return $b['N'] <=> $a['N']; // Descending order
        });

        return view('copras.results', compact(
            'alternatives', 
            'criteria', 
            'decisionMatrix', 
            'normalizedMatrix', 
            'weightedNormalizedMatrix',
            'sumP',
            'sumR',
            'results' // Ini sudah berisi Q dan N serta nama alternatif
        ));
    }
}