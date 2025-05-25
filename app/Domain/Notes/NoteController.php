<?php

namespace App\Domain\Notes;

use App\Domain\Notes\Repositories\NoteRepository;
use App\Domain\Notes\Requests\StoreNoteRequest;
use App\Domain\Notes\Requests\UpdateNoteRequest;
use App\Domain\Notes\Resources\HtmlNoteResource;
use App\Domain\Notes\Resources\NoteResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct(private NoteRepository $repository)
    {
    }

    public function index(Request $request)
    {
        return NoteResource::collection($request->user()->notes);
    }

    public function store(StoreNoteRequest $request)
    {
        $noteData = $request->validated();
        return new NoteResource($this->repository->store($noteData, $request->user()));
    }

    public function show(Note $note)
    {
        return new NoteResource($note);
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        $noteData = $request->validated();
        return new NoteResource($this->repository->update($noteData, $note));
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json(
            ['message' => 'Note has been deleted']
        );
    }

    public function render(Note $note)
    {
        return new HtmlNoteResource($note);
    }
}
