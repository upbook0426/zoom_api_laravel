@extends('layouts.layout')

@section('main-content')
    <div class="main-content">
        @if($oauthSuccess)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h4 class="alert-heading">連携完了</h4>
            <p>Zoomとの連携が完了しました！</p>
        </div>
        @endif

        @if($noZoomCode)
        <div class="alert alert-danger mb-3" role="alert">
            <h4 class="alert-heading">Zoomとの連携が行われていません。</h4>
            <p>このシステムをご利用する場合、Zoomとの連携を行ってください。</p>
            <a href="{{$zoomOuthLink}}" class="btn btn-danger">Zoomと連携</a>
        </div>
        @else
            <h1>予約一覧</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>お客様名</th>
                        <th>会社名</th>
                        <th>アドレス</th>
                        <th>ミーティング開始日</th>
                        <th>デバッグ操作</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($meetings as $meeting)
                    <tr class="@if($meeting->is_canceled) table-danger @endif">
                        <td>{{ $meeting->name }}</td>
                        <td>{{ $meeting->company_name }}</td>
                        <td>{{ $meeting->email }}</td>
                        <td>{{ $meeting->start_at }}</td>
                        <td>
                            @if($meeting->is_canceled)
                                <b>このミーティングはキャンセルされました。</b>
                            @else
                                <a href="{{'/form/alter?hash='.$meeting->hash}}" class="btn btn-success">時間を変更</a>
                                <a href="{{'/form/delete?hash='.$meeting->hash}}" class="btn btn-danger">ミーティングを削除</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <h2>まだミーティングを受け付けていません。</h2>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>
@endsection