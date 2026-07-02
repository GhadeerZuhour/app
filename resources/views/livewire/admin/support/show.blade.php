<div class="container py-4">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h4 class="mb-1">{{ $ticket->subject }}</h4>
            <div class="text-muted small">
                Type: {{ strtoupper($ticket->type) }} |
                Status: {{ strtoupper($ticket->status) }} |
                Owner: {{ $ticket->owner?->email ?? '—' }}
            </div>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" wire:click="assignToMe">Assign to me</button>

            <select class="form-select form-select-sm" style="width:160px" wire:model="status">
                <option value="new">NEW</option>
                <option value="in_progress">IN PROGRESS</option>
                <option value="resolved">RESOLVED</option>
            </select>
            <button class="btn btn-sm btn-dark" wire:click="updateStatus">Save</button>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="mb-3">
                <strong>Description</strong>
                <div class="mt-1">{{ $ticket->description ?? '—' }}</div>
            </div>

            <hr>

            <strong>Conversation</strong>
            <div class="mt-2">
                @forelse($ticket->messages as $m)
                    <div class="border rounded p-2 mb-2">
                        <div class="small text-muted">
                            {{ $m->senderLabel() }} • {{ $m->created_at->format('Y-m-d H:i') }}
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
            <textarea class="form-control" rows="3" wire:model.defer="reply" placeholder="Write a reply..."></textarea>
            @error('reply') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

            <div class="mt-2">
                <button class="btn btn-primary" wire:click="sendReply">Send</button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.support.index') }}">Back</a>
            </div>
        </div>
    </div>
</div>