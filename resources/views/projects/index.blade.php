@extends ( 'layouts.app')

@section('content')

    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-center w-full">
            <h2 class="text-gray-600">My Projects</h2>
            <a href="/projects/create" >New Project</a>
        </div>
    </header>

    <main class="flex flex-wrap -mx-3">
        @forelse($projects as $project)
        <div class="w-1/3 px-3 pb-6">
            <div class="bg-white mr-4 p-5 rounded shadow" style="height: 200px;">
                <h3 class="text-xl py-4"> {{ $project->title }} </h3>
                <div class="text-gray-600"> {{ Str::limit($project->description, 100) }} </div>
            </div> 
        </div>
        @empty
            <div>No projects yet.</div>
        @endforelse
    </main>

@endsection