<x-filament::widget>
    <x-filament::card>
        <div id="dashboard-area">
            {{-- Tombol Export PDF --}}
            <div class="mb-4">
                <button onclick="downloadDashboard(this)" style="background-color:#4f46e5; color:white; padding:8px 16px; border:none; border-radius:6px;">
                    📄 Download PDF
                </button>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($this->getStats() as $stat)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-4">
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $stat->getLabel() }}
                        </div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {!! $stat->getValue() !!}
                        </div>

                        @if ($stat->getDescription())
                            <div class="text-sm text-gray-500 mt-1">
                                {!! $stat->getDescription() !!}
                            </div>
                        @endif

                        @if ($stat->getChart())
                            <div class="mt-2">
                                {!! $stat->getChart() !!}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        
    </x-filament::card>
</x-filament::widget>
