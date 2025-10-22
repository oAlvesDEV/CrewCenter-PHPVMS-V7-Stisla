<div class="table-responsive">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>@lang('common.name')</th>
        <th>@lang('common.description')</th>
        <th>@lang('common.downloads')</th>
      </tr>
    </thead>
    <tbody>
      @foreach($files as $file)
        <tr>
          <td>
            <a href="{{ route('frontend.downloads.download', [$file->id]) }}" target="_blank"
               @if($file->isExternalFile) data-external-redirect="{{ $file->url }}" @endif>
              {{ $file->name }}
            </a>
          </td>
          <td>
            @if($file->description)
              {{ $file->description }}
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>
            @if ($file->download_count > 0)
              <span class="badge badge-primary">{{ $file->download_count }} {{ trans_choice('common.download', $file->download_count) }}</span>
            @else
              <span class="text-muted">0</span>
            @endif
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
