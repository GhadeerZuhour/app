<div class="container py-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h4 class="mb-1">{{ $ticket->subject }}</h4>
            <div class="text-muted small">
                Type: {{ strtoupper($ticket->type) }} |
                Status: {{ strtoupper($ticket->status) }}
            </div>
        </div>
        <a class="btn btn-outline-secondary" href="{{ route('tenant.support.index') }}">Back</a>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <strong>Conversation</strong>
            <div class="mt-2">
                @forelse($ticket->messages as $m)
                    <div class="border rounded p-2 mb-2">
                        <div class="small text-muted">
                            {{ $m->user?->name ?? 'User' }} • {{ $m->created_at?->format('Y-m-d H:i') }}
                        </div>
                        <div>{{ $m->message }}</div>
                    </div>
                @empty
                    <div class="text-muted">No messages yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <textarea class="form-control" rows="3" wire:model.defer="reply" placeholder="Write a message..."></textarea>
            @error('reply') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

            <div class="mt-2">
                <button class="btn btn-primary" wire:click="sendReply">Send</button>
            </div>
        </div>
    </div>
</div>
