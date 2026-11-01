<div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200 dark:from-gray-900 dark:to-gray-800 dark:border-gray-700">
    @php
        $roleInfo = $this->getRoleInfo();
    @endphp

    @if($roleInfo)
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('admin.your_role') }}</h3>
                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                    {{ $roleInfo['role_name'] }}
                </p>
            </div>
            <div class="text-right">
                <div class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 text-xs font-semibold rounded-full">
                    {{ __('admin.status.active') }}
                </div>
            </div>
        </div>
    @else
        <div class="text-gray-500 dark:text-gray-400">
            {{ __('admin.no_role_info') }}
        </div>
    @endif
</div>
