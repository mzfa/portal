<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class VariabelController extends Controller
{
    public function index()
    {
        $variabel = [];
        $data = DB::table('variabel')->where(['parent_id' => 0])->whereNull('deleted_at')->get();
        // dd($data);
        foreach($data as $key => $item)
        {
            array_push($variabel, [
                'variabel_id' => $item->variabel_id,
                'nama_variabel' => $item->nama_variabel,
                'jenis_variabel' => $item->jenis_variabel,
                'harian' => $item->harian,
                'bulanan' => $item->bulanan,
                'parent_id' => $item->parent_id,
                'subvariabel' => []
            ]);
            $variabel_id = $item->variabel_id;
            $subvariabel = DB::table('variabel')->where(['parent_id' => $variabel_id])->whereNull('deleted_at')->get();
            // dd($subvariabel);
            foreach($subvariabel as $sub)
            {
                array_push($variabel[$key]["subvariabel"], [
                    'variabel_id' => $sub->variabel_id,
                    'nama_variabel' => $sub->nama_variabel,
                    'jenis_variabel' => $sub->jenis_variabel,
                    'harian' => $sub->harian,
                    'bulanan' => $sub->bulanan,
                ]);
                // dd($variabel['subvariabel']);
            }
        } 
        // dd($variabel);
        return view('variabel.index', compact('variabel'));
    }

    public function store(Request $request){
        $request->validate([
            'nama_variabel' => ['required', 'string'],
        ]);
        $parent_id = 0;
        $jenis_variabel = null;
        if(isset($request->parent_id)){
            $parent_id = $request->parent_id;
        }
        if(isset($request->jenis_variabel)){
            $jenis_variabel = 1;
        }
        $data = [
            'nama_variabel' => $request->nama_variabel,
            'jenis_variabel' => $jenis_variabel,
            'harian' => $request->harian,
            'bulanan' => $request->bulanan,
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'parent_id' => $parent_id,
        ];

        DB::table('variabel')->insert($data);

        return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan!']);
    }

    public function edit($id)
    {
        // $id = Crypt::decrypt($id);
        // dd($data);
        $text = "Data tidak ditemukan";
        if($data = DB::table('variabel')->where(['variabel_id' => $id])->first()){
            return view('modal_content.edit_variabel', compact('data'));
        }
        return $text;
    }

    public function update(Request $request){
        $request->validate([
            'nama_variabel' => ['required', 'string'],
        ]);

        $jenis_variabel = null;
        if(isset($request->jenis_variabel)){
            $jenis_variabel = 1;
        }else{
            
        }
        $data = [
            'nama_variabel' => $request->nama_variabel,
            'jenis_variabel' => $jenis_variabel,
            'harian' => $request->harian,
            'bulanan' => $request->bulanan,
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
        ];
        $variabel_id = Crypt::decrypt($request->variabel_id);
        DB::table('variabel')->where(['variabel_id' => $variabel_id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Ubah!']);
    }

    public function delete($id){
        $id = Crypt::decrypt($id);
        // if($data = DB::select("SELECT * FROM tbl_variabel WHERE variabel_id='$id'")){
        //     DB::select("DELETE FROM tbl_variabel WHERE variabel_id='$id'");
        // }
        $data = [
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now(),
        ];
        DB::table('variabel')->where(['variabel_id' => $id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus!']);
    }
    
}
