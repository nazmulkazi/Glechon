<x-jet-form-section submit="createTeam">
    <x-slot name="title">
        {{ __('Team Details') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Create a new team to collaborate with others on projects.') }}
    </x-slot>

    <x-slot name="form">
        <div class="tw-col-span-6">
            <x-jet-label value="{{ __('Team Owner') }}" />

            <div class="tw-flex tw-items-center tw-mt-2">
                <img class="tw-w-12 tw-h-12 tw-rounded-full tw-object-cover" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}">

                <div class="tw-ml-4 tw-leading-tight">
                    <div>{{ $this->user->name }}</div>
                    <div class="tw-text-gray-700 tw-text-sm">{{ $this->user->email }}</div>
                </div>
            </div>
        </div>

        <div class="tw-col-span-6 sm:tw-col-span-4">
            <x-jet-label for="name" value="{{ __('Team Name') }}" />
            <x-jet-input id="name" type="text" class="tw-mt-1 tw-block tw-w-full" wire:model.defer="state.name" autofocus />
            <x-jet-input-error for="name" class="tw-mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-button>
            {{ __('Create') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
