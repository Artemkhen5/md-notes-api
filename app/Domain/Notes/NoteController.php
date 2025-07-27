<?php

namespace App\Domain\Notes;

use App\Domain\Notes\Repositories\NoteRepository;
use App\Domain\Notes\Requests\SpellCheckRequest;
use App\Domain\Notes\Requests\StoreFromFileNoteRequest;
use App\Domain\Notes\Requests\StoreNoteRequest;
use App\Domain\Notes\Requests\UpdateNoteRequest;
use App\Domain\Notes\Resources\HtmlNoteResource;
use App\Domain\Notes\Resources\NoteResource;
use App\Http\Controllers\Controller;
use App\Services\LanguageToolService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (Auth::user()->cannot('view', $note)) {
            abort(403, 'You can not view this note.');
        }
        return new NoteResource($note);
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        if (Auth::user()->cannot('update', $note)) {
            abort(403, 'You can not update this note.');
        }
        $noteData = $request->validated();
        return new NoteResource($this->repository->update($noteData, $note));
    }

    public function destroy(Note $note)
    {
        if (Auth::user()->cannot('delete', $note)) {
            abort(403, 'You can not delete this note.');
        }
        $note->delete();
        return response()->json(
            ['message' => 'Note has been deleted']
        );
    }

    public function render(Note $note)
    {
        if (Auth::user()->cannot('delete', $note)) {
            abort(403, 'You can not render this note.');
        }
        return new HtmlNoteResource($note);
    }

    public function spellCheck(Note $note, SpellCheckRequest $request, LanguageToolService $service)
    {
        $language = $request->validated()['language'] ?? '';
        $response = $service->checkGrammar($note->content, $language);
        return response()->json($response);
    }

    public function file(StoreFromFileNoteRequest $request)
    {
        $data = $request->validated();
        $note = $this->repository->storeFromFile($data, $request->user());
        return new NoteResource($note);
    }
}
