@extends('layouts.layout')

@section('main-content')
    <div class="main-content">
        <form method="post" action="">
            @csrf
            @if (!empty($error))
                <div class="alert alert-danger mb-3" role="alert">
                    {{ $error }}
                </div>
            @endif

            <div class="mb-3">
                <label for="admin-email" class="form-label">メールアドレス</label>
                <input type="email" name="admin-email" class="form-control" id="admin-email" placeholder="name@example.com" required>
            </div>

            <div class="mb-3">
                <label for="admin-password" class="form-label">パスワード</label>
                <input type="password" name="admin-password" class="form-control" id="admin-password" required>
            </div>

            <div class="col-auto">
                <button type="submit" class="submit-btn btn btn-primary btn-lg">送信</button>
            </div>
        </form>
    </div>
@endsection