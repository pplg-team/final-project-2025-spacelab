<?php

namespace App\Http\Controllers\Admin\Term;

use App\Http\Controllers\Controller;
use App\Models\Block;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    /**
     * Store a newly created block.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'terms_id' => 'required|exists:terms,id',
                    'name' => 'required|string|max:255',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after:start_date'
                ],
                [
                    'terms_id.required' => 'Tahun ajaran wajib diisi',
                    'terms_id.exists' => 'Tahun ajaran tidak ditemukan',
                    'name.required' => 'Nama block wajib diisi',
                    'name.max' => 'Nama block maksimal 255 karakter',
                    'start_date.required' => 'Tanggal mulai wajib diisi',
                    'end_date.required' => 'Tanggal selesai wajib diisi',
                    'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai'
                ]
            );

            Block::create($validated);

            return redirect()->route('admin.terms.index')->with('success', 'Block berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified block (for edit modal).
     */
    public function show(Block $block)
    {
        return response()->json([
            'id' => $block->id,
            'terms_id' => $block->terms_id,
            'name' => $block->name,
            'start_date' => $block->start_date->format('Y-m-d'),
            'end_date' => $block->end_date->format('Y-m-d')
        ]);
    }

    /**
     * Update the specified block.
     */
    public function update(Request $request, Block $block)
    {
        try {
            $validated = $request->validate(
                [
                    'name' => 'required|string|max:255',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after:start_date'
                ],
                [
                    'name.required' => 'Nama block wajib diisi',
                    'name.max' => 'Nama block maksimal 255 karakter',
                    'start_date.required' => 'Tanggal mulai wajib diisi',
                    'end_date.required' => 'Tanggal selesai wajib diisi',
                    'end_date.after' => 'Tanggal selesai harus setelah tanggal mulai'
                ]
            );

            $block->update($validated);

            return redirect()->route('admin.terms.index')->with('success', 'Block berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified block from storage.
     */
    public function destroy(Block $block)
    {
        try {
            $block->delete();
            return redirect()->route('admin.terms.index')->with('success', 'Block berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus block: ' . $e->getMessage());
        }
    }
}
