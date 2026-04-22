@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">Edit Email Template</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <form action="{{ route('email-templates.update', $emailTemplate->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Template Name</label>
                    <input type="text" name="name" class="form-control text-uppercase"
                        value="{{ $emailTemplate->name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" class="form-control" value="{{ $emailTemplate->subject }}"
                        required>
                </div>
                @if ($emailTemplate->name == 'custom')
                    <div class="form-group">
                        <label for="subject">Redirect URL</label>
                        <input type="text" name="redirect_url" class="form-control" value="{{ $emailTemplate->redirect_url }}"
                        required>
                    </div>
                @endif
                <div class="form-group">
                    <label for="body">Body         @if ($emailTemplate->name != 'custom')(Example: Use %user_name% for dynamic username) @endif</label>
                    <textarea name="body" class="form-control" rows="5" required>{{ $emailTemplate->body }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
        @if ($emailTemplate->name != 'custom')
            <div class="col-4">
                <div>
                    <h4>Available Placeholders:</h4>
                    <div class="placeholder-btn hov-bg-black-10 p-2 fs-13 fw-700" style="cursor: pointer;"
                        data-placeholder="%user_name%">%user_name%</div>
                    <div class="placeholder-btn hov-bg-black-10 p-2 fs-13 fw-700" style="cursor: pointer;"
                        data-placeholder="%product_name%">%product_name%</div>
                    <div class="placeholder-btn hov-bg-black-10 p-2 fs-13 fw-700" style="cursor: pointer;"
                        data-placeholder="%current_price%">%current_price%</div>
                </div>
            </div>
        @endif
    </div>
@endsection


@section('script')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text)
                .then(function() {
                    AIZ.plugins.notify('success', 'Copied to clipboard: ' + text);
                })
                .catch(function(err) {
                    console.error('Failed to copy: ', err);
                });
        }

        document.querySelectorAll('.placeholder-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                const placeholder = button.getAttribute('data-placeholder');
                copyToClipboard(placeholder);
            });
        });
    </script>
@endsection
