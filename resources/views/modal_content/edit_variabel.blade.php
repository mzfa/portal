@extends('layouts.tamplate')

@section('content')


<div class="mb-3 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Nama variabel</label>
    <div class="col-sm-10">
    <input type="text" class="form-control" id="nama_variabel" name="nama_variabel" value="{{ $data->nama_variabel }}" required>
    </div>
</div>
<input type="hidden" class="form-control" id="variabel_id" name="variabel_id" value="{{ Crypt::encrypt($data->variabel_id) }} " required>
<div class="mb-3 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Target Harian</label>
    <div class="col-sm-10">
    <input type="text" class="form-control" id="harian" name="harian" value="{{ $data->harian }}">
    </div>
</div>
<div class="mb-3 row">
    <label for="staticEmail" class="col-sm-2 col-form-label">Target Bulanan</label>
    <div class="col-sm-10">
    <input type="text" class="form-control" id="bulanan" name="bulanan" value="{{ $data->bulanan }}">
    </div>
</div>
<div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" @if($data->jenis_variabel == 1) checked @endif id="customSwitchDet" name="jenis_variabel">
    <label class="custom-control-label" for="customSwitchDet">Total dibagi jumlah bulan?</label>
</div>


@endsection
