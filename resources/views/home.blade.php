@extends('layouts.app')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-12">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>Selamat Datang, {{ Auth::user()->name }}</h3>

                <p>Pada Aplikasi E-Office Rumah Sakit Umum Pekerja</p>
            </div>
            <div class="icon">
                <i class="ion ion-hospital-o"></i>
            </div>
            <a href="{{ url('portal') }}" class="small-box-footer"><i class="fas fa-envelope"></i> &nbsp; Lanjut Ke Portal </a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->
<!-- Main row -->

@endsection