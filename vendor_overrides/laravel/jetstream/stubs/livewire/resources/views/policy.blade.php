<x-guest-layout>
    <div class="tw-pt-4 tw-bg-gray-100">
        <div class="tw-min-h-screen tw-flex tw-flex-col tw-items-center tw-pt-6 sm:tw-pt-0">
            <div>
                <x-jet-authentication-card-logo />
            </div>

            <div class="tw-w-full sm:tw-max-w-2xl tw-mt-6 tw-p-6 tw-bg-white tw-shadow-md tw-overflow-hidden sm:tw-rounded-lg tw-prose">
                {!! $policy !!}
            </div>
        </div>
    </div>
</x-guest-layout>
