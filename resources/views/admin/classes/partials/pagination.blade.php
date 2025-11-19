<div class="mt-6 flex items-center justify-between">
    <div class="text-sm text-gray-700">
        Showing <span class="font-medium">{{ $classes->firstItem() }}</span> to <span class="font-medium">{{ $classes->lastItem() }}</span> of <span class="font-medium">{{ $classes->total() }}</span> results
    </div>
    <div class="flex space-x-2">
        {{ $classes->links() }}
    </div>
</div>