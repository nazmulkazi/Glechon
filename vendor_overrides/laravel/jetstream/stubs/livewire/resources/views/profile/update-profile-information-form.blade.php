<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="tw-col-span-6 sm:tw-col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="tw-hidden"
                            wire:model="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-jet-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="tw-mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="tw-rounded-full tw-h-20 tw-w-20 tw-object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="tw-mt-2" x-show="photoPreview" style="display: none;">
                    <span class="tw-block tw-rounded-full tw-w-20 tw-h-20 tw-bg-cover tw-bg-no-repeat tw-bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-jet-secondary-button class="tw-mt-2 tw-mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-jet-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-jet-secondary-button type="button" class="tw-mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-jet-secondary-button>
                @endif

                <x-jet-input-error for="photo" class="tw-mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="tw-col-span-6 sm:tw-col-span-4">
            <x-jet-label for="name" value="{{ __('Name') }}" />
            <x-jet-input id="name" type="text" class="tw-mt-1 tw-block tw-w-full" wire:model.defer="state.name" autocomplete="name" />
            <x-jet-input-error for="name" class="tw-mt-2" />
        </div>

        <!-- Email -->
        <div class="tw-col-span-6 sm:tw-col-span-4">
            <x-jet-label for="email" value="{{ __('Email') }}" />
            <x-jet-input id="email" type="email" class="tw-mt-1 tw-block tw-w-full" wire:model.defer="state.email" />
            <x-jet-input-error for="email" class="tw-mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="tw-text-sm tw-mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="tw-underline tw-text-sm tw-text-gray-600 hover:tw-text-gray-900" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p v-show="verificationLinkSent" class="tw-mt-2 tw-font-medium tw-text-sm tw-text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="tw-mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
