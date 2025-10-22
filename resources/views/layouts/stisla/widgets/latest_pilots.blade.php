<div class="card shadow-sm mb-4">
  <div class="card-header bg-primary text-white">
    <h4 class="mb-0">
      <i class="fas fa-user-friends me-2"></i> Pilots List
    </h4>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th scope="col">Ident</th>
            <th scope="col">Name</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $u)
            <tr>
              <td class="fw-semibold text-dark">{{ $u->ident }}</td>
              <td>{{ $u->name_private }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
