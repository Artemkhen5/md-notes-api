<?php

namespace App\Domain\Notes\Repositories;

use App\Domain\Notes\Note;
use App\Models\User;

class NoteRepository
{
    public function store(array $data, User $user): Note
    {
        $note = $user->notes()->create($data);
        return $note;
    }

    public function update(array $data, Note $note): Note
    {
        $note->update($data);
        return $note->fresh();
    }
}
