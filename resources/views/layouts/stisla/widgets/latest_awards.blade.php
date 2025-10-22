@if($awards->count() > 0)
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th scope="col">Ident</th>
            <th scope="col">Name</th>
            <th scope="col">Award</th>
          </tr>
        </thead>
        <tbody>
          @foreach($awards as $a)
            <tr>
              <td class="fw-semibold text-dark">
                {{ optional($a->user)->ident }}
              </td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar me-2">
                    <img src="{{ optional($a->user)->avatar ? $a->user->avatar->url : optional($a->user)->gravatar(64) }}"
                         alt="{{ optional($a->user)->name_private }}"
                         class="rounded-circle"
                         width="36" height="36"
                         style="object-fit:cover;">
                  </div>
                  <div>
                    <span class="fw-semibold">{{ optional($a->user)->name_private }}</span><br>
                    <small class="text-muted">{{ optional($a->user)->callsign }}</small>
                  </div>
                </div>
              </td>
              <td>
                <div class="d-flex align-items-center">
                  @if (isset($a->award->image_url))
                    <img src="{{ $a->award->image_url }}" 
                         alt="{{ $a->award->name }}" 
                         class="rounded me-2"
                         width="42"
                         height="42"
                         style="object-fit:contain;">
                  @endif
                  <span class="fw-semibold">{{ optional($a->award)->name }}</span>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endif
