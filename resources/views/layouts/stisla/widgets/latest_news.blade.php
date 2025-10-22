    @if($news->count() === 0)
      <div class="text-center text-muted" style="padding: 30px;">
        @lang('widgets.latestnews.nonewsfound')
      </div>
    @endif

    @foreach($news as $item)
      <h4 style="margin-top: 0px;">{{ $item->subject }}</h4>
      <p class="category">{{ $item->user->name_private }}
        - {{ show_datetime($item->created_at) }}</p>

      {!! $item->body !!}
    @endforeach

