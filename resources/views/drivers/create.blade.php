@extends('layouts.motorpool-admin')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">

                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Add New Driver</h2>
                    <p class="mt-1 text-sm text-gray-600">Create a new driver record</p>
                </div>

                <form method="POST" action="{{ route('drivers.store') }}" class="space-y-6">
                    @csrf

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- CONTACT NUMBER -->
                        <div>
                            <label for="contact_num" class="block text-sm font-medium text-gray-700">
                                Contact Number
                            </label>

                            <input type="text"
                                   name="contact_num"
                                   id="contact_num"
                                   value="{{ old('contact_num') }}"
                                   maxlength="11"
                                   oninput="validateContact(this)"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]"
                                   placeholder="09XXXXXXXXX">

                            <p id="contact_error" class="mt-1 text-sm text-red-600 hidden"></p>

                            @error('contact_num')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- EMAIL -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   value="{{ old('email') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1e6031] focus:ring-[#1e6031]">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <!-- SUBMIT -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-[#1e6031] border border-transparent rounded-md font-bold text-lg text-white uppercase tracking-widest hover:bg-[#164f2a] transition ease-in-out duration-150">
                            Create Driver
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<!-- REAL-TIME VALIDATION SCRIPT -->
<script>
function validateContact(input) {

    const errorText = document.getElementById("contact_error");

    // Remove non-numbers immediately
    input.value = input.value.replace(/[^0-9]/g, '');

    const value = input.value;

    if (value.length === 0) {
        errorText.classList.add("hidden");
        input.classList.remove("border-red-500");
        return;
    }

    if (value.length < 11) {
        errorText.textContent = "Contact number must be 11 digits.";
        errorText.classList.remove("hidden");
        input.classList.add("border-red-500");
        return;
    }

    if (!/^09\d{9}$/.test(value)) {
        errorText.textContent = "Number must start with 09.";
        errorText.classList.remove("hidden");
        input.classList.add("border-red-500");
        return;
    }

    // VALID
    errorText.classList.add("hidden");
    input.classList.remove("border-red-500");
}
</script>

@endsection
