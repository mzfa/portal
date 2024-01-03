<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function cek_surat_dong($id)
    {
        $id = base64_decode($id);
        $list_penerima = DB::table('users')
        ->leftJoin('user_akses', 'users.id', '=', 'user_akses.user_id')
        ->leftJoin('hakakses', 'hakakses.hakakses_id', '=', 'user_akses.hakakses_id')
        ->whereNotNull('user_akses.hakakses_id')
        ->whereNull('users.deleted_at')
        ->get();
        $surat = DB::table('surat')
        ->leftJoin('users', 'users.id', '=', 'surat.user_id')
        ->leftJoin('pegawai', 'users.pegawai_id', '=', 'pegawai.pegawai_id')
        ->where(['surat.surat_id' => $id])
        // ->whereNull('surat.status')
        ->first();
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

        $lampiran = DB::table('file')
        ->where(['surat_id' => $id])
        ->get();
        
        $lampiran = DB::table('file')
        ->where(['surat_id' => $id])
        ->get();
        // dd($surat);
        return view('cek_surat_dong', compact('surat','list_penerima','surat_balasan','lampiran'));
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // if($data =  DB::select("SELECT * FROM users WHERE user_name='$request->username' AND user_password='$request->password'")){

        //     if(Auth::user()->level_user == "Admin"){
        //         return redirect()->intended('home/admin');
        //     }else{
        //         return redirect()->intended('home');
        //     }
        // }
        $check_username = DB::table('users')->where('username', $request->username)->first();
        if ($check_username) {
            $check_password = DB::table('users')->where('username', $request->username)->where('password', $request->password)->first();
            if ($check_password) {
                // Auth::attempt($credentials);
                // dd($check_password);
                $menu = [];
                $id = $check_password->id;
                $user_akses = DB::table('user_akses')->where(['user_id' => $id])->first();
                // dd($user_akses);
                if(isset($user_akses->hakakses_id)){

                    $data_hakakses = DB::table('hakakses')->where(['hakakses_id' => $user_akses->hakakses_id])->get();
                    if(isset($data_hakakses[0]->menu_id)){
                        $menu_akses = explode ("|", $data_hakakses[0]->menu_id);
                        // dd($menu_akses);
                        foreach($menu_akses as $item)
                        {
                            $data = DB::table('menu')->where(['menu_id' => $item])->whereNull('deleted_at')->get();
                            // dump($data);
                            if(isset($data[0])){
                                if($data[0]->parent_id == 0){
                                    $menu[$data[0]->menu_id] = [
                                        'menu_id' => $data[0]->menu_id,
                                        'nama_menu' => $data[0]->nama_menu,
                                        'url_menu' => $data[0]->url_menu,
                                        'icon_menu' => $data[0]->icon_menu,
                                        'parent_id' => $data[0]->parent_id,
                                        'submenu' => [],
                                    ];
                                }
                                else{
                                    $menu[$data[0]->parent_id]['submenu'][] = [
                                        'menu_id' => $data[0]->menu_id,
                                        'nama_menu' => $data[0]->nama_menu,
                                        'url_menu' => $data[0]->url_menu,
                                        'icon_menu' => $data[0]->icon_menu,
                                        'parent_id' => $data[0]->parent_id,
                                    ];
                                }
                            }
                        }
                    }
                }
                
                // dd($data_hakakses);
                $user_data = DB::table('pegawai_detail')->where(['pegawai_id' => $check_password->pegawai_id])->get();
                $image = "";
                if(isset($user_data[0]->foto_profile))
                    $image = asset('images/profile/'.$user_data[0]->foto_profile);
                session(['menu' => $menu]);
                session(['foto_profile' => $image]);
                Auth::loginUsingId($check_password->id, true);
                return redirect()->intended('home');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
