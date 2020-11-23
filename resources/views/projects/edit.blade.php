<?php
    use App\Models\Project;
?>
@extends ( 'layouts.app')

@section('content')
<div class="lg:w-1/2 lg:mx-auto bg-white p-6 md:px-16 rounded shadow">
    <h1 class="text-2xl font-normal mb-10 text-center">Edit your project</h1> 
    <form action="{{$project->path()}}" method="post">
    @method('PATCH')
    @include('projects._form', [
        'buttonText' => 'Edit Project'
        ])
    </form>  
</div>
@endsection