<?php

namespace App\Livewire\Entries;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Entry;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    use WithFileUploads;

    // Common fields
    public $account_id;
    public $entry_type = 'income';
    public $payment_method = 'cash';
    public $amount;
    public $entry_date;
    public $description;

    // Check fields
    public $check_number;
    public $bank_name;
    public $bank_branch;
    public $check_account_number;
    public $check_date;
    public $check_attachment;

    // Bank transfer fields
    public $from_account_id;
    public $to_account_id;
    public $transfer_date;
    public $reference_number;

    protected function rules()
    {
        $rules = [
            'account_id' => 'required',
            'entry_type' => 'required|in:income,expense',
            'payment_method' => 'required|in:cash,bank_transfer,check',
            'amount' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
        ];

        if ($this->payment_method === 'check') {
            $rules += [
                'check_number' => 'required',
                'bank_name' => 'required',
                'check_date' => 'required|date',
            ];
        }

        if ($this->payment_method === 'bank_transfer') {
            $rules += [
                'from_account_id' => 'required',
                'to_account_id' => 'required|different:from_account_id',
                'transfer_date' => 'required|date',
            ];
        }

        return $rules;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {

            $entry = Entry::create([
                'tenant_id' => tenant_id(),
                'account_id' => $this->account_id,
                'user_id' => auth()->id(),
                'entry_type' => $this->entry_type,
                'payment_method' => $this->payment_method,
                'amount' => $this->amount,
                'entry_date' => $this->entry_date,
                'description' => $this->description,
            ]);

            // CHECK
            if ($this->payment_method === 'check') {
                $path = $this->check_attachment
                    ? $this->check_attachment->store('checks', 'public')
                    : null;

                $entry->checkDetail()->create([
                    'check_number' => $this->check_number,
                    'bank_name' => $this->bank_name,
                    'bank_branch' => $this->bank_branch,
                    'account_number' => $this->check_account_number,
                    'check_date' => $this->check_date,
                    'attachment' => $path,
                ]);
            }

            // BANK TRANSFER
            if ($this->payment_method === 'bank_transfer') {
                $entry->bankTransferDetail()->create([
                    'from_account_id' => $this->from_account_id,
                    'to_account_id' => $this->to_account_id,
                    'transfer_date' => $this->transfer_date,
                    'reference_number' => $this->reference_number,
                ]);
            }
        });

        session()->flash('success', 'Entry created successfully');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.entries.form', [
            'accounts' => Account::all(),
        ]);
    }
}

