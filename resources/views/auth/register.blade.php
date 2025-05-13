<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4"
        x-data="{ selectedRole: '', selectedRoleName: '{{ __('Select User Role') }}' }">
            <x-input-label for="role" :value="__('Role')" />
            <div class="relative w-full">
                <x-dropdown class="w-full" align="left" width="100%">
                    <x-slot name="trigger">
                        <x-primary-button
                        class="w-full justify-between"
                        x-text="selectedRoleName"></x-primary-button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="w-full"
                        style="width: 400px; max-height: 200px; overflow-y: auto;">
                            @foreach ($roles as $role)
                                <x-dropdown-link
                                    href="#"
                                    @click.prevent="
                                        selectedRole = '{{ $role->id }}';
                                        selectedRoleName = '{{ $role->name }}';
                                    "
                                >
                                    {{ $role->name }}
                                </x-dropdown-link>
                            @endforeach
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hidden input to submit selected role -->
            <input type="hidden" name="role" :value="selectedRole">

            @error('role')
                <x-input-error :messages="$message" class="mt-2" />
            @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
