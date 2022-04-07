<?php

namespace App\Http\Controllers;

use App\Exports\WaliKelasFormatExport;
use App\Models\Guru;
use App\Models\Setting;
use App\Models\WaliKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class WaliKelasController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request;

        $setting = Setting::all()[0];

        if ($request->has('tahun_pelajaran')) {
            $wali_kelas = WaliKelas::where('tahun_pelajaran', $filter->tahun_pelajaran)->get();
        } else {
            $wali_kelas = WaliKelas::where('tahun_pelajaran', $setting->tahun_pelajaran)->get();
        }

        $guru = Guru::all();

        $tahun_pelajaran = ['2019 / 2020', '2020 / 2021', '2021 / 2022', '2022 / 2023', '2023 / 2024'];

        return view('admin.wali-kelas.index', compact('filter', 'setting', 'wali_kelas', 'guru', 'tahun_pelajaran'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kelas' => 'required',
            'guru' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data wali kelas gagal diperbarui');
        }

        $wali_kelas = WaliKelas::find($id);

        $wali_kelas->update([
            'kelas' => $request->kelas,
            'guru_id' => $request->guru,
        ]);

        return redirect()->back()->with('success', 'Data wali kelas berhasil diperbarui');
    }

    public function destroy($id)
    {
        $wali_kelas = WaliKelas::find($id);

        $wali_kelas->guru->user->update([
            'role' => 'guru'
        ]);

        $wali_kelas->delete();

        return redirect()->back()->with('success', 'Data wali kelas berhasil dihapus');
    }

    public function import()
    {
        try {
            Excel::import(new \App\Imports\WaliKelasImport, request()->file('data_wali_kelas'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Data wali kelas gagal diimport');
        }

        return redirect()->back()->with('success', 'Data wali kelas berhasil diimport');
    }

    public function export_format()
    {
        return Excel::download(new WaliKelasFormatExport(), 'data-wali_kelas-mutuharjo' . '.xlsx');
    }
}
