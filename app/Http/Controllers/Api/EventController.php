<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    use CanLoadRelationships;

    private array $relations = ['user', 'attendees', 'attendees.user'];

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index', 'show');
        $this->authorizeResource(Event::class, 'event');
    }

    public function index()
    {
        $query = $this->loadRelationShips(Event::query());

        return EventResource::collection($query->latest()->paginate());
    }


    public function store(Event $event, EventRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = $request->user()->id;

        $event = Event::create($data);

        return new EventResource($this->loadRelationShips($event));
    }


    public function show(Event $event)
    {
        return new EventResource($event->load('user', 'attendees'));
    }


    public function update(EventRequest $request, Event $event)
    {
        // $this->authorize('update-event', $event);

        $data = $request->validated();

        $event->update($data);

        return new EventResource($this->loadRelationShips($event));
    }


    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json(null, 204);
    }
}
