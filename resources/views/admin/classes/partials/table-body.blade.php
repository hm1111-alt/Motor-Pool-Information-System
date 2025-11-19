@foreach($classes as $class)
<tr class="class-row hover:bg-gray-50 transition duration-150" 
    data-name="{{ strtolower($class->class_name) }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900">{{ $class->class_name }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <a href="{{ route('admin.classes.edit', $class) }}" class="text-[#1e6031] hover:text-[#164f2a] mr-3 inline-flex items-center edit-class">
            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
        </a>
        <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="inline delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center delete-class" data-name="{{ $class->class_name }}">
                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete
            </button>
        </form>
    </td>
</tr>
@endforeach

@if($classes->isEmpty())
<tr id="no-results-row">
    <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">
        <div class="flex flex-col items-center justify-center py-8">
            <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <p class="text-lg font-medium text-gray-900">No classes found</p>
            <p class="mt-1 text-gray-500">Try adjusting your search criteria</p>
        </div>
    </td>
</tr>
@endif