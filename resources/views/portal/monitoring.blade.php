@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                            <li class="pt-2 px-3">
                                <h3 class="card-title">Bulan</h3>
                            </li>
                            @for ($i = 1; $i <= 12; $i++)
                                @php
                                    $month = date('F', mktime(0,0,0,$i));
                                @endphp
                                <li class="nav-item active">
                                    <a class="nav-link" id="custom-tabs-two-{{ $i }}-tab" data-toggle="pill"
                                        href="#custom-tabs-two-{{ $i }}" role="tab" aria-controls="custom-tabs-two-{{ $i }}">{{ $month }}</a>
                                </li>
                            @endfor
                            {{-- <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-profile-tab" data-toggle="pill"
                                    href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile"
                                    aria-selected="false">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-messages-tab" data-toggle="pill"
                                    href="#custom-tabs-two-messages" role="tab" aria-controls="custom-tabs-two-messages"
                                    aria-selected="false">Messages</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-settings-tab" data-toggle="pill"
                                    href="#custom-tabs-two-settings" role="tab" aria-controls="custom-tabs-two-settings"
                                    aria-selected="false">Settings</a>
                            </li> --}}
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-two-tabContent">
                            @for ($m = 1; $m <= 12; $m++)
                                @php
                                    $month = date('F', mktime(0,0,0,$m));
                                @endphp
                                <div class="tab-pane fade" id="custom-tabs-two-{{ $m }}" role="tabpanel" aria-labelledby="custom-tabs-two-{{ $m }}-tab">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Portal Bulan ({{ $month }}) &nbsp;
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-lg">
                                                    Tambah
                                                </button>
                                            </h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover text-nowrap" style="width: 100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Variabel</th>
                                                            <th>Target Bulanan</th>
                                                            <th>Target Harian</th>
                                                            <th class="bg-warning">Total</th>
                                                            <th class="bg-warning">Capaian</th>
                                                            @php
                                                                $bulan = $m;
                                                                $tahun = date('Y');
                                                                // dd($bulan);
                                                                $jumHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                                                            @endphp
                                                            @for ($i = 1; $i <= $jumHari; $i++)
                                                                <th>{{ $i }}</th>
                                                            @endfor
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no = 1;
                                                            $total = 0;
                                                            
                                                        @endphp
                                                        @foreach ($variabel as $item)
                                                            <tr>
                                                                <td>
                                                                    <h5>{{ strtoupper($item['nama_variabel']) }}</h5>
                                                                </td>
                                                                <td id="{{ $item['variabel_id'] . '-bulanan' }}">{{ $item['bulanan'] }}</td>
                                                                <td id="{{ $item['variabel_id'] . '-harian' }}">{{ $item['harian'] }}</td>
                                                                <td class="bg-warning" id="{{ $item['variabel_id'] . '-total'.$m }}"></td>
                                                                <td class="bg-warning" id="{{ $item['variabel_id'] . '-capaian'.$m }}"></td>
                                                                @for ($i = 1; $i <= $jumHari; $i++)
                                                                    @php
                                                                        $variabel_id = $item['variabel_id'];
                                                                        $tanggal = $i;
                                                                        $transaksi = DB::table('transaksi_var')
                                                                            ->where(['variabel_id' => $variabel_id, 'tanggal' => $tanggal, 'bulan' => $bulan, 'tahun' => $tahun])
                                                                            ->whereNull('deleted_at')
                                                                            ->first();
                                                                        if (isset($transaksi)) {
                                                                            $total += floatval($transaksi->value);
                                                                        }
                                                                    @endphp
                                                                    <td id="{{ $item['variabel_id'] . '-' . $i. '-' . $m }}">
                                                                        @isset($transaksi)
                                                                            {{ $transaksi->value }}
                                                                        @endisset
                                                                    </td>
                                                                @endfor
                                        
                                                                @if ($item['jenis_variabel'] == '1')
                                                                    <script>
                                                                        document.getElementById("{{ $item['variabel_id'] . '-total'.$m }}").innerHTML = "{{ round($total / $jumHari, 1) }} ";
                                                                        document.getElementById("{{ $item['variabel_id'] . '-capaian'.$m }}").innerHTML =
                                                                            "{{ round(($total / $jumHari / floatval($item['bulanan'])) * 100) }} %";
                                                                    </script>
                                                                @else
                                                                    <script>
                                                                        document.getElementById("{{ $item['variabel_id'] . '-total'.$m }}").innerHTML = "{{ round($total, 1) }}";
                                                                        document.getElementById("{{ $item['variabel_id'] . '-capaian'.$m }}").innerHTML =
                                                                            "{{ round(($total / floatval($item['bulanan'])) * 100) }} %";
                                                                    </script>
                                                                @endif
                                        
                                                                @php $total = 0; @endphp
                                                            </tr>
                                                            @php
                                                                $total_harian = 0;
                                                            @endphp
                                                            @foreach ($item['subvariabel'] as $subvariabel)
                                                                <tr>
                                                                    <td>
                                                                        <p class="text-danger">
                                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($subvariabel['nama_variabel']) }}
                                                                        </p>
                                                                    </td>
                                                                    <td>{{ $subvariabel['bulanan'] }}</td>
                                                                    <td>{{ $subvariabel['harian'] }}</td>
                                                                    <td class="bg-warning" id="{{ $subvariabel['variabel_id'] . '-totalsub'.$m }}">
                                                                    </td>
                                                                    <td class="bg-warning"
                                                                        id="{{ $subvariabel['variabel_id'] . '-capaiansub'.$m }}"></td>
                                                                    @for ($i = 1; $i <= $jumHari; $i++)
                                                                        @php
                                                                            $variabel_id = $subvariabel['variabel_id'];
                                                                            $tanggal = $i;
                                                                            if($i < 10){
                                                                                $tanggal = '0' . $i;
                                                                            }
                                                                            
                                                                            $transaksi = DB::table('transaksi_var')
                                                                                ->where(['variabel_id' => $variabel_id, 'tanggal' => $tanggal, 'bulan' => $bulan, 'tahun' => $tahun])
                                                                                ->whereNull('deleted_at')
                                                                                ->first();
                                                                            if (isset($transaksi)) {
                                                                                $total += floatval($transaksi->value);
                                                                            }
                                                                        @endphp
                                                                        <td>
                                                                            @isset($transaksi)
                                                                                {{ $transaksi->value }}
                                                                                <script>
                                                                                    var data = document.getElementById("{{ $item['variabel_id'] . '-' . $i. '-' . $m }}").innerHTML;
                                                                                    document.getElementById("{{ $item['variabel_id'] . '-' . $i. '-' . $m }}").innerHTML = Number(data) + Number(
                                                                                        '{{ $transaksi->value }}');
                                                                                    // console.log(data);
                                                                                </script>
                                                                            @endisset
                                                                        </td>
                                                                    @endfor
                                                                    @if ($item['jenis_variabel'] == '1')
                                                                        <script>
                                                                            document.getElementById("{{ $subvariabel['variabel_id'] . '-totalsub'.$m }}").innerHTML =
                                                                                "{{ round($total / $jumHari, 1) }} ";
                                                                            document.getElementById("{{ $subvariabel['variabel_id'] . '-capaiansub'.$m }}").innerHTML =
                                                                                "{{ round(($total / $jumHari / floatval($subvariabel['bulanan'])) * 100) }} %";
                                                                        </script>
                                                                    @else
                                                                        <script>
                                                                            document.getElementById("{{ $subvariabel['variabel_id'] . '-totalsub'.$m }}").innerHTML = "{{ round($total, 1) }}";
                                                                            document.getElementById("{{ $subvariabel['variabel_id'] . '-capaiansub'.$m }}").innerHTML =
                                                                                "{{ round(($total / floatval($subvariabel['bulanan'])) * 100) }} %";
                                                                        </script>
                                                                    @endif
                                                                    @php
                                                                        $total_harian += $total;
                                                                    @endphp
                                                                    @php $total = 0; @endphp
                                                                </tr>
                                                            @endforeach
                                                            <script>
                                                                var data = document.getElementById("{{ $item['variabel_id'] . '-total'.$m }}").innerHTML;
                                                                if ("{{ $total_harian }}" != 0) {
                                                                    document.getElementById("{{ $item['variabel_id'] . '-total'.$m }}").innerHTML = Number('{{ $total_harian }}');
                                                                }
                                                                var capaian = document.getElementById("{{ $item['variabel_id'] . '-capaian'.$m }}").innerHTML;
                                                                var total_capaian = (Number('{{ $total_harian }}') / Number("{{ $item['bulanan'] }}")) * 100;
                                                                if ("{{ $total_harian }}" != 0) {
                                                                    document.getElementById("{{ $item['variabel_id'] . '-capaian'.$m }}").innerHTML = total_capaian.toFixed() + "%";
                                                                }
                                                                // document.getElementById("{{ $item['variabel_id'] . '-total' }}").innerHTML = (Number(data) + Number('{{ $total_harian }}'))/Number("{{ $item['bulanan'] }}");
                                                                // console.log(data);
                                                            </script>
                                                            @php
                                                                $total_harian = 0;
                                                            @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                
            </div>
        </div>

        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg">
                <form action="{{ url('portal/store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Data</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <label for="nama_portal" class="col-sm-2 col-form-label">Nama portal</label>
                                <div class="col-sm-10">
                                    <select name="variabel_id" id="variabel_id" class="form-control select2bs4"
                                        data-dropdown-css-class="select2-danger" data-placeholder="Penerima" required>
                                        @foreach ($list_variabel as $item)
                                            <option value="{{ $item->variabel_id }}">{{ $item->nama_variabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="value" class="col-sm-2 col-form-label">Nilai</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="value" name="value" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="value" class="col-sm-2 col-form-label">Tanggal</label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        $('.select2').select2()
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

        function edit(id) {
            $.ajax({
                type: 'get',
                url: "{{ url('portal/edit') }}/" + id,
                // data:{'id':id}, 
                success: function(tampil) {

                    // console.log(tampil); 
                    $('#tampildata').html(tampil);
                    $('#editModal').modal('show');
                }
            })
        }
    </script>
@endpush
