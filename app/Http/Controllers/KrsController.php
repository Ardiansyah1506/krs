<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KrsController extends Controller
{
      public function changestatus()
    {
        // Get the active academic year code from Siakad
       $data =DB::connection('siadin')->table('mhs')
       ->select('id', 'nim', 'name', 'akd_status')
       ->where('nim','not like', '%.2023.%')
       ->whereIn('akd_status', [1, 2, 8])
       ->whereNotIn('nim', function ($query) {
           $query->select('nim')
               ->from('biku_reg')
               ->where('ta', '=', DB::connection('siakad')->table('tahun_ajaran')
                   ->where('set_biku', '=', 1)
                   ->value('kode'))
               ->where('spp_bayar', '=', 1);
       })
       ->whereNotIn('nim', function ($query) {
           $query->select('nim')
               ->from(DB::connection('siakad')->table('krs_verified'))
               ->where('verified', '=', 1)
               ->where('ta', '=', DB::connection('siakad')->table('tahun_ajaran')
                   ->where('set_biku', '=', 1)
                   ->value('kode'));
       })
       ->get();
        dd($data);
    }
    public function baseonpembayaran()
    {
        $data =DB::connection('siadin')->table('mhs')
        ->select('id', 'nim', 'name', 'akd_status')
        ->whereNotIn('akd_status', [3,4,6,7, 8])
        ->whereIn('nim', function ($query) {
            $query->select('nim')
                ->from('biku_reg')
                ->where('ta', '=', DB::connection('siakad')->table('tahun_ajaran')
                    ->where('set_biku', '=', 1)
                    ->value('kode'))
                ->where('spp_bayar', '=', 1);
        })
        ->whereIn('nim', function ($query) {
            $query->select('nim')
                ->from(DB::connection('siakad')->table('krs_verified'))
                ->where('verified', '=', 1)
                ->where('ta', '=', DB::connection('siakad')->table('tahun_ajaran')
                    ->where('set_biku', '=', 1)
                    ->value('kode'));
        })
        ->get();
         dd($data);
    }
}
