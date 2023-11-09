<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KrsController extends Controller
{
    public function index()
    {
        return view('index');
    }


    public function createTable()
    {
        $date = date("Y_m_d");
        $connectionName = 'tampung';

        $query = "CREATE TABLE {$connectionName}.status_bayar_{$date} (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nim VARCHAR(14),
            nama VARCHAR(100),
            akdm_stat CHAR(2)
        )";

        DB::connection($connectionName)->statement($query);
    }


    public function getTAaktif()
    {
        $query = DB::connection('siakad')->table('tahun_ajaran')
            ->select('kode')
            ->where('set_aktif', '=', '1')
            ->first();

        return $query->kode;
    }


    // ubah status mahasiswa mangkir
    public function changestatusmangkir()
    {
        set_time_limit(300);
        $ta = $this->getTAaktif();
        $jenis = substr($ta, 4, 1);
        $tahun = substr($ta, 0, 4);

        $data = DB::connection('siadin')->table('mhs')
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

            // dd($data);


        //   backup data hasil query
        $date = date("Y_m_d");
        foreach ($data as $selectdata) {
            $existingData = DB::connection('tampung')
            ->table("status_bayar_{$date}")
            ->where('id', $selectdata->id)
            ->first();

        // Jika data tidak ada, lakukan penyisipan
        if (!$existingData) {
            DB::connection('tampung')
                ->table("status_bayar_{$date}")
                ->insert([
                    'id' => $selectdata->id,
                    'nim' => $selectdata->nim,
                    'nama' => $selectdata->nama,
                    'akdm_stat' => $selectdata->akdm_stat,
                ]);
        }

            //   Update field akademi status hasil query pada tabel mhs
            DB::connection('siadin')->table('mhs')
                ->where('id', $selectdata->id)
                ->update(['akdm_stat' => 5]);
        }

        return response()->json(['message' => 'Data Berhasil di Update']);
    }

    public function restoreStatusMangkir()
    {
        set_time_limit(300);
        // get data table backup
        $date = date("Y_m_d");
        $selectdata = DB::connection('tampung')->table("status_bayar_{$date}")->get();
        // dd($selectdata);
        // Mengembalikan data awal tabel mahasiswa sebelum diupdate
        foreach ($selectdata as $data) {
            DB::connection('siadin')->table('mhs')
                ->where('id', $data->id)
                ->update(['akdm_stat' => $data->akdm_stat]);
        }
        return response()->json(['message' => 'Data Berhasil di kembalikan']);

    }


    // fungsi change status base on pembayaran
    public function baseonpembayaran()
    {
        $date = date("Y_m_d");
        set_time_limit(300);

        $data = DB::connection('siadin')->table('mhs')
            ->select('id', 'nim', 'nama', 'akdm_stat')
            ->whereNotIn('akdm_stat', [3, 4, 6, 7, 8])
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
            // dd($data);


        //   backup data hasil query
        foreach ($data as $selectdata) {
            $existingData = DB::connection('tampung')
            ->table("status_bayar_{$date}")
            ->where('id', $selectdata->id)
            ->first();

        // Jika data tidak ada, lakukan penyisipan
        if (!$existingData) {
            DB::connection('tampung')
                ->table("status_bayar_{$date}")
                ->insert([
                    'id' => $selectdata->id,
                    'nim' => $selectdata->nim,
                    'nama' => $selectdata->nama,
                    'akdm_stat' => $selectdata->akdm_stat,
                ]);
        }

                //   Update field akademi status hasil query pada tabel mhs
            DB::connection('siadin')->table('mhs')
                ->where('id', $selectdata->id)
                ->update(['akdm_stat' => 8]);
        }

    }

    public function restoreBaseOnPembayaran()
    {
        $date = date("Y_m_d");
        set_time_limit(300);
        // get data table backups
        $selectdata = DB::connection('tampung')->table("status_bayar_{$date}")->get();

        // Mengembalikan data awal tabel mahasiswa sebelum diupdate
        foreach ($selectdata as $data) {
            DB::connection('siadin')->table('mhs')
                ->where('id', $data->id)
                ->update(['akdm_stat' => $data->akdm_stat]);
        }

        // dd($selectdata);

    }



    public function changestatuscuti()
    {
        $data = DB::connection('siadin')
            ->table('mhs')
            ->select('id', 'nim', 'nama', 'akdm_stat')
            ->whereIn('nim', function ($query) {
                $query->select('nim')
                    ->from(DB::connection('siakad')->table('mahasiswa_cuti'))
                    ->where('tahun_ajar1', function ($subQuery) {
                        $subQuery->select('tahun_awal')
                            ->from(DB::connection('siakad')->table('tahun_ajaran'))
                            ->where('set_aktif_biku', 1);
                    })
                    ->where('tahun_ajar2', function ($subQuery) {
                        $subQuery->select('tahun_akhir')
                            ->from(DB::connection('siakad')->table('tahun_ajaran'))
                            ->where('set_aktif_biku', 1);
                    })
                    ->where('jenis_semester', ['ganjil', 'genap']); // Ganti dengan nilai yang sesuai
            })
            ->get();

            dd($data);

        //   backup data hasil query
        foreach ($data as $selectdata) {
            DB::connection('tampung')
                ->table('mhs_cuti_2023_9-11-2023')
                ->insert([
                    'id' => $selectdata->id,
                    'nim' => $selectdata->nim,
                    'nama' => $selectdata->nama,
                    'akdm_stat' => $selectdata->akdm_stat,
                ]);

                     //   Update field akademi status hasil query pada tabel mhs
            DB::connection('siadin')->table('mhs')
                ->where('id', $data->id)
                ->update(['akdm_stat' => 2]);
        }
        // }


    }


    //
    public function restoreDataCuti()
    {
        // get data table backup
        $selectdata = DB::connection('tampung')->table('mhs_cuti_2023_9-11-2023')->get();

        // Mengembalikan data awal tabel mahasiswa sebelum diupdate
        foreach ($selectdata as $data) {
            DB::connection('siadin')->table('mhs')
                ->where('id', $data->id)
                ->update(['akdm_stat' => $data->akdm_stat]);
        }
        // dd($selectdata);
    }




    // function memindah table temp ke aktiv
    public function MoveAktif()
    {
        DB::connection('siakad')->beginTransaction();

        try {

            DB::connection('siakad')->statement('DELETE FROM krs_jadwal_aktiv');

            DB::connection('siakad')->statement('INSERT INTO krs_jadwal_aktiv SELECT * FROM krs_jadwal_temp');

            DB::connection('siakad')->statement('DELETE FROM krs_aktiv');

            DB::connection('siakad')->statement('INSERT INTO krs_aktiv SELECT * FROM krs_aktiv_copy');

            DB::connection('siakad')->commit();

            return response()->json(['message', 'data berhasil dihapus dan dipindah'], 200);

        } catch (\Exception $e) {
            DB::connection('siakad')->rollback();
            throw $e;
        }

    }



    // function memindah table aktiv ke archive
    public function MoveArchive()
    {
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
