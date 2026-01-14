<div class="p-4 bg-white rounded shadow">

    <h2 class="text-lg font-bold mb-4">New Entry</h2>

    <form wire:submit.prevent="save" class="space-y-4">

        {{-- Account --}}
        <select wire:model="account_id" class="w-full border rounded p-2">
            <option value="">Select Account</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}">{{ $account->name }}</option>
            @endforeach
        </select>

        {{-- Entry Type --}}
        <select wire:model="entry_type" class="w-full border rounded p-2">
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>

        {{-- Payment Method --}}
        <select wire:model.live="payment_method" class="w-full border rounded p-2">
            <option value="cash">Cash</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="check">Check</option>
        </select>

        {{-- Amount --}}
        <input type="number" step="0.01" wire:model="amount"
               placeholder="Amount"
               class="w-full border rounded p-2">

        {{-- Entry Date --}}
        <input type="date" wire:model="entry_date"
               class="w-full border rounded p-2">

        {{-- CHECK DETAILS --}}
        @if($payment_method === 'check')
            <input wire:model="check_number" placeholder="Check Number" class="w-full border p-2">
            <input wire:model="bank_name" placeholder="Bank Name" class="w-full border p-2">
            <input wire:model="bank_branch" placeholder="Bank Branch" class="w-full border p-2">
            <input wire:model="check_account_number" placeholder="Account Number" class="w-full border p-2">
            <input type="date" wire:model="check_date" class="w-full border p-2">
            <input type="file" wire:model="check_attachment" class="w-full border p-2">
        @endif

        {{-- BANK TRANSFER DETAILS --}}
        @if($payment_method === 'bank_transfer')
            <select wire:model="from_account_id" class="w-full border p-2">
                <option value="">From Account</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            </select>

            <select wire:model="to_account_id" class="w-full border p-2">
                <option value="">To Account</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            </select>

            <input type="date" wire:model="transfer_date" class="w-full border p-2">
            <input wire:model="reference_number" placeholder="Reference Number" class="w-full border p-2">
        @endif

        {{-- Description --}}
        <textarea wire:model="description" placeholder="Description"
                  class="w-full border rounded p-2"></textarea>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Save Entry
        </button>

    </form>
</div>
