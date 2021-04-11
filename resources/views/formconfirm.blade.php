@extends('layouts.layout')

@section('main-content')
    <div class="main-content">
        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">お問い合わせありがとうございます。ミーティングを作成いたしました。</h4>
            <p>この度はお問い合わせいただき誠ありがとうございます。いただいたメールの方にzoomミーティングURL,
            パスワード、その他入力情報を記載したメールを送信いたしましたので、ご確認ください。受信フォルダに見当たらない場合、迷惑メールに振り分けられている可能性がございます。</p>

            <p>それでは当日お会いできることを楽しみにしております。またご入力いただいた内容は以下の通りです。ご不明な点がございましたら、カスタマーサービスへご連絡ください。</p>

            <hr>
            <p class="mt-2"><b>お名前</b></p>
            <p>{{session('name')}}</p>

            <p class="mt-2"><b>会社名</b></p>
            <p>{{session('companyname')}}</p>

            <p class="mt-2"><b>ご相談内容</b></p>
            <p>{{session('content')}}</p>

            <p class="mt-2"><b>ミーティング開始日時</b></p>
            <p>{{session('start_time')}}</p>

            <p class="mt-2"><b>フォーム問い合わせ番号</b></p>
            <p>{{session('form_id')}}</p>

        </div>
    </div>
@endsection