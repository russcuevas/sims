<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Email Trash</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <style>
    body { background-color: #f8f9fa; }
    .sidebar {
      width: 220px;
      background-color: #fff;
      height: 100vh;
      padding-top: 20px;
      border-right: 1px solid #dee2e6;
    }
    .sidebar a {
      padding: 10px 20px;
      display: block;
      color: #000;
      text-decoration: none;
    }
    .sidebar a:hover, .sidebar .active {
      background-color: #e2e6ea;
      font-weight: bold;
    }
    .email-table td, .email-table th {
      vertical-align: middle;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
      <a href="{{ route('manager.email.management.page') }}">Sent</a>
      <a href="{{ route('manager.email.trash') }}" class="active">Trash</a>
    </div>

    <!-- Archived Emails List -->
    <div class="flex-grow-1 p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
  <a href="/manager/dashboard" style="text-decoration: none">Back to dashboard</a>

  <div class="d-flex gap-2">
    <form id="restoreEmailsForm" action="{{ route('manager.email.bulkRestore') }}" method="POST">
      @csrf
      <input type="hidden" name="email_ids" id="restoreIdsInput">
      <button type="submit" id="restoreSelectedBtn" class="btn btn-success btn-sm d-none">
        <i class="bi bi-arrow-counterclockwise"></i> Restore
      </button>
    </form>

    <form id="deleteEmailsForm" action="{{ route('manager.email.bulkDeletePermanent') }}" method="POST">
      @csrf
      <input type="hidden" name="email_ids" id="deleteIdsInput">
      <button type="submit" id="deleteSelectedBtn" class="btn btn-danger btn-sm d-none">
        <i class="bi bi-trash"></i> Delete Permanently
      </button>
    </form>
  </div>
</div>

<table class="table table-bordered">
  <thead>
    <tr>
      <th><input type="checkbox" id="selectAll"></th>
      <th>To</th>
      <th>Subject</th>
      <th>Message</th>
      <th>Archived At</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($emails as $email)
      <tr>
        <td><input type="checkbox" class="email-checkbox" value="{{ $email->id }}"></td>
        <td>{{ $email->to_email }}</td>
        <td>{{ $email->subject ?? 'No Subject' }}</td>
        <td>{{ Str::limit(strip_tags($email->message), 50) }}</td>
        <td>{{ \Carbon\Carbon::parse($email->updated_at)->format('F d, Y h:i A') }}</td>
        <td>
          <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewEmailModal{{ $email->id }}">
            View
          </button>
        </td>
      </tr>

      <!-- Modal -->
      <div class="modal fade" id="viewEmailModal{{ $email->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Archived Email to: {{ $email->to_email }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <p><strong>Subject:</strong> {{ $email->subject ?? 'No Subject' }}</p>
              <div class="border rounded p-2" style="white-space: pre-wrap;">{{ $email->message }}</div>
              @if ($email->file)
                <hr>
                <p><strong>Attachment:</strong></p>
                                    <a href="{{ asset('emails/' . $email->file) }}" target="_blank" class="btn btn-sm btn-secondary">
                                        View Attachment
                                    </a>              
                                    @endif
            </div>
          </div>
        </div>
      </div>
    @empty
      <tr><td colspan="6" class="text-center">No archived emails found.</td></tr>
    @endforelse
  </tbody>
</table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif
    </script>

  <script>
    const restoreBtn = document.getElementById('restoreSelectedBtn');
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    const restoreInput = document.getElementById('restoreIdsInput');
    const deleteInput = document.getElementById('deleteIdsInput');
    const checkboxes = document.querySelectorAll('.email-checkbox');
    const selectAll = document.getElementById('selectAll');

    function updateBulkButtons() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
        
        if (selected.length > 0) {
        restoreBtn.classList.remove('d-none');
        deleteBtn.classList.remove('d-none');
        } else {
        restoreBtn.classList.add('d-none');
        deleteBtn.classList.add('d-none');
        }

        restoreInput.value = selected.join(',');
        deleteInput.value = selected.join(',');
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateBulkButtons));
    selectAll.addEventListener('change', () => {
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkButtons();
    });
    </script>

</body>
</html>
