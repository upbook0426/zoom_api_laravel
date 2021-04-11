@extends('layouts.layout')

@section('main-content')
    <div class="main-content">
        <form method="post">
        @csrf
            <button type="submmit" class="btn btn-danger">ミーティングをキャンセルする</button>
        </form>
    </div>
@endsection