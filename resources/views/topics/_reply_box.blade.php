@include('common.error')
<div class="reply-box">
    <form action="{{ route('replies.store') }}" method="POST" accept-charset="UTF-8">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="topic_id" value="{{ $topic->id }}">
        <div class="form-group">
            <textarea id="inputor" class="form-control" rows="3" placeholder="分享你的想法"  name="content"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-share"></i>回复</button>
    </form>
    {{-- 得到已经评论的所有人隐藏 --}}
    @foreach ($replies as $reply)
        <input type="text" hidden="hidden" value="{{ $reply->user->name }}" id="{{ $reply->user_id }}" name="replies">
    @endforeach
</div>
<hr>
@section('styles')
    <link href="{{ asset('css/jquery.atwho.min.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('js/jquery.caret.js') }}"></script>
    <script src="{{ asset('js/jquery.atwho.js') }}"></script>
    <script>

   $(function(){
       // 获取已有评论的所有人名
       var nameValues = [];
       $("input[name='replies']").each(function () {
           var name = $(this).val();
           if ($.inArray(name, nameValues) == -1) {
               nameValues.push(name);
           }
       });
       $('#inputor').atwho({
           at: "@",
           data:nameValues,
           limit: 100
       })
   })
    </script>
@endsection