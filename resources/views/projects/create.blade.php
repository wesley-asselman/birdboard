@extends ( 'layouts.app')

@section('content')

    
    <h1>Create a project</h1>
    <form action="/projects" method="post">
    @csrf
        <div>
            <label for="title">Title</label>
            <input type="text" name="title" placeholder="title">
        </div>

        <div>
            <label for="title">Description</label>
            <textarea name="description" placeholder="Description..."></textarea>
        </div>

        <div>
            <button class="button" type="submit">Create Project</button>
            <a href="/projects">Cancel</a>
        </div>
    </form>

@endsection