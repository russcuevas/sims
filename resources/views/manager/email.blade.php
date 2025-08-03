<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Email Inbox</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
    body {
      background-color: #f8f9fa;
    }
    .sidebar {
      width: 220px;
      background-color: #fff;
      height: 100vh;
      padding-top: 20px;
      border-right: 1px solid #dee2e6;
    }
    .compose-btn {
      width: 90%;
      margin: 0 auto 20px;
      display: block;
      border-radius: 10px;
      background-color: #A16D28;
      border: none;
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
    .modal-header, .modal-body, .modal-footer {
      border: none;
    }
    .modal-body textarea {
      resize: none;
      border: none;
      height: 200px;
      width: 100%;
    }
    .modal-body textarea:focus {
      outline: none;
      box-shadow: none;
    }
  </style>
</head>
<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar">
      <button class="btn btn-primary compose-btn" data-bs-toggle="modal" data-bs-target="#composeModal">Compose</button>
      <a href="{{ route('manager.email.management.page') }}" class="active">Sent</a>
      <a href="{{ route('manager.email.trash') }}">Trash</a>
    </div>

    <!-- Email List -->
    <div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/manager/dashboard" style="text-decoration: none">Back to dashboard</a>

        <!-- Trash icon button -->
        <form id="deleteEmailsForm" action="{{ route('manager.email.bulkDelete') }}" method="POST">
            @csrf
            <input type="hidden" name="email_ids" id="emailIdsInput">
            <button type="submit" id="deleteSelectedBtn" class="btn btn-danger btn-sm d-none">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>
    </div>    
    <table class="table table-bordered">
            <thead>
        <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>To</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Sent At</th>
            <th>Action</th>
        </tr>
            </thead>
            <tbody>
                @foreach ($emails as $email)
    <tr>
        <td><input type="checkbox" class="email-checkbox" value="{{ $email->id }}"></td>
        <td>{{ $email->to_email }}</td>
        <td>{{ $email->subject ?? 'No Subject' }}</td>
        <td>{{ Str::limit(strip_tags($email->message), 50) }}</td>
        <td>{{ \Carbon\Carbon::parse($email->created_at)->format('F d, Y h:i A') }}</td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewEmailModal{{ $email->id }}">
                View
            </button>
        </td>
    </tr>


                <!-- Modal for this email -->
                <div class="modal fade" id="viewEmailModal{{ $email->id }}" tabindex="-1" aria-labelledby="viewEmailLabel{{ $email->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="viewEmailLabel{{ $email->id }}">Email to: {{ $email->to_email }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Subject:</strong> {{ $email->subject ?? 'No Subject' }}</p>
                                <p><strong>Message:</strong></p>
                                <div class="border rounded p-1" style="white-space: pre-wrap;">
                                    <p>{{ $email->message }}</p>
                                </div>
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
                @endforeach
            </tbody>
        </table>
    </div>
  </div>

  <!-- Compose Modal -->
  <div class="modal fade" id="composeModal" tabindex="-1" aria-labelledby="composeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 class="modal-title" id="composeModalLabel">New Message</h5>
          <button type="button" class="btn-close ms-3" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('manager.email.send') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body px-4">
          <div class="mb-2">
            <select style="width: 100%" class="form-select select2 border-0 border-bottom" name="to_email" required>
            <option value="" disabled selected>To</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->supplier_email_add }}">{{ $supplier->supplier_email_add }}</option>
            @endforeach
            </select>
          </div>
          <div class="mb-2">
            <input type="text" class="form-control border-0 border-bottom" placeholder="Subject" name="subject">
          </div>
          <div>
            <textarea class="form-control" placeholder="Start writing your message..." name="message"></textarea>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
        <div class="d-flex align-items-center gap-2">
        <button type="submit" class="btn btn-primary">Send</button>

        <!-- File input wrapped in label -->
        <label class="btn btn-light mb-0">
        <i class="bi bi-paperclip"></i>
            <input type="file" hidden name="attachment" id="attachmentInput">
        </label>

        <!-- File name display -->
        <span id="fileNameDisplay" class="text-muted small"></span>
            </div>

            </div>

        </div>
        </div>
    </div>
    </form>

    <!-- jQuery (required for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2({
      dropdownParent: $('#composeModal') // ensures correct rendering inside Bootstrap modal
    });
  });
</script>

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
        const attachmentInput = document.getElementById('attachmentInput');
        const fileNameDisplay = document.getElementById('fileNameDisplay');

        attachmentInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
            fileNameDisplay.textContent = this.files[0].name;
            } else {
            fileNameDisplay.textContent = '';
            }
        });
    </script>

    <script>
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        const checkboxes = document.querySelectorAll('.email-checkbox');
        const emailIdsInput = document.getElementById('emailIdsInput');
        const selectAll = document.getElementById('selectAll');

        function updateDeleteButton() {
            const selectedIds = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedIds.length > 0) {
                deleteBtn.classList.remove('d-none');
                emailIdsInput.value = selectedIds.join(',');
            } else {
                deleteBtn.classList.add('d-none');
                emailIdsInput.value = '';
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateDeleteButton);
        });

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateDeleteButton();
        });
    </script>

</body>
</html>
