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
<!-- ワーニングメッセージ -->
@if (session('warning'))
<div class="alert alert-danger alert-dismissible" id="alertfadeout">
<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
</button>
<ul style="margin-bottom:0px">
    <li>{{ session('warning') }}</li>
</ul>
</div>
@endif
