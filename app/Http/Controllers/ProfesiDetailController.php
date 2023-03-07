<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ProfesiDetailController extends Controller
{
    public function index()
    {
        $data = DB::table('profesi_detail')
        ->leftJoin('profesi', 'profesi_detail.profesi_id', '=', 'profesi.profesi_id')
        ->whereNull('profesi_detail.deleted_at')->get();
        $profesi = DB::table('profesi')->whereNull('profesi.deleted_at')->get();
        return view('profesi_detail.index', compact('data','profesi'));
    }
    public function store(Request $request){
        $request->validate([
            'nama_profesi_detail' => ['required', 'string'],
        ]);
        $data = [
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'nama_profesi_detail' => $request->nama_profesi_detail,
            'profesi_id' => $request->profesi_id,
        ];
        DB::table('profesi_detail')->insert($data);

        return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan!']);
    }

    public function edit($id)
    {
        // $id = Crypt::decrypt($id);
        // dd($data);
        $text = "Data tidak ditemukan";
        $profesi = DB::table('profesi')->whereNull('profesi.deleted_at')->get();
        if($data = DB::select("SELECT * FROM profesi_detail WHERE profesi_detail_id='$id'")){

            $text = '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-12 col-form-label">Nama Profesi Detail</label>'.
                    '<div class="col-sm-12">'.
                    '<input type="text" class="form-control" id="nama_profesi_detail" name="nama_profesi_detail" value="'.$data[0]->nama_profesi_detail.'" required>'.
                    '</div>'.
                '</div>'.
                '<div class="mb-3 row">'.
                    '<label for="staticEmail" class="col-sm-12 col-form-label">Jenis Profesi</label>'.
                    '<div class="col-sm-12">'.
                        '<select name="profesi_id" id="profesi_id" class="form-control">';
                            foreach ($profesi as $item){
                                $retVal = ($item->profesi_id == $data[0]->profesi_id) ? "selected" : "" ;
                                $text .= '<option value="'.$item->profesi_id.'" '.$retVal.'>'. $item->nama_profesi .'</option>';
                            }
                            $text .='</select>'.
                    '</div>'.
                '</div>'.
                '<input type="hidden" class="form-control" id="profesi_detail_id" name="profesi_detail_id" value="'.Crypt::encrypt($data[0]->profesi_detail_id) .'" required>';
        }
        return $text;
        // return view('profesi_detail.edit');
    }

    public function update(Request $request){
        $request->validate([
            'nama_profesi_detail' => ['required', 'string'],
        ]);
        $data = [
            'updated_by' => Auth::user()->id,
            'updated_at' => now(),
            'nama_profesi_detail' => $request->nama_profesi_detail,
            'profesi_id' => $request->profesi_id,
        ];
        $profesi_detail_id = Crypt::decrypt($request->profesi_detail_id);
        DB::table('profesi_detail')->where(['profesi_detail_id' => $profesi_detail_id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Ubah!']);
    }
    public function delete($id){
        $id = Crypt::decrypt($id);
        $data = [
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now(),
        ];
        DB::table('profesi_detail')->where(['profesi_detail_id' => $id])->update($data);
        return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus!']);
    }
    
}
