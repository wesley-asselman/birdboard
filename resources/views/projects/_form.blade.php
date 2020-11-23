@csrf
    <div class="field mb-6">
        <div>
            <label for="title" class="label text-sm mb-2 block">Title</label>
            <div class="control">
                <input type="text" name="title" class="input bg-transparent border border-grey-light rounded p-2 text-xs w-full" value="{{ $project->title }}" required>
            </div>
        </div>
    </div>
    <div class="field mb-6">
        <div class="control">
            <div>
                <label for="title" class="label text-sm mb-2 block">Description</label>
                <textarea rows="10" class="textarea bg-transparent border border-grey-light rounded p-2 text-xs w-full" name="description" required>{{ $project->description }}</textarea>
            </div>
        </div>
    </div> 
    
        <div>
       
            <button class="button" type="submit">{{ $buttonText }}</button>
            <a href="{{ $project->path() }}">Cancel</a>
        </div> 
   @if ($errors->any())
        <div class="field mt-6" >  
        
            @foreach($errors->all() as $error)
                <li class="text-sm text-red-500"> {{$error}} </li>
            @endforeach
     
        </div>   
    @endif