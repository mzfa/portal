<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RS Umum Pekerja</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="container">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-12">
                        <h1 class="m-0 text-center">RIWAYAT SURAT RS UMUM PEKERJA </h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Riwayat Surat</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="mailbox-read-info">
                            @if ($surat->status == 'batal')
                                <div class="alert alert-danger">
                                    <h5><i class="icon fas fa-ban"></i> Mohon Maaf Surat Sudah Di Batalkan!</h5>
                                </div>
                            @elseif($surat->status == 'acc')
                                <div class="alert alert-success">
                                    <h5><i class="icon fas fa-check"></i> Surat Sudah Di Arsipkan!</h5>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-12 text-center">


                                </div>
                            </div>
                            <table>
                                <tr>
                                    <td width="30%"><img src="{{ asset(env('APP_LOGO')) }}" style="width: 90%"
                                            alt=""></td>
                                    <th align="left">
                                        <h2>Nota Dinas</h2>
                                        <h5>{{ $surat->no_surat }}</h5>
                                    </th>
                                    <th>
                                        <center>
                                            {!! QrCode::size(150)->generate(url('/cek_surat_dong/').base64_encode($surat->surat_id) ) !!}
                                            <br><em>QR CODE</em>
                                        </center>
                                    </th>
                                </tr>
                            </table>
                            <hr>
                            <table>
                                <tr>
                                    <th>Kepada Yth</th>
                                    <td>&nbsp;</td>
                                    <td>:</td>
                                    <td>
                                        @php
                                            $penerima_id = explode('|', $surat->penerima_id);
                                            // dump($penerima_id);
                                            for ($i = 0; $i < count($penerima_id); $i++) {
                                                $id = $penerima_id[$i];
                                                if ($penerima_id[$i] > 0) {
                                                    $user = DB::table('users')
                                                        ->leftJoin('pegawai', 'users.pegawai_id', '=', 'pegawai.pegawai_id')
                                                        ->where(['users.id' => $id])
                                                        ->first();
                                                    echo $user->nama_pegawai . ' , ';
                                                }
                                            }
                                        @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dari</th>
                                    <td>&nbsp;</td>
                                    <td>:</td>
                                    <td>{{ $surat->nama_pegawai }}</td>
                                </tr>
                                <tr>
                                    <th>Perihal</th>
                                    <td>&nbsp;</td>
                                    <td>:</td>
                                    <td>{{ $surat->judul_surat }}</td>
                                </tr>
                                <tr>
                                    <th>Lampiran</th>
                                    <td>&nbsp;</td>
                                    <td>:</td>
                                    <td>
                                        @if (isset($lampiran[0]))
                                            Terlampir
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal</th>
                                    <td>&nbsp;</td>
                                    <td>:</td>
                                    <td>{{ date('d-M-Y H:i:s', strtotime($surat->created_at)) }}</td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            {!! $surat->isi_surat !!}
                        </div>
                        <div class="card-footer bg-white">
                            {{-- <h2>Lampiran</h2> --}}
                            <div class="row">
                                @foreach ($lampiran as $item)
                                    <div class="col-md-3 col-4">
                                        <span class="mailbox-attachment-icon"><i class="far fa-file"></i></span>

                                        <div class="mailbox-attachment-info">
                                            <a href="{{ asset('document/lampiran/' . $item->nama_file) }}"
                                                target="_blank" class="mailbox-attachment-name"><i
                                                    class="fas fa-paperclip"></i> {{ $item->nama_file }}</a>
                                            {{-- <span class="mailbox-attachment-size clearfix mt-1">
                                            <span>1,245 KB</span>
                                            <a href="#" class="btn btn-default btn-sm float-right"><i
                                                    class="fas fa-eye"></i></a>
                                        </span> --}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @php
                            $datapenerima = explode('|', $surat->penerima_id);
                            $hitung = count($datapenerima) - 2;
                            // dump($datapenerima[$hitung]);
                        @endphp
                        @foreach ($surat_balasan as $balas)
                            <hr>
                            <div class="mailbox-read-message text-right">
                                <u>
                                    <em class="bg-warning">Dijawab : {{ $balas->nama_pegawai }}</em><br>
                                    <em>No Disposisi : {{ $balas->nomor_disposisi }}</em>
                                </u><br>
                                {{-- <u><h5>Diteruskan : {{ $balas->nama_pegawai }}</h5></u> --}}
                                <em>{{ \Carbon\Carbon::parse($balas->created_at)->diffForHumans() }}</em><br><br>
                                {!! $balas->isi_balasan !!}
                                @php
                                    $id_balasan = $balas->surat_balasan_id;
                                    $lampiran_balasan = DB::table('file_balasan')
                                        ->where(['surat_balasan_id' => $id_balasan])
                                        ->get();
                                @endphp
                                <div class="row text-right">
                                    @foreach ($lampiran_balasan as $item)
                                        <div class="col-md-3 col-4">
                                            <span class="mailbox-attachment-icon"><i class="far fa-file"></i></span>

                                            <div class="mailbox-attachment-info">
                                                <a href="{{ asset('document/lampiran/' . $item->nama_file_balasan) }}"
                                                    target="_blank" class="mailbox-attachment-name"><i
                                                        class="fas fa-paperclip"></i>
                                                    {{ $item->nama_file_balasan }}</a>
                                                {{-- <span class="mailbox-attachment-size clearfix mt-1">
                                            <span>1,245 KB</span>
                                            <a href="#" class="btn btn-default btn-sm float-right"><i
                                                class="fas fa-eye"></i></a>
                                            </span> --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                        <!-- /.mailbox-read-message -->
                    </div>
                    <!-- /.card-body -->

                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('assets/plugins/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('assets/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
</body>

</html>
