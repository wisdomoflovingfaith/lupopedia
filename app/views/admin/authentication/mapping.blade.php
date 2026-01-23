@extends('layouts.admin')

@section('title', 'Create User Mapping')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Create User Mapping</h1>
                <a href="{{ route('admin.authentication.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Authentication
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Map Lupopedia User to Crafty Syntax Operator</h5>
                </div>
                <div class="card-body">
                    <form id="mappingForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lupo_user_id">Lupopedia User</label>
                                    <select class="form-control" id="lupo_user_id" name="lupo_user_id" required>
                                        <option value="">Select Lupopedia User</option>
                                        @foreach($lupoUsers as $user)
                                            <option value="{{ $user->id }}" {{ old('lupo_user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name ?? 'Unknown' }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lupo_user_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="crafty_operator_id">Crafty Syntax Operator</label>
                                    <select class="form-control" id="crafty_operator_id" name="crafty_operator_id" required>
                                        <option value="">Select Crafty Operator</option>
                                        @foreach($craftyOperators as $operator)
                                            <option value="{{ $operator->operatorid }}" {{ old('crafty_operator_id') == $operator->operatorid ? 'selected' : '' }}>
                                                {{ $operator->crafty_name ?? 'Unknown' }} ({{ $operator->crafty_email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('crafty_operator_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mapping_type">Mapping Type</label>
                                    <select class="form-control" id="mapping_type" name="mapping_type" required>
                                        <option value="manual" {{ old('mapping_type', 'manual') == 'manual' ? 'selected' : '' }}>
                                            Manual
                                        </option>
                                        <option value="auto" {{ old('mapping_type') == 'auto' ? 'selected' : '' }}>
                                            Automatic
                                        </option>
                                        <option value="imported" {{ old('mapping_type') == 'imported' ? 'selected' : '' }}>
                                            Imported
                                        </option>
                                    </select>
                                    @error('mapping_type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">Notes (Optional)</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Create Mapping
                            </button>
                            <a href="{{ route('admin.authentication.index') }}" class="btn btn-secondary ml-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Existing Mappings</h6>
                </div>
                <div class="card-body">
                    @if($existingMappings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Lupo User</th>
                                        <th>Crafty Op</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($existingMappings->take(10) as $mapping)
                                        <tr>
                                            <td>
                                                <small>{{ $mapping->lupo_email }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $mapping->crafty_email }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($existingMappings->count() > 10)
                            <div class="text-center">
                                <small class="text-muted">And {{ $existingMappings->count() - 10 }} more...</small>
                            </div>
                        @endif
                    @else
                        <p class="text-muted">No existing mappings found.</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Mapping Guidelines</h6>
                </div>
                <div class="card-body">
                    <ul class="small">
                        <li><strong>Manual:</strong> Created by administrator manually</li>
                        <li><strong>Automatic:</strong> Created by system based on email match</li>
                        <li><strong>Imported:</strong> Created from bulk import process</li>
                        <li>Each user can only be mapped to one operator</li>
                        <li>Each operator can only be mapped to one user</li>
                        <li>Notes are optional but recommended for tracking</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('mappingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
    submitBtn.disabled = true;
    
    const formData = new FormData(this);
    
    axios.post('{{ route('admin.authentication.mapping.store') }}', formData)
        .then(response => {
            if (response.data.success) {
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = `
                    <strong>Success!</strong> ${response.data.message}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                `;
                
                document.querySelector('.card-body').prepend(alert);
                
                // Reset form
                this.reset();
                
                // Scroll to top
                window.scrollTo(0, 0);
            } else {
                // Show error message
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show';
                alert.innerHTML = `
                    <strong>Error!</strong> ${response.data.message}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                `;
                
                document.querySelector('.card-body').prepend(alert);
                window.scrollTo(0, 0);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Handle validation errors
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                let errorHtml = '<strong>Please fix the following errors:</strong><ul>';
                
                for (const [field, messages] of Object.entries(errors)) {
                    errorHtml += `<li>${messages.join(', ')}</li>`;
                }
                
                errorHtml += '</ul>';
                
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show';
                alert.innerHTML = `
                    ${errorHtml}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                `;
                
                document.querySelector('.card-body').prepend(alert);
                window.scrollTo(0, 0);
            } else {
                alert('Error creating mapping. Please try again.');
            }
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
});

// Auto-select users/operators from URL parameters
const urlParams = new URLSearchParams(window.location.search);
const lupoId = urlParams.get('lupo_id');
const craftyId = urlParams.get('crafty_id');

if (lupoId) {
    document.getElementById('lupo_user_id').value = lupoId;
}

if (craftyId) {
    document.getElementById('crafty_operator_id').value = craftyId;
}
</script>
@endpush
