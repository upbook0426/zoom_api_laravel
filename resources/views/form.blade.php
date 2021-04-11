@extends('layouts.layout')

@section('main-content')
    <div class="main-content">
        <form method="post" action="">
            @csrf

            @if(isset($error))
            <div class="alert alert-danger mb-3" role="alert">
                入力項目に誤りがあります。ご確認の上、もう一度送信をお願いします。
            </div>
            @endif
            
            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス<span class="err"> *入力必須</span></label>
                <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com">
                @if(isset($error['companyname']))<p class="err">{{$error['companyname'][0]}}</p>@endif
            </div>

            <div class="mb-3">
                <label for="your-name" class="form-label">お名前<span class="err"> *入力必須</span></label>
                <input type="text" name="yourname" class="form-control" id="your-name" placeholder="お名前をご入力ください" required>
                @if(isset($error['companyname']))<p class="err">{{$error['companyname'][0]}}</p>@endif
            </div>

            <div class="mb-3">
                <label for="company-name" class="form-label">会社名<span class="err"> *入力必須</span></label>
                <input type="text" name="companyname" class="form-control" id="company-name" placeholder="株式会社〇〇〇〇">
                @if(isset($error['companyname']))<p class="err">{{$error['companyname'][0]}}</p>@endif
            </div>

            <div class="mb-3">
                <label for="startAt" class="form-label">ミーティング開始日時</label>
                <input type="datetime-local" name="startAt" class="form-control" id="startAt" placeholder="name@example.com">
                @if(isset($error['companyname']))<p class="err">{{$error['companyname'][0]}}</p>@endif
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">ご相談内容<span class="err"> *入力必須</span></label>
                <textarea name="content" class="form-control" id="content" rows="3"></textarea>
                <small>1000文字以内でご入力ください。</small>
                @if(isset($error['companyname']))<p class="err">{{$error['companyname'][0]}}</p>@endif
            </div>

            <div class="col-auto">
                <button type="submit" class="submit-btn btn btn-primary btn-lg">送信</button>
            </div>
        </form>
    </div>
@endsection