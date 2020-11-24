<div class="card mt-3">
    <ul class="text-xs list-reset">
        @foreach($project->activity as $activity)
            <li class="{{ $loop->last ? '' : 'mb-1' }}">
                @include("projects.activity.{$activity->description}")
                <span class="text-gray-600">{{ $activity->created_at->diffforhumans(null, true) }}</Span>
            </li>
        @endforeach
    </ul>
</div>