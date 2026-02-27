<div class="mt-6 flex items-center justify-between">
    @php
        $statusFilter = request('status');
        $filterText = '';
        if ($statusFilter) {
            $filterText = ' (' . ucfirst($statusFilter) . ')';
        }
    @endphp
    <div class="text-sm text-gray-700">
        Showing <span class="font-medium">{{ $subunits->firstItem() }}</span> to <span class="font-medium">{{ $subunits->lastItem() }}</span> of <span class="font-medium">{{ $subunits->total() }}</span> results{!! $filterText !!}
    </div>
    <div class="flex space-x-2">
        {{ $subunits->links() }}
    </div>
</div>