@extends('layouts.app')

@section('content')
    {{-- <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-auto">
                </div>
                <div class="col px-0 align-self-center">
                    <h5 class="mb-0 text-color-theme">Manage Menu</h5>
                    <p class="text-muted size-12"></p>
                </div>
                <div class="col-auto">
                    <button tooltip="Tambah Data" id="create_record" class="btn btn-primary text-white shadow-sm"
                        type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <i class="bi bi-plus"></i> Tambah
                    </button>
                </div>
            </div>
        </div>
        <div class="card border-0">
            <div class="card-body">
                <div class="table-responsive">

                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ Session::get('success') }}</strong> 
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <table class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>Nama Menu</th>
                                <th>Url</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1 @endphp
                            @foreach ($menu as $item)
                                <tr class="bg-info">
                                    <td>
                                        <h5 class="text-white">{{ strtoupper($item['nama_menu']) }}</h5>
                                        @if ($item['parent_id'] == 0)
                                        @else
                                            <h5 class="text-primary">&nbsp;&nbsp;&nbsp;
                                                {{ strtoupper($item['nama_menu']) }}</h5>
                                        @endif
                                    </td>
                                    <td>{{ $item['url_menu'] }}</td>
                                    <td>
                                        <a onclick="return edit({{ $item['menu_id'] }})"
                                            class="btn text-white btn-warning"><i class="bi bi-pen"></i></a>
                                        <a onclick="return tambahsubmenu({{ $item['menu_id'] }})"
                                            class="btn text-white btn-primary"><i class="bi bi-plus"></i></a>
                                            @if (empty($item['submenu']))
                                            <a href="{{ url('menu/delete/' . Crypt::encrypt($item['menu_id'])) }}"
                                                class="btn text-white btn-danger"><i class="bi bi-trash"></i></a>
                                            @endif
                                    </td>
                                </tr>
                                @foreach ($item['submenu'] as $submenu)
                                <tr>
                                    <td>
                                        <p class="text-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($submenu['nama_menu']) }}</p>
                                    </td>
                                    <td>{{ $submenu['url_menu'] }}</td>
                                    <td>
                                        <a onclick="return edit({{ $submenu['menu_id'] }})"
                                            class="btn text-white btn-info"><i class="bi bi-pen"></i></a>
                                        <a href="{{ url('menu/delete/' . Crypt::encrypt($submenu['menu_id'])) }}"
                                            class="btn text-white btn-danger"><i class="bi bi-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Menu &nbsp;
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-lg">
                                Tambah
                            </button>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">



                            <table class="table" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nama Menu</th>
                                        <th>Url</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=1 @endphp
                                    @foreach ($menu as $item)
                                        <tr class="bg-info">
                                            <td>
                                                <h5 class="text-white">{{ strtoupper($item['nama_menu']) }}</h5>
                                                @if ($item['parent_id'] == 0)
                                                @else
                                                    <h5 class="text-primary">&nbsp;&nbsp;&nbsp;
                                                        {{ strtoupper($item['nama_menu']) }}</h5>
                                                @endif
                                            </td>
                                            <td>{{ $item['url_menu'] }}</td>
                                            <td>
                                                <a onclick="return edit({{ $item['menu_id'] }})" class="btn text-white btn-warning"><i class="fa fa-pen"></i></a>
                                                <a onclick="return tambahsubmenu({{ $item['menu_id'] }})" class="btn text-white btn-primary"><i class="fa fa-plus"></i></a>
                                                @if (empty($item['submenu']))
                                                    <a href="{{ url('menu/delete/' . Crypt::encrypt($item['menu_id'])) }}" class="btn text-white btn-danger"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                        @foreach ($item['submenu'] as $submenu)
                                            <tr>
                                                <td>
                                                    <p class="text-danger">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($submenu['nama_menu']) }}
                                                    </p>
                                                </td>
                                                <td>{{ $submenu['url_menu'] }}</td>
                                                <td>
                                                    <a onclick="return edit({{ $submenu['menu_id'] }})" class="btn text-white btn-info"><i class="fa fa-pen"></i></a>
                                                    <a href="{{ url('menu/delete/' . Crypt::encrypt($submenu['menu_id'])) }}" class="btn text-white btn-danger"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-lg">
            <div class="modal-dialog modal-lg">
                <form action="{{ url('menu/store') }}" method="post" enctype="multipart/form-data">
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
                                <label for="staticEmail" class="col-sm-2 col-form-label">Nama Menu</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nama_menu" name="nama_menu" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Url</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="url_menu" name="url_menu">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Icon</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="icon_menu" name="icon_menu">
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

        <div class="modal fade" id="editModal">
            <div class="modal-dialog modal-lg">
                <form action="{{ url('menu/update') }}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Ubah Data</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="tampildata"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="subMenuModal">
            <div class="modal-dialog modal-lg">
                <form action="{{ url('menu/store') }}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Sub Menu</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <label for="nama_menu" class="col-sm-2 col-form-label">Nama Menu</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nama_menu" name="nama_menu" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="url_menu" class="col-sm-2 col-form-label">Url</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="url_menu" name="url_menu">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Icon</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="icon_menu" name="icon_menu">
                                </div>
                            </div>
                            <input type="hidden" name="parent_id" id="parent_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function edit(id) {
            $.ajax({
                type: 'get',
                url: "{{ url('menu/edit') }}/" + id,
                // data:{'id':id}, 
                success: function(tampil) {

                    // console.log(tampil); 
                    $('#tampildata').html(tampil);
                    $('#editModal').modal('show');
                }
            })
        }

        function tambahsubmenu(id) {
            $('#parent_id').val(id);
            $('#subMenuModal').modal('show');
        }
    </script>
@endpush
