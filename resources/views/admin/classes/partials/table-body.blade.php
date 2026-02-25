@php
    // Initialize row number counter based on current page
    $rowNumber = ($classes->currentPage() - 1) * $classes->perPage() + 1;
@endphp

@foreach($classes as $class)
<tr class="class-row hover:bg-gray-50 transition duration-150 {{ $loop->even ? 'bg-white' : 'bg-gray-50' }}" 
    data-name="{{ strtolower($class->class_name) }}">
    <td style="padding: 8px; text-align: center; font-size: 0.875rem;">
        {{ $rowNumber + $loop->index }}
    </td>
    <td style="padding: 8px; font-size: 0.875rem;">
        <div class="text-sm font-medium text-gray-900">{{ $class->class_name }}</div>
    </td>
    <td style="padding: 8px; text-align: center;">
        <div class="flex justify-center space-x-1 action-buttons">
            <!-- Edit Button (Opens Modal) -->
            <button type="button"
                data-bs-toggle="modal"
                data-bs-target="#editClassModal"
                data-id="{{ $class->id_class }}"
                data-name="{{ $class->class_name }}"
                class="btn edit-btn inline-flex items-center px-2 py-1 border text-xs font-medium rounded-md transition ease-in-out duration-150">
                <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </button>
        </div>
    </td>
</tr>
@endforeach

@if($classes->isEmpty())
<tr id="no-results-row">
    <td colspan="3" style="padding: 20px; text-align: center; font-size: 0.875rem; color: #6b7280;">
        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 32px 0;">
            <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <p style="font-size: 1.125rem; font-weight: 500; color: #111827; margin: 0 0 4px 0;">No classes found</p>
            <p style="margin: 0; color: #6b7280;">Try adjusting your search criteria</p>
        </div>
    </td>
</tr>
@endif