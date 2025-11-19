<div class="mt-6 flex items-center justify-between">
    <div class="text-sm text-gray-700">
        Showing <span class="font-medium">{{ $employees->firstItem() }}</span> to <span class="font-medium">{{ $employees->lastItem() }}</span> of <span class="font-medium">{{ $employees->total() }}</span> results
    </div>
    <div class="flex space-x-2">
        {{ $employees->links() }}
    </div>
</div>