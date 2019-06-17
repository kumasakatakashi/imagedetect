@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">画像文字認識</div>
            </div>
            <div class="card-body">
                <!-- エラーメッセージ -->
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible" id="alertfadeout">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul style="margin-bottom:0px">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                </div>
                @endif
                <!-- アップロードフォーム -->
                {!! Form::open(['url' => '/upload', 'method' => 'post', 'files' => true]) !!}
                    <div class="form-group">
                        {!! Form::label('lefile', '画像ファイル：') !!}
                        {!! Form::file('file', ['id' => 'lefile', 'style' => 'display:none']) !!}
                        <div class="input-group">
                            <input type="text" id="photoCover" class="form-control" placeholder="select file..." readonly="readonly">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-primary" onclick="$('input[id=lefile]').click();">Browse</button>
                            </span>
                        </div>
                    </div>
                    {{ csrf_field() }}
                    <div class="form-group">
                        {!! Form::submit('アップロード', ['class' => 'btn btn-primary']) !!}
                    </div>
                {!! Form::close() !!}
                <!-- アップロード画像表示 -->
                @isset($filename)
                <hr>
                <a href="{{ asset('storage/'.$filename) }}" data-lightbox="detectimage">
                    <img src="{{ asset('storage/'.$filename) }}" width="200" height="150" />
                </a>
                <!-- OCR認識フォーム -->
                <hr>
                {!! Form::open(['url' => '/detect', 'method' => 'post', 'files' => false]) !!}
                <div class="form-group">
                    {!! Form::hidden('imagepath', asset('storage/'.$filename)) !!}
                    {!! Form::submit('文字認識', ['class' => 'btn btn-danger']) !!}
                </div>
                {!! Form::close() !!}
                @endisset
                <!-- 認識結果表示 -->
                @isset($result_text)
                <div class="form-group">
                    {!! Form::textarea('result_text', $result_text, ['class' => 'form-control', 'rows' => '20']) !!}
                </div>
                @endisset
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $('input[id=lefile]').change(function(){
        $('input[id=photoCover]').val($(this).val().replace("C:\\fakepath\\", ""));
    });    
});
$(document).ready(function(){
    $(window).on('load', function(){
        window.setTimeout("$('#alertfadeout').fadeOut()", 5000);
    });
});
</script>
@endsection
