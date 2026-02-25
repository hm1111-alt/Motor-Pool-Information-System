<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')
    
    <div class="modal-body px-3 py-2">
        <div class="mb-2">
            <label for="update_password_current_password" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-2">
            <label for="update_password_password" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">New Password</label>
            <input id="update_password_password" name="password" type="password" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-2">
            <label for="update_password_password_confirmation" class="form-label small fw-semibold text-success mb-1" style="font-size: 0.75rem;">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control form-control-sm border-success py-1 px-2 rounded" style="font-size: 0.75rem; height: 30px;" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="modal-footer py-1 justify-content-end">
        <button type="submit" class="btn btn-sm btn-success py-1" style="font-size: 0.75rem; height: 30px;">
            Save
        </button>
        
        @if (session('status') === 'password-updated')
            <div class="alert alert-success mt-2" role="alert" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                {{ __('Password updated successfully!') }}
            </div>
        @endif
    </div>
</form>
