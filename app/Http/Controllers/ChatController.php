<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Property;
use App\Notifications\NewMessageNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $conversations = Conversation::query()
            ->where('buyer_id', $user->id)
            ->orWhere('agent_id', $user->id)
            ->with(['property.images', 'buyer', 'agent'])
            ->latest('last_message_at')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    public function show(Request $request, Conversation $conversation): View
    {
        $this->ensureParticipant($request, $conversation);

        $conversation->load(['property', 'buyer', 'agent', 'messages.sender']);

        // Mark unread messages from the counterpart as read.
        $conversation->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('chat.show', compact('conversation'));
    }

    public function start(Request $request, Property $property): RedirectResponse
    {
        $user = $request->user();

        if ($user->id === $property->owner_id) {
            return redirect()->route('properties.show', $property)->with('status', "You can't start a conversation about your own listing.");
        }

        $conversation = Conversation::firstOrCreate([
            'property_id' => $property->id,
            'buyer_id'    => $user->id,
            'agent_id'    => $property->owner_id,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    public function send(Request $request, Conversation $conversation): RedirectResponse
    {
        $this->ensureParticipant($request, $conversation);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = DB::transaction(function () use ($conversation, $request, $data) {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $request->user()->id,
                'body'            => $data['body'],
            ]);

            $conversation->update(['last_message_at' => now()]);

            return $message;
        });

        $recipient = $conversation->counterpartFor($request->user());
        if ($recipient) {
            $recipient->notify(new NewMessageNotification($conversation, $message));
        }

        return redirect()->route('chat.show', $conversation);
    }

    private function ensureParticipant(Request $request, Conversation $conversation): void
    {
        abort_unless(
            $request->user()->id === $conversation->buyer_id || $request->user()->id === $conversation->agent_id,
            403
        );
    }
}
