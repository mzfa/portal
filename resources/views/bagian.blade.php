@extends('layouts.app')

@section('content')
    @php
        $indent = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    @endphp

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Bagian &nbsp;
                            <a tooltip="Sync Data bagian" href="{{ url('bagian/sync') }}" id="create_record" class="btn btn-danger text-white shadow-sm">
                                <i class="bi bi-sync"></i> Sync
                            </a>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nama Bagian</th>
                                        <th>Group </th>
                                        <th>Seri Bagian </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $bagian1)
                                        @empty($bagian1->referensi_bagian)
                                            <tr class="@if ($bagian1->group_bagian == 'GROUP') text-danger @else text-success @endif">
                                                <td>{{ $bagian1->nama_bagian }}</td>
                                                <td>{{ $bagian1->group_bagian }}</td>
                                                <td>{{ $bagian1->seri_bagian }}</td>
                                            </tr>
                                            @foreach ($data as $bagian2)
                                                @if ($bagian1->bagian_id == $bagian2->referensi_bagian)
                                                    <tr class="@if ($bagian2->group_bagian == 'GROUP') text-danger @else text-success @endif">
                                                        <td>{!! $indent !!}{{ $bagian2->nama_bagian }}</td>
                                                        <td>{{ $bagian2->group_bagian }}</td>
                                                        <td>{{ $bagian2->seri_bagian }}</td>
                                                    </tr>
                                                @endif
                                                @foreach ($data as $bagian3)
                                                    @if ($bagian1->bagian_id == $bagian2->referensi_bagian && $bagian2->bagian_id == $bagian3->referensi_bagian)
                                                        <tr class="@if ($bagian3->group_bagian == 'GROUP') text-danger @else text-success @endif">
                                                            <td>{!! $indent . $indent !!}{{ $bagian3->nama_bagian }}</td>
                                                            <td>{{ $bagian3->group_bagian }}</td>
                                                            <td>{{ $bagian3->seri_bagian }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endempty
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
