<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KrsController extends Controller
{
    public function index(){
        return view('index');
    }



    public function getTAaktif()
    {
    $query = DB::connection('siakad')->table('tahun_ajaran')
        ->select('kode')
        ->where('set_aktif', '=', '1')
        ->first();

    return $query->kode;;
    }


    // ubah status mahasiswa mangkir
      public function changestatus()
    {
        $ta =$this->getTAaktif();
        $jenis = substr($ta,4,1);
        $tahun = substr($ta,0,4);


        // Get the active academic year code from Siakad
       $data =DB::connection('siadin')->table('mhs')
       ->select('id', 'nim', 'nama', 'akdm_stat')
       ->whereIn('akdm_stat', [1, 2, 8]);
       if ($jenis == 1) {
        $data->where('nim', 'not like', '%' . $tahun . '%');
        }
       $data = $data->whereNotIn('nim', function ($query) {
           $query->select('nim')
               ->from('biku_reg')
               ->where('ta', '=', DB::connection('siakad')->table('tahun_ajaran')
                   ->where('set_aktif_biku', '=', 1)
                   ->value('kode'))
               ->where('spp_bayar', '=', 1);
       })
       ->whereNotIn('nim', function ($query) {
           $query->select('nim')
               ->from(DB::connection('siakad')->table('krs_verified'))
               ->where('verified', '=', 1)
               ->where('ta', '=', DB::connection('siakad')->table('tahun_ajaran')
                   ->where('set_aktif_biku', '=', 1)
                   ->value('kode'));
       })->get();
          dd($data);
    }

    // fungsi change status base on pembayaran
    public function baseonpembayaran()
    {
        $data =DB::connection('siadin')->table('mhs')
        ->select('id', 'nim', 'nama', 'akdm_stat')
        ->whereNotIn('akdm_stat', [3,4,6,7, 8])
        ->whereIn('nim', function ($query) {
            $query->select('nim')
                ->from('biku_reg')
                ->where('ta', '=', DB::connection('siakad')->table('tahun_ajaran')
                    ->where('set_aktif_biku', '=', 1)
                    ->value('kode'))
                ->where('spp_bayar', '=', 1);
        })
        ->whereIn('nim', function ($query) {
            $query->select('nim')
                ->from(DB::connection('siakad')->table('krs_verified'))
                ->where('verified', '=', 1)
                ->where('ta', '=', DB::connection('siakad')->table('tahun_ajaran')
                    ->where('set_aktif_biku', '=', 1)
                    ->value('kode'));
        })
        ->get();
         dd($data);
    }

    // function memindah table temp ke aktiv
    public function MoveAktif(){
        DB::connection('siakad')->beginTransaction();

        try {

            DB::connection('siakad')->statement('DELETE FROM krs_jadwal_aktiv');

            DB::connection('siakad')->statement('INSERT INTO krs_jadwal_aktiv SELECT * FROM krs_jadwal_temp');

            DB::connection('siakad')->statement('DELETE FROM krs_aktiv');

            DB::connection('siakad')->statement('INSERT INTO krs_aktiv SELECT * FROM krs_aktiv_copy');

            DB::connection('siakad')->commit();

            return response()->json(['message', 'data berhasil dihapus dan dipindah'],200);

        } catch (\Exception $e) {
            DB::connection('siakad')->rollback();
            throw $e;
        }

    }


    // function memindah table aktiv ke archive
    public function MoveArchive(){
        DB::connection('siakad')->beginTransaction();

        try {
            // insert data dari krs_jadwal_aktif ke krs_jadwal_archive
            DB::connection('siakad')->statement('INSERT INTO krs_jadwal_archive SELECT * FROM krs_jadwal_active');

            // insert data dari krs_aktif_copy ke krs_aktiv
            DB::connection('siakad')->statement('INSERT INTO krs_aktiv SELECT * FROM krs_aktiv_copy');

            // mengkosongkan data dari tabel krs_jadwal_temp
            DB::connection('siakad')->statement('DELETE FROM krs_jadwal_temp');

            // mengkosongkan data dari tabel krs_active_copy
            DB::connection('siakad')->statement('DELETE FROM krs_active_copy');

            DB::connection('siakad')->commit();

        } catch (\Exception $e) {
            DB::connection('siakad')->rollback();
            throw $e;
        }
    }
}
