@extends('layouts.tamplate')

@section('content')

    <div class="mb-3 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Hak Akses</label>
        <div class="col-sm-10">
        <input type="text" class="form-control" id="nama_hakakses" name="nama_hakakses" value="{{ $data->nama_hakakses }}" required>
        </div>
    </div>
    @php
        $bagian = explode('|',$data->akses_bagian);
    @endphp
    <div class="mb-3 row">
        <label for="staticEmail" class="col-sm-2 col-form-label">Akses Bagian</label>
        <div class="col-sm-10">
            <div class="form-group">
                <div class="select2-purple">
                    <select class="select2" name="akses_bagian[]" multiple="multiple" data-placeholder="Akses Bagian"
                        data-dropdown-css-class="select2-purple" style="width: 100%;" required>
                        @foreach ($list_variabel as $item)
                            @if(array_search(strval($item->variabel_id),$bagian,true))
                                <option value="{{ $item->variabel_id }}" selected>{{ $item->nama_variabel }}</option>
                            @else
                                <option value="{{ $item->variabel_id }}">{{ $item->nama_variabel }}</option>
                            @endif
                        @endforeach

                    </select>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" class="form-control" id="hakakses_id" name="hakakses_id" value="{{ Crypt::encrypt($data->hakakses_id) }}" required>
@endsection

@push('scripts')
    <script>
        $('.select2').select2()
        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    </script>
@endpush