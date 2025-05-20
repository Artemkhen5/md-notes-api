<?php

namespace App\Domain\Notes;

use App\Domain\Notes\Repositories\NoteRepository;
use App\Domain\Notes\Requests\StoreNoteRequest;
use App\Domain\Notes\Requests\UpdateNoteRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct(private NoteRepository $repository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json($request->user()->notes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        $noteData = $request->validated();
        return response()->json($this->repository->store($noteData, $request->user()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $noteData = $request->validated();
        return response()->json($this->repository->update($noteData, $note));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        $note->delete();
        return response()->json(
            ['message' => 'Note has been deleted']
        );
    }
}
