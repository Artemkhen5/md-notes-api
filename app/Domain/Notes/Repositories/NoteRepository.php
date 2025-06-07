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

    public function storeFromFile(array $data, User $user): Note
    {
        $content = file_get_contents($data['file']);
        $note = $user->notes()->create([
            'title' => $data['title'],
            'content' => $content,
        ]);
        return $note;
    }
}
