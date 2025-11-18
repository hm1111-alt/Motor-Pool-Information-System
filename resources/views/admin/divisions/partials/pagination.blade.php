<div class="mt-6 flex items-center justify-between">
    <div class="text-sm text-gray-700">
        Showing <span class="font-medium">{{ $divisions->firstItem() }}</span> to <span class="font-medium">{{ $divisions->lastItem() }}</span> of <span class="font-medium">{{ $divisions->total() }}</span> results
    </div>
    <div class="flex space-x-2">
        {{ $divisions->links() }}
    </div>
</div>