@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Menu &nbsp;
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <form action="{{ url('hakakses/modul_akses') }}" method="post">
                                @csrf
                                <button type="submit" action="{{ url('menu') }}" class="btn btn-primary">Simpan Akses</button>
                                <input type="hidden" name="hakakses_id" value="{{ $data_hakakses[0]->hakakses_id }}">
                                <table class="table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Menu</th>
                                            <th>Url</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $menu_akses = explode('|', $data_hakakses[0]->menu_id);
                                            // dump($menu_akses);
                                        @endphp
                                        @foreach ($menu as $item)
                                            <tr class="bg-info">
                                                <td>
                                                    <?php
                                                    $status = null;
                                                    foreach ($menu_akses as $cekmenu) {
                                                        if ($cekmenu == $item['menu_id']) {
                                                            $status = 'active';
                                                        }
                                                    }
                                                    ?>
                                                    <input type="checkbox" <?php if ($status != null) {
                                                        echo 'checked';
                                                    } ?> class="checkbox" name="menu_id[]" value="{{ $item['menu_id'] }}">
                                                </td>
                                                <td>
                                                    <h5 class="text-white">{{ strtoupper($item['nama_menu']) }}</h5>
                                                    @if ($item['parent_id'] == 0)
                                                    @else
                                                        <h5 class="text-primary">&nbsp;&nbsp;&nbsp;
                                                            {{ strtoupper($item['nama_menu']) }}</h5>
                                                    @endif
                                                </td>
                                                <td>{{ $item['url_menu'] }}</td>
                                            </tr>
                                            @foreach ($item['submenu'] as $submenu)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" <?php if (array_search($submenu['menu_id'], $menu_akses) != null) {
                                                            echo 'checked';
                                                        } ?> class="checkbox" name="menu_id[]" value="{{ $submenu['menu_id'] }}">
                                                    </td>
                                                    <td>
                                                        <p class="text-danger">&nbsp;&nbsp;&nbsp;{{ strtoupper($submenu['nama_menu']) }}</p>
                                                    </td>
                                                    <td>{{ $submenu['url_menu'] }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
