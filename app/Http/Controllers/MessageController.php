<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MessageController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $listInbox = DB::table('surat')
        ->where(['surat.penerima_id' => $user_id])
        ->whereNull('surat.deleted_at')
        ->get();
        return view('message.index', compact('listInbox'));
    }

    public function tulis(){

        $user_id = Auth::user()->id;

        $user_akses = DB::table('users')
        ->leftJoin('user_akses', 'users.id', '=', 'user_akses.user_id')
        ->leftJoin('hakakses', 'hakakses.hakakses_id', '=', 'user_akses.hakakses_id')
        ->where(['users.id' => $user_id])
        ->first();
        $pecah_array = explode('|', $user_akses->akses_bagian);
        // $gabung_array = [];
        // foreach($pecah_array as $gabung){
        //     array_push($gabung_array,$gabung);
        // }
        // dd($pecah_array, $gabung_array);
        // $gabung_array = implode(',', $pecah_array);
        
        $list_penerima = DB::table('users')
        ->leftJoin('user_akses', 'users.id', '=', 'user_akses.user_id')
        ->leftJoin('hakakses', 'hakakses.hakakses_id', '=', 'user_akses.hakakses_id')
        ->whereIn('hakakses.hakakses_id', $pecah_array)
        ->get();
        // ->whereNotNull('user_akses.hakakses_id')
        // ->whereNull('users.deleted_at')

        $list_bagian = DB::table('users')->leftJoin('pegawai', 'users.pegawai_id', '=', 'pegawai.pegawai_id')->leftJoin('bagian', 'pegawai.bagian_id', '=', 'bagian.bagian_id')->where(['users.id' => $user_id])->first();
        // dd($list_bagian);
        return view('message.tulis',compact('list_penerima','list_bagian'));
    }
    public function edit($id){
        $user_id = Auth::user()->id;
        $list_penerima = DB::table('users')
        ->leftJoin('user_akses', 'users.id', '=', 'user_akses.user_id')
        ->leftJoin('hakakses', 'hakakses.hakakses_id', '=', 'user_akses.hakakses_id')
        ->whereNotNull('user_akses.hakakses_id')
        ->whereNull('users.deleted_at')
        ->get();
        $surat = DB::table('surat')
        ->where(['surat.surat_id' => $id])
        ->whereNull('surat.created_by')
        ->whereNull('surat.deleted_at')
        ->first();
        $list_bagian = DB::table('users')->leftJoin('pegawai', 'users.pegawai_id', '=', 'pegawai.pegawai_id')->leftJoin('bagian', 'pegawai.bagian_id', '=', 'bagian.bagian_id')->where(['users.id' => $user_id])->first();
        return view('message.edit',compact('list_penerima','surat','list_bagian'));
    }
    public function inbox(Request $request){
        // dd($request->pencarian);
        $pencarian = $request->pencarian;
        // dd($pencarian)
        $user_id = Auth::user()->id;
        $list_surat = DB::table('surat')
        ->where('surat.penerima_id', 'like', '%|'. $user_id .'|%')
        ->where('surat.judul_surat', 'like', '%'. $pencarian .'%')
        ->whereNull('surat.status')
        ->orderByDesc('surat.created_at')
        ->get();
        return view('message.inbox', compact('list_surat'));
    }
    public function cetak_barcode($id)
    {
        $id = Crypt::decrypt($id);
        $surat = DB::table('surat')
        ->leftJoin('users', 'users.id', '=', 'surat.user_id')
        ->leftJoin('pegawai', 'users.pegawai_id', '=', 'pegawai.pegawai_id')
        ->where(['surat.surat_id' => $id])
        ->first();
        // dd($surat);
        return view('message.cetak_barcode', compact('surat'));
    }   
    public function sent(Request $request){
        $user_id = Auth::user()->id;
        $pencarian = $request->pencarian;
        $list_surat = DB::table('surat')
        ->where(['surat.user_id' => $user_id])
        ->whereNotNull('surat.created_by')
        ->whereNull('surat.deleted_at')
        ->orderByDesc('surat.created_at')
        ->where('surat.judul_surat', 'like', '%'. $pencarian .'%')
        ->get();
        return view('message.sent', compact('list_surat'));
    }
    public function read($id){
        $list_penerima = DB::table('users')
        ->leftJoin('user_akses', 'users.id', '=', 'user_akses.user_id')
        ->leftJoin('hakakses', 'hakakses.hakakses_id', '=', 'user_akses.hakakses_id')
        ->whereNotNull('user_akses.hakakses_id')
        ->whereNull('users.deleted_at')
        ->get();
        $user_id = Auth::user()->id;
        $surat = DB::table('surat')
        ->leftJoin('users', 'users.id', '=', 'surat.user_id')
        ->leftJoin('pegawai', 'users.pegawai_id', '=', 'pegawai.pegawai_id')
        ->where(['surat.surat_id' => $id])
        // ->whereNull('surat.status')
        ->first();
        $cek_balasan = DB::table('surat_balasan')->where(['surat_balasan.surat_id' => $id])->where(['surat_balasan.user_id' => $user_id])->first();
        $surat_balasan = DB::table('surat_balasan')
        ->leftJoin('users', 'users.id', '=', 'surat_balasan.user_id')
        ->leftJoin('pegawai', 'users.pegawai_id', '=', 'pegawai.pegawai_id')
        ->select([
            'pegawai.nama_pegawai',
            'users.name',
            'surat_balasan.*',
        ])
        // ->leftJoin('users', 'users.id', '=', 'surat.user_id')
        ->where(['surat_balasan.surat_id' => $id])
        ->whereNull('surat_balasan.deleted_at')
        ->get();

        $list_bagian = DB::table('users')->leftJoin('pegawai', 'users.pegawai_id', '=', 'pegawai.pegawai_id')->leftJoin('bagian', 'pegawai.bagian_id', '=', 'bagian.bagian_id')->where(['users.id' => $user_id])->first();

        $lampiran = DB::table('file')
        ->where(['surat_id' => $id])
        ->get();
        
        $lampiran = DB::table('file')
        ->where(['surat_id' => $id])
        ->get();
        // dd($surat);
        return view('message.read', compact('surat','list_penerima','surat_balasan','cek_balasan','lampiran','list_bagian'));
    }
    public function draft(Request $request){
        $pencarian = $request->pencarian;
        $user_id = Auth::user()->id;
        $list_surat = DB::table('surat')
        ->where(['surat.user_id' => $user_id])
        ->whereNull('surat.created_by')
        ->whereNull('surat.deleted_at')
        ->orderByDesc('surat.created_at')
        ->where('surat.judul_surat', 'like', '%'. $pencarian .'%')
        ->get();
        return view('message.draft',compact('list_surat'));
    }
    public function arsipOpen(){
        $user_id = Auth::user()->id;
        $list_surat = DB::table('surat')
        ->where(['surat.user_id' => $user_id])
        ->where(['surat.status' => "arsip"])
        ->orderByDesc('surat.created_at')
        ->get();
        return view('message.arsip',compact('list_surat'));
    }
    public function batal($id){
        $id = Crypt::decrypt($id);
        $data = [
            'deleted_at' => now(),
            'deleted_by' => Auth::user()->id,
            'status' => 'batal',
            'change_status_id' => Auth::user()->id,
        ];
        // dd($data);
        DB::table('surat')->where(['surat.surat_id' => $id])->update($data);
        return Redirect::back()->with(['success' => 'Surat Berhasil di batalkan!']);
    }
    public function arsip($id){
        $id = Crypt::decrypt($id);
        $data = [
            'updated_at' => now(),
            'updated_by' => Auth::user()->id,
            'status' => 'arsip',
            'change_status_id' => Auth::user()->id,
        ];
        // dd($data);
        DB::table('surat')->where(['surat.surat_id' => $id])->update($data);
        return Redirect::back()->with(['success' => 'Surat Berhasil di arsip!']);
    }
    public function aktifkan($id){
        $id = Crypt::decrypt($id);
        $data = [
            'deleted_at' => null,
            'deleted_by' => null,
            'status' => null,
            'change_status_id' => null,
        ];
        // dd($data);
        DB::table('surat')->where(['surat.surat_id' => $id])->update($data);
        return Redirect::back()->with(['success' => 'Surat Berhasil di Aktifkan!']);
    }
    public function nomorotomatis($bagian){
        // $user_id = Auth::user()->id;
        $data = DB::select("SELECT max(no) as nourut FROM surat WHERE bagian='$bagian'");
        $notis = 1;
        if(isset($data[0]->nourut)){
            $notis = $data[0]->nourut + 1;
        }
        $notis .= '/MEMO/'.$bagian.'/'.date('m/Y');
        return $notis;
    }
    public function nomordisposisi($bagian){
        // $user_id = Auth::user()->id;
        $data = DB::select("SELECT max(no) as nourut FROM surat_balasan WHERE disposisi_bagian='$bagian'");
        $notis = 1;
        if(isset($data[0]->nourut)){
            $notis = $data[0]->nourut + 1;
        }
        $notis .= '/DIS/'.$bagian.'/'.date('m/Y');
        return $notis;
    }
    public function reply(Request $request){
        // dd($request);
        $penerima = $request->penerima_id;
        // foreach($request->penerima_id as $penerima_id){
        //     $penerima .= $penerima_id.'|';
        // }
        $error = "";
        $nama_file_surat = [];
        if($request->hasFile('file')){
            $semua_file = "";
            foreach($request->file as $file){
                // dd($file->getClientMimeType());
                if(in_array($file->getClientMimeType(),['image/jpg','image/jpeg','image/png','image/svg','application/zip','application/xls','application/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/pdf'])){
                    $file_name = round(microtime(true) * 1000).'-'.str_replace(' ','-',$file->getClientOriginalName());
                    // $name = Auth::user()->pegawai_id;
                    $file->move(public_path('document/lampiran/'), $file_name);
                    array_push($nama_file_surat, $file_name);
                }else{
                    $error .= $file->getClientOriginalName()."File anda tidak dapat kami simpan cek kembali extensi dan besar filenya"."<br>";
                }
            }
            // dd($nama_file_surat);
            if($error !== ""){
                return Redirect::back()->with(['error' => $error]);
            }
            
        }
        $no_surat = app('App\Http\Controllers\MessageController')->nomordisposisi($request->bagian);
        $no = explode('/', $no_surat);
        $surat_id = Crypt::decrypt($request->surat_id);
        $penerima_id = Crypt::decrypt($request->penerima_sebelumnya).$penerima.'|';
        DB::table('surat')->where(['surat.surat_id' => $surat_id])->update([
            'penerima_id' => $penerima_id,
        ]);
        $data = [
            'created_by' => Auth::user()->id,
            'created_at' => now(),
            'user_id' => Auth::user()->id,
            'isi_balasan' => $request->pesan,
            'surat_id' => $surat_id,
            'penerima_balasan_id' => $penerima,
            'no' => $no[0],
            'disposisi_bagian' => $request->bagian,
            'nomor_disposisi' => $no_surat,
        ];
        $store_surat = DB::table('surat_balasan')->insertGetId($data);
        // DB::table('surat_balasan')->insert($data);
        foreach($nama_file_surat as $file_surat){
            $data1 = [
                'nama_file_balasan' => $file_surat,
                'surat_balasan_id' => $store_surat,
            ];
            DB::table('file_balasan')->insert($data1);
        }
        return Redirect::back()->with(['success' => 'Surat Berhasil di kirim!']);
    }
    public function store(Request $request){
        $request->validate([
            'penerima_id' => ['required'],
            'judul' => ['required'],
            'pesan' => 'required',
        ]);
        // dd($request->bagian);
        $no_surat = app('App\Http\Controllers\MessageController')->nomorotomatis($request->bagian);
        $no = explode('/', $no_surat);
        // dd($no[0]);
        if(isset($request->simpan)){
            $error = "";
            $nama_file_surat = [];
            if($request->hasFile('file')){
                $semua_file = "";
                foreach($request->file as $file){
                    // dd($file->getClientMimeType());
                    if(in_array($file->getClientMimeType(),['image/jpg','image/jpeg','image/png','image/svg','application/zip','application/xls','application/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/pdf'])){
                        $file_name = round(microtime(true) * 1000).'-'.str_replace(' ','-',$file->getClientOriginalName());
                        // $name = Auth::user()->pegawai_id;
                        $file->move(public_path('document/lampiran/'), $file_name);
                        array_push($nama_file_surat, $file_name);
                    }else{
                        $error .= $file->getClientOriginalName()."File anda tidak dapat kami simpan cek kembali extensi dan besar filenya"."<br>";
                    }
                }
                // dd($nama_file_surat);
                if($error !== ""){
                    return Redirect::back()->with(['error' => $error]);
                }
                
            }
            $penerima = "|".$request->penerima_id."|";
            // foreach($request->penerima_id as $penerima_id){
                //     $penerima .= $penerima_id.'|';
                // }
            $data = [
                'created_by' => Auth::user()->id,
                'user_id' => Auth::user()->id,
                'created_at' => now(),
                'penerima_id' => $penerima,
                'judul_surat' => $request->judul,
                'isi_surat' => $request->pesan,
                'bagian' => $request->bagian,
                'no_surat' => $no_surat,
                'no' => $no[0],
            ];
            $store_surat = DB::table('surat')->insertGetId($data);
            
            foreach($nama_file_surat as $file_surat){
                $data1 = [
                    'nama_file' => $file_surat,
                    'surat_id' => $store_surat,
                ];
                DB::table('file')->insert($data1);
            }
            return Redirect::back()->with(['success' => 'Surat Berhasil di kirim!']);
        }else{
            // $penerima = "|";
            // foreach($request->penerima_id as $penerima_id){
                //     $penerima .= $penerima_id.'|';
                // }
            $penerima = "|".$request->penerima_id."|";
            $data = [
                'created_at' => now(),
                'user_id' => Auth::user()->id,
                'penerima_id' => $penerima,
                'judul_surat' => $request->judul,
                'isi_surat' => $request->pesan,
            ];
            // dd($data);
            DB::table('surat')->insert($data);
            return Redirect::back()->with(['success' => 'Surat Berhasil di simpan!']);
        }
        // return view('message.trash');
    }
    public function update(Request $request){
        $request->validate([
            'penerima_id' => ['required'],
            'judul' => ['required'],
            'pesan' => 'required'
        ]);
        // dd($request);
        $id = $request->surat_id;
        $no_surat = app('App\Http\Controllers\MessageController')->nomorotomatis($request->bagian);
        $no = explode('/', $no_surat);
        if(isset($request->simpan)){
            // $penerima = "|";
            // foreach($request->penerima_id as $penerima_id){
            //     $penerima .= $penerima_id.'|';
            // }
            $error = "";
            $nama_file_surat = [];
            if($request->hasFile('file')){
                $semua_file = "";
                foreach($request->file as $file){
                    // dd($file->getClientMimeType());
                    if(in_array($file->getClientMimeType(),['image/jpg','image/jpeg','image/png','image/svg','application/zip','application/xls','application/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf'])){
                        $file_name = round(microtime(true) * 1000).'-'.str_replace(' ','-',$file->getClientOriginalName());
                        // $name = Auth::user()->pegawai_id;
                        $file->move(public_path('document/lampiran/'), $file_name);
                        array_push($nama_file_surat, $file_name);
                    }else{
                        $error .= $file->getClientOriginalName()."File anda tidak dapat kami simpan cek kembali extensi dan besar filenya"."<br>";
                    }
                }
                // dd($nama_file_surat);
                if($error !== ""){
                    return Redirect::back()->with(['error' => $error]);
                }
                
            }
            $penerima = "|".$request->penerima_id."|";
            $data = [
                'created_by' => Auth::user()->id,
                'user_id' => Auth::user()->id,
                'created_at' => now(),
                'penerima_id' => $penerima,
                'judul_surat' => $request->judul,
                'isi_surat' => $request->pesan,
                'bagian' => $request->bagian,
                'no_surat' => $no_surat,
                'no' => $no[0],
            ];
            DB::table('surat')->where(['surat.surat_id' => $id])->update($data);
            
            foreach($nama_file_surat as $file_surat){
                $data1 = [
                    'nama_file' => $file_surat,
                    'surat_id' => $id,
                ];
                DB::table('file')->insert($data1);
            }
            
            return Redirect::back()->with(['success' => 'Surat Berhasil di kirim!']);
        }else{
            // $penerima = "|";
            // foreach($request->penerima_id as $penerima_id){
            //     $penerima .= $penerima_id.'|';
            // }
            $penerima = "|".$request->penerima_id."|";
            $data = [
                'created_at' => now(),
                'user_id' => Auth::user()->id,
                'penerima_id' => $penerima,
                'judul_surat' => $request->judul,
                'isi_surat' => $request->pesan,
                // 'bagian' => $request->profesi,
                // 'no_surat' => $no_surat,
                // 'no' => $no,
            ];
            // dd($data);
            DB::table('surat')->where(['surat.surat_id' => $id])->update($data);
            return Redirect::back()->with(['success' => 'Surat Berhasil di simpan!']);
        }
        // return view('message.trash');
    }
}
