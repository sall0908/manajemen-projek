<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,user_id',
        ]);

        // Gunakan kolom yang benar pada tabel cards: card_title
        $card = Card::create([
            'project_id'  => $projectId,
            'card_title'  => $request->title,
            'description' => $request->description,
            'status'      => 'todo',
            'created_by'  => auth()->user()->user_id,
        ]);

        // Attach the user to the card
        $card->assignedUsers()->attach($request->user_id, ['assigned_at' => now()]);

        return back()->with('success', 'Card created successfully.');
    }
}