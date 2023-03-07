@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Variabel &nbsp;
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
                                        <th>Nama Variabel</th>
                                        <th>Target Bulanan</th>
                                        <th>Target Harian</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no=1 @endphp
                                    @foreach ($variabel as $item)
                                        <tr class="bg-info">
                                            <td>
                                                <h5 class="text-white">{{ strtoupper($item['nama_variabel']) }}</h5>
                                                @if ($item['parent_id'] == 0)
                                                @else
                                                    <h5 class="text-primary">&nbsp;&nbsp;&nbsp;
                                                        {{ strtoupper($item['nama_variabel']) }}</h5>
                                                @endif
                                            </td>
                                            <td>{{ $item['bulanan'] }}</td>
                                            <td>{{ $item['harian'] }}</td>
                                            <td>
                                                <a onclick="return edit({{ $item['variabel_id'] }})" class="btn text-white btn-warning"><i class="fa fa-pen"></i></a>
                                                <a onclick="return tambahsubvariabel({{ $item['variabel_id'] }})" class="btn text-white btn-primary"><i class="fa fa-plus"></i></a>
                                                @if (empty($item['subvariabel']))
                                                    <a href="{{ url('variabel/delete/' . Crypt::encrypt($item['variabel_id'])) }}" class="btn text-white btn-danger"><i class="fa fa-trash"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                        @foreach ($item['subvariabel'] as $subvariabel)
                                            <tr>
                                                <td>
                                                    <p class="text-danger">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ strtoupper($subvariabel['nama_variabel']) }}
                                                    </p>
                                                </td>
                                                <td>{{ $subvariabel['bulanan'] }}</td>
                                                <td>{{ $subvariabel['harian'] }}</td>
                                                <td>
                                                    <a onclick="return edit({{ $subvariabel['variabel_id'] }})" class="btn text-white btn-info"><i class="fa fa-pen"></i></a>
                                                    <a href="{{ url('variabel/delete/' . Crypt::encrypt($subvariabel['variabel_id'])) }}" class="btn text-white btn-danger"><i class="fa fa-trash"></i></a>
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
                <form action="{{ url('variabel/store') }}" method="post" enctype="multipart/form-data">
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
                                <label for="staticEmail" class="col-sm-2 col-form-label">Nama variabel</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nama_variabel" name="nama_variabel" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Target Bulanan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="bulanan" name="bulanan" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Target Harian</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="harian" name="harian" required>
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
                <form action="{{ url('variabel/update') }}" method="post">
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

        <div class="modal fade" id="subvariabelModal">
            <div class="modal-dialog modal-lg">
                <form action="{{ url('variabel/store') }}" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Sub Variabel</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 row">
                                <label for="nama_variabel" class="col-sm-2 col-form-label">Nama variabel</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nama_variabel" name="nama_variabel" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Target Bulanan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="bulanan" name="bulanan" required>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Target Harian</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="harian" name="harian" required>
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
                url: "{{ url('variabel/edit') }}/" + id,
                // data:{'id':id}, 
                success: function(tampil) {

                    // console.log(tampil); 
                    $('#tampildata').html(tampil);
                    $('#editModal').modal('show');
                }
            })
        }

        function tambahsubvariabel(id) {
            $('#parent_id').val(id);
            $('#subvariabelModal').modal('show');
        }
    </script>
@endpush
