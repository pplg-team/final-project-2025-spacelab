<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use App\Models\Block;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TermController extends Controller
{
    public function index()
    {
        $terms = Term::with('blocks')->orderBy('start_date', 'desc')->get();
        return view('admin.term.index', [
            'terms' => $terms,
            'title' => 'Tahun Ajaran',
            'description' => 'Halaman tahun ajaran',
        ]);
    }

    public function store(Request $request)
    {
        try {

            $isActive = $request->boolean('is_active');
            $validated = $request->validate(
                [
                    'tahun_ajaran' => 'required|string|max:255',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after:start_date',
                    'kind' => 'required|in:ganjil,genap',
                    'is_active' => 'nullable|boolean'
                ],
                [
                    'tahun_ajaran.required' => 'Tahun ajaran wajib diisi',
                    'start_date.required' => 'Tanggal mulai wajib diisi',
                    'end_date.required' => 'Tanggal selesai wajib diisi',
                    'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai',
                    'kind.required' => 'Jenis semester wajib diisi',
                    'kind.in' => 'Jenis semester harus ganjil atau genap',
                    'is_active.boolean' => 'Status aktif harus boolean'
                ]
            );

            $validated['is_active'] = $isActive;

            // If this term is set as active, deactivate all other terms
            if ($validated['is_active']) {
                Term::where('is_active', true)->update(['is_active' => false]);
            }

            Term::create($validated);

            return redirect()->route('admin.terms.index')->with('success', 'Tahun ajaran berhasil ditambahkan');
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function edit(Term $term)
    {
        return response()->json([
            'id' => $term->id,
            'tahun_ajaran' => $term->tahun_ajaran,
            'start_date' => $term->start_date->format('Y-m-d'),
            'end_date' => $term->end_date->format('Y-m-d'),
            'kind' => $term->kind,
            'is_active' => $term->is_active
        ]);
    }

    public function update(Request $request, Term $term)
    {
        try {
            $isActive = $request->boolean('is_active');
            $validated = $request->validate(
                [
                    'tahun_ajaran' => 'required|string|max:255',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after:start_date',
                    'kind' => 'required|in:ganjil,genap',
                    'is_active' => 'nullable|boolean'
                ],
                [
                    'tahun_ajaran.required' => 'Tahun ajaran wajib diisi',
                    'start_date.required' => 'Tanggal mulai wajib diisi',
                    'end_date.required' => 'Tanggal selesai wajib diisi',
                    'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai',
                    'kind.required' => 'Jenis semester wajib diisi',
                    'kind.in' => 'Jenis semester harus ganjil atau genap',
                    'is_active.boolean' => 'Status aktif harus boolean'
                ]
            );

            $validated['is_active'] = $isActive;

            // If this term is set as active, deactivate all other terms
            if ($validated['is_active']) {
                Term::where('id', '!=', $term->id)->where('is_active', true)->update(['is_active' => false]);
            }

            $term->update($validated);

            return redirect()->route('admin.terms.index')->with('success', 'Tahun ajaran berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Term $term)
    {
        try {
            $term->delete();
            return redirect()->route('admin.terms.index')->with('success', 'Tahun ajaran berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus tahun ajaran: ' . $e->getMessage());
        }
    }
}
