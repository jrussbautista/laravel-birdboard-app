


<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1>Project: {{ $project->title }}</h1>
                     <div>{{ $project->description }}</div>                
                     @forelse ($project->tasks as $task)
                        <div class="flex mb-2">
                            <form method="POST" action="{{ $task->path() }}">
                                @csrf
                                @METHOD('PATCH')
                                <input name="body" value="{{ $task->body }}" class="{{ $task->completed ? 'text-gray-500' : '' }}">
                                <input type="checkbox" name="completed" onchange="this.form.submit()"
                                    {{ $task->completed ? 'checked' : ''}}
                                    >
                            </form>
                        </div>
                    @empty
                        <div>
                            <p>Add new task</p>
                        </div>
                     @endforelse

                     <form action="{{ $project->path() }}/tasks" method="POST">
                        @csrf
                        <div>
                            <textarea name="body" placeholder="Add task..."></textarea>
                        </div>
                        <div>
                            <button type="submit">Add</button>
                        </div>
                    </form>

                    <div>
                        <p>Notes:</p>
                        <textarea>{{ $project->notes }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


