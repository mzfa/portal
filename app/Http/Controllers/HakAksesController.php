<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HakAksesController extends Controller
{
    public function index()
    {
        $data = DB::table('hakakses')->whereNull('deleted_at')->get();
        $list_variabel = DB::table('variabel')->whereNull('variabel.deleted_at')->get();
        return view('hakakses.index', compact('data','list_variabel'));
    }
    public function modul_akses($id)
    {
        $id = Crypt::decrypt($id);
        $data_hakakses = DB::select("SELECT * FROM hakakses WHERE hakakses_id='$id'");
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
        return view('hakakses.modul_akses', compact('data_hakakses','menu'));
    }
    public function modul_akses_store(Request $request){
        $menu_id = '';
        $hakakses_id = $request->hakakses_id;
        foreach($request->menu_id as $item){
            $menu_id .= $item."|";
        }
        // dd($menu_id);
        DB::table('hakakses')->where('hakakses_id', $hakakses_id)->update(['menu_id' => $menu_id]);
        return redirect('hakakses')->with(['success' => 'Data Berhasil Di Simpan!']);
    }

    public function store(Request $request){
        $request->validate([
            'nama_hakakses' => ['required', 'string'],
        ]);
        $akses_bagian = "|";
        foreach($request->akses_bagian as $akses_bagian_id){
            $akses_bagian .= $akses_bagian_id.'|';
        }
        $data = [
            'nama_hakakses' => $request->nama_hakakses,
            'akses_bagian' => $akses_bagian,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
        ];
        DB::table('hakakses')->insert($data);

        return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan!']);
    }

    public function edit($id)
    {
        $text = "Data tidak ditemukan";
        $hakakses = DB::table('hakakses')->get();
        $list_variabel = DB::table('variabel')->whereNull('variabel.deleted_at')->get();
        $data = DB::table('hakakses')->where(['hakakses_id' => $id])->first();
        return view('modal_content.hak_akses', compact('data','hakakses','list_variabel'));
    }

    public function update(Request $request){
        $request->validate([
            'nama_hakakses' => ['required', 'string'],
        ]);
        $akses_bagian = "|";
        foreach($request->akses_bagian as $akses_bagian_id){
            $akses_bagian .= $akses_bagian_id.'|';
        }
        $hakakses_id = Crypt::decrypt($request->hakakses_id);
        $data = [
            'nama_hakakses' => $request->nama_hakakses,
            'akses_bagian' => $akses_bagian,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ];
        DB::table('hakakses')->where(['hakakses_id' => $hakakses_id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Update!']);
    }

    public function delete($id){
        $id = Crypt::decrypt($id);
        // if($data = DB::select("SELECT * FROM hakakses WHERE hakakses_id='$id'")){
        //     DB::select("DELETE FROM hakakses WHERE hakakses_id='$id'");
        // }
        $data = [
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now(),
        ];
        DB::table('hakakses')->where(['hakakses_id' => $id])->update($data);
        
        return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus!']);
    }
    
}
