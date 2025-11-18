@foreach($classes as $class)
    <tr>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">{{ $class->class_name }}</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a href="{{ route('admin.classes.edit', $class) }}" class="text-[#1e6031] hover:text-[#164f2a] mr-3 edit-class">Edit</a>
            <a href="{{ route('admin.classes.destroy', $class) }}" class="text-red-600 hover:text-red-900 delete-class">Delete</a>
        </td>
    </tr>
@endforeach