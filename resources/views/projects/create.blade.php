<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="/projects">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block">Title</label>
                            <input type="text" name="title" id="title">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block">Description</label>
                            <textarea type="text" name="description" id="description"></textarea>
                        </div>
                        <div>
                            <button class="bg-gray-800 px-4 py-2 block text-white rounded" type="submit">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


