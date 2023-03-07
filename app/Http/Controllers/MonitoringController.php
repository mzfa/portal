<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MonitoringController extends Controller
{
    public function index()
    {
        $menu = [];
        $data = DB::table('menu')->where(['parent_id' => 0])->whereNull('deleted_at')->get();
        // dd($data);
        foreach($data as $key => $item)
        {
            array_push($menu, [
                'menu_id' => $item->menu_id,
                'nama_menu' => $item->nama_menu,
                'url_menu' => $item->url_menu,
                'icon_menu' => $item->icon_menu,
                'parent_id' => $item->parent_id,
                'submenu' => []
            ]);
            $menu_id = $item->menu_id;
            $submenu = DB::table('menu')->where(['parent_id' => $menu_id])->whereNull('deleted_at')->get();
            // dd($submenu);
            foreach($submenu as $sub)
            {
                array_push($menu[$key]["submenu"], [
                    "menu_id" => $sub->menu_id,
                    "nama_menu" => $sub->nama_menu,
                    "url_menu" => $sub->url_menu,
                    "icon_menu" => $sub->icon_menu,
                ]);
                // dd($menu['submenu']);
            }
        }
        // dd($menu);
        return view('monitoring.index', compact('menu'));
    }

    public function unit()
    {
        $user_id = Auth::user()->id;
        $list_penerima = DB::table('users')
        ->leftJoin('user_akses', 'users.id', '=', 'user_akses.user_id')
        ->leftJoin('hakakses', 'hakakses.hakakses_id', '=', 'user_akses.hakakses_id')
        ->leftJoin('pegawai', 'pegawai.pegawai_id', '=', 'users.pegawai_id')
        ->leftJoin('bagian', 'bagian.bagian_id', '=', 'pegawai.bagian_id')
        // ->whereIn('hakakses.hakakses_id', $pecah_array)
        ->whereNotNull('user_akses.hakakses_id')
        ->get();
        // dd($list_penerima);
        return view('monitoring.unit', compact('list_penerima'));
    }   
    public function perunit($bagian){
        // $bagian = Auth::user()->id;
        // dd($bagian);
        $list_surat = DB::table('surat')
        ->where(['surat.bagian' => $bagian])
        ->whereNotNull('surat.created_by')
        ->whereNull('surat.deleted_at')
        ->orderByDesc('surat.created_at')
        // ->where('surat.judul_surat', 'like', '%'. $pencarian .'%')
        ->get();
        // dd($list_surat);
        return view('message.sent', compact('list_surat'));
    }
    public function today(){
        // $bagian = Auth::user()->id;
        // dd($bagian);
        $list_surat = DB::table('surat')
        ->whereDate('created_at', Carbon::today())
        ->whereNotNull('surat.created_by')
        ->whereNull('surat.deleted_at')
        ->orderByDesc('surat.created_at')
        // ->where('surat.judul_surat', 'like', '%'. $pencarian .'%')
        ->get();
        // dd($list_surat);
        return view('monitoring.today', compact('list_surat'));
    }

    public function arsip()
    {
        $user_id = Auth::user()->id;
        $list_penerima = DB::table('users')
        ->leftJoin('user_akses', 'users.id', '=', 'user_akses.user_id')
        ->leftJoin('hakakses', 'hakakses.hakakses_id', '=', 'user_akses.hakakses_id')
        ->leftJoin('pegawai', 'pegawai.pegawai_id', '=', 'users.pegawai_id')
        ->leftJoin('bagian', 'bagian.bagian_id', '=', 'pegawai.bagian_id')
        // ->whereIn('hakakses.hakakses_id', $pecah_array)
        ->whereNotNull('user_akses.hakakses_id')
        ->get();
        // dd($list_penerima);
        return view('monitoring.arsip', compact('list_penerima'));
    }   
    public function arsip_detail($bagian){
        // $bagian = Auth::user()->id;
        // dd($bagian);
        $list_surat = DB::table('surat')
        ->where(['surat.bagian' => $bagian])
        ->where(['surat.status' => "arsip"])
        ->whereNotNull('surat.created_by')
        ->whereNull('surat.deleted_at')
        ->orderByDesc('surat.created_at')
        // ->where('surat.judul_surat', 'like', '%'. $pencarian .'%')
        ->get();
        // dd($list_surat);
        return view('message.sent', compact('list_surat'));
    }

    public function store(Request $request){
        $request->validate([
            'nama_menu' => ['required', 'string'],
        ]);
        $parent_id = 0;
        if(isset($request->parent_id)){
            $parent_id = $request->parent_id;
        }
        $data = [
            'nama_menu' => $request->nama_menu,
            'icon_menu' => $request->icon_menu,
            'url_menu' => $request->url_menu,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'parent_id' => $parent_id,
        ];

        DB::table('menu')->insert($data);

        return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan!']);
    }

    public function edit($id)
    {
        // $id = Crypt::decrypt($id);
        // dd($data);
        $text = "Data tidak ditemukan";
        if($data = DB::table('menu')->where(['menu_id' => $id])->get()){

            $text = '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-2 col-form-label">Nama menu</label>'.
                    '<div class="col-sm-10">'.
                    '<input type="text" class="form-control" id="nama_menu" name="nama_menu" value="'.$data[0]->nama_menu.'" required>'.
                    '</div>'.
                '</div>'.
                '<input type="hidden" class="form-control" id="menu_id" name="menu_id" value="'.Crypt::encrypt($data[0]->menu_id) .'" required>'.
                '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-2 col-form-label">Icon</label>'.
                    '<div class="col-sm-10">'.
                    '<input type="text" class="form-control" id="icon_menu" name="icon_menu" value="'.$data[0]->icon_menu.'">'.
                    '</div>'.
                '</div>'.
                '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-2 col-form-label">Url</label>'.
                    '<div class="col-sm-10">'.
                    '<input type="text" class="form-control" id="url_menu" name="url_menu" value="'.$data[0]->url_menu.'">'.
                    '</div>'.
                '</div>';
        }
        return $text;
        // return view('admin.menu.edit');
    }

    public function update(Request $request){
        $request->validate([
            'nama_menu' => ['required', 'string'],
        ]);
        $data = [
            'nama_menu' => $request->nama_menu,
            'icon_menu' => $request->icon_menu,
            'url_menu' => $request->url_menu,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ];
        $menu_id = Crypt::decrypt($request->menu_id);
        DB::table('menu')->where(['menu_id' => $menu_id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Ubah!']);
    }

    public function delete($id){
        $id = Crypt::decrypt($id);
        // if($data = DB::select("SELECT * FROM tbl_menu WHERE menu_id='$id'")){
        //     DB::select("DELETE FROM tbl_menu WHERE menu_id='$id'");
        // }
        $data = [
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now(),
        ];
        DB::table('menu')->where(['menu_id' => $id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus!']);
    }
    
}
