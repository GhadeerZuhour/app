<?php

namespace App\Livewire\Entries;

use App\Models\CheckDetails;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Account;
use App\Models\Entry;
use App\Models\ZeroBalance;
use App\Models\CheckDetail;

class Create extends Component
{
    use WithFileUploads;

    /** Dropdown */
    public array $accounts = [];

    /** Entry header */
    public $account_id = null;
    public string $payment_method = 'cash';
    public string $entry_date;

    /** Zero balance */
    public bool $needsZeroBalance = false;
    public $zero_amount = null;

    /** Checks flow */
    public int $checks_count = 1;
    public bool $generated = false;   // after generating checks
    public ?int $entry_id = null;

    /**
     * rows:
     * - BEFORE generate: rows[0] is template row
     * - AFTER generate: rows = list of generated check rows (each includes id)
     */
    public array $rows = [];

    public function mount(): void
    {
        $this->accounts = Account::where('tenant_id', tenant_id())->get()->toArray();
        $this->entry_date = now()->toDateString();

        // template row
        $this->rows = [];
        $this->rows[] = $this->defaultTemplateRow();
    }

    /** Default template row for check */
    private function defaultTemplateRow(): array
    {
        return [
            'customer' => '',
            'item' => '',
            'check_date' => $this->entry_date ?? now()->toDateString(),
            'check_number' => '',
            'bank_name' => '',
            'account_number' => '',
            'branch_number' => '',
            'amount' => '',
            'direction' => 'in',
            'attachment' => null,
        ];
    }

    /** Account changed -> enforce zero balance */
    public function updatedAccountId(): void
    {
        $this->checkZeroBalance();
    }

    /** Entry date changed -> if already generated, do NOT auto change. If not generated and using check, update template check_date only. */
    public function updatedEntryDate(): void
    {
        if ($this->payment_method === 'check' && !$this->generated) {
            $this->rows[0]['check_date'] = $this->entry_date;
        }
    }

    /** Payment method changed */
    public function updatedPaymentMethod(): void
    {
        // reset stage when switching method
        $this->generated = false;
        $this->entry_id = null;

        if ($this->payment_method === 'check') {
            // keep only template row
            $this->rows = [$this->defaultTemplateRow()];
            if ($this->checks_count < 1)
                $this->checks_count = 1;
        } else {
            // cash/bank: one simple row with amount
            $this->rows = [
                ['amount' => '', 'direction' => 'in']
            ];
        }
    }

    /** When user changes number of checks: we do NOT generate rows here (per your UX) */
    public function updatedChecksCount($value): void
    {
        $value = (int) $value;
        if ($value < 1)
            $value = 1;
        if ($value > 50)
            $value = 50;
        $this->checks_count = $value;
    }

    /** Check if zero balance exists for current month */
    public function checkZeroBalance(): void
    {
        $this->needsZeroBalance = false;
        $this->zero_amount = null;

        if (!$this->account_id)
            return;

        $month = now()->startOfMonth()->toDateString();

        $exists = ZeroBalance::where('tenant_id', tenant_id())
            ->where('account_id', $this->account_id)
            ->where('month', $month)
            ->exists();

        if (!$exists) {
            $this->needsZeroBalance = true;
            $this->dispatch('open-zero-modal');
        }
    }

    /** Save zero balance modal */
    public function saveZeroBalance(): void
    {
        $this->validate([
            'account_id' => 'required',
            'zero_amount' => 'required|numeric',
        ]);

        $month = now()->startOfMonth()->toDateString();

        ZeroBalance::updateOrCreate(
            [
                'tenant_id' => tenant_id(),
                'account_id' => $this->account_id,
                'month' => $month,
            ],
            [
                'zero_amount' => $this->zero_amount,
            ]
        );

        $this->needsZeroBalance = false;
        $this->dispatch('close-zero-modal');
        session()->flash('success', __('entries.zero_saved'));
    }

    /**
     * STEP 1 (Checks):
     * User enters template + checks_count, then clicks "Generate & Save"
     * -> creates Entry + N CheckDetail rows
     * -> loads generated rows to allow editing
     */
    public function generateAndSaveChecks(): void
    {
        $this->validate([
            'account_id' => 'required',
            'payment_method' => 'required|in:cash,bank,check',
            'entry_date' => 'required|date',
            'checks_count' => 'required|integer|min:1|max:50',

            'rows.0.check_number' => 'required|string|max:255',
            'rows.0.bank_name' => 'required|string|max:255',
            'rows.0.amount' => 'required|numeric|min:0.01',
            'rows.0.check_date' => 'required|date',
            'rows.0.direction' => 'required|in:in,out',
            'rows.0.attachment' => 'nullable|file|max:5120',
        ]);

        if ($this->needsZeroBalance) {
            $this->dispatch('open-zero-modal');
            return;
        }

        DB::transaction(function () {
            $template = $this->rows[0];

            $baseDate = Carbon::parse($this->entry_date); // monthly plan is based on entry_date
            $baseNumber = (string) $template['check_number'];

            // attachment for template (optional) - same file can be reused? usually no.
            // We'll attach only to first check if uploaded.
            $firstAttachmentPath = null;
            if (!empty($template['attachment'])) {
                $firstAttachmentPath = $template['attachment']->store('checks', 'public');
            }

            $entry = Entry::create([
                'tenant_id' => tenant_id(),
                'account_id' => $this->account_id,
                'user_id' => Auth::id(),
                'payment_method' => 'check',
                'entry_date' => $this->entry_date,
                'total_amount' => (float) $template['amount'] * (int) $this->checks_count,
            ]);

            for ($i = 0; $i < $this->checks_count; $i++) {
                $date = $baseDate->copy()->addMonths($i)->toDateString();

                $entry->check()->create([
                    'customer' => $template['customer'] ?? null,
                    'item' => $template['item'] ?? null,
                    'check_number' => $this->incrementCheckNumber($baseNumber, $i),
                    'bank_name' => $template['bank_name'],
                    'account_number' => $template['account_number'] ?? null,
                    'branch_number' => $template['branch_number'] ?? null,
                    'amount' => $template['amount'],
                    'direction' => $template['direction'],
                    'check_date' => $date,
                    'attachment' => $i === 0 ? $firstAttachmentPath : null,
                ]);
            }

            $this->entry_id = $entry->id;
        });

        $this->loadGeneratedChecks();
    }

    /**
     * Load generated checks into $rows for editing (step 2)
     */
    public function loadGeneratedChecks(): void
    {
        if (!$this->entry_id)
            return;

        $entry = Entry::with('check')->findOrFail($this->entry_id);

        $this->generated = true;

        $this->rows = $entry->check->map(function ($c) {
            return [
                'id' => $c->id,
                'customer' => $c->customer,
                'item' => $c->item,
                'check_date' => $c->check_date,
                'check_number' => $c->check_number,
                'bank_name' => $c->bank_name,
                'account_number' => $c->account_number,
                'branch_number' => $c->branch_number,
                'amount' => $c->amount,
                'direction' => $c->direction,
                'attachment' => $c->attachment,
            ];
        })->toArray();
    }

    /**
     * STEP 2:
     * User edits generated check_date/check_number then click "Update & Finish"
     */
    public function updateGeneratedChecks()
    {
        $this->validate([
            'entry_id' => 'required|integer',
            'rows' => 'required|array|min:1',
            'rows.*.id' => 'required|integer',
            'rows.*.check_date' => 'required|date',
            'rows.*.check_number' => 'required|string|max:255',
            'rows.*.amount' => 'required|numeric|min:0.01',
            'rows.*.direction' => 'required|in:in,out',
            'rows.*.attachment_file' => 'nullable|file|max:5120',
        ]);

        DB::transaction(function () {
            foreach ($this->rows as $row) {
                $data = [
                    'check_date' => $row['check_date'],
                    'check_number' => $row['check_number'],
                    'customer' => $row['customer'] ?? null,
                    'item' => $row['item'] ?? null,
                    'bank_name' => $row['bank_name'] ?? null,
                    'account_number' => $row['account_number'] ?? null,
                    'branch_number' => $row['branch_number'] ?? null,
                    'amount' => $row['amount'],
                    'direction' => $row['direction'],
                ];

                if (!empty($row['attachment_file'])) {
                    $data['attachment'] = $row['attachment_file']->store('checks', 'public');
                }

                CheckDetails::where('id', $row['id'])
                    ->where('entry_id', $this->entry_id)
                    ->update($data);
            }

        });

        session()->flash('success', __('entries.entry_saved'));
        return redirect()->route('entries.index');
    }

    /**
     * For CASH/BANK (simple):
     * You can expand later. For now, one amount.
     */
    public function saveSimple()
    {
        $this->validate([
            'account_id' => 'required',
            'payment_method' => 'required|in:cash,bank',
            'entry_date' => 'required|date',
            'rows.0.amount' => 'required|numeric|min:0.01',
        ]);

        if ($this->needsZeroBalance) {
            $this->dispatch('open-zero-modal');
            return;
        }

        DB::transaction(function () {
            Entry::create([
                'tenant_id' => tenant_id(),
                'account_id' => $this->account_id,
                'user_id' => Auth::id(),
                'payment_method' => $this->payment_method,
                'entry_date' => $this->entry_date,
                'total_amount' => (float) $this->rows[0]['amount'],
            ]);
        });

        session()->flash('success', __('entries.entry_saved'));
        return redirect()->route('entries.index');
    }

    /** Increment check number */
    private function incrementCheckNumber(string $base, int $offset): string
    {
        if (trim($base) === '')
            return '';

        // numeric with leading zeros
        if (ctype_digit($base)) {
            $len = strlen($base);
            $num = (int) $base;
            $new = (string) ($num + $offset);
            return str_pad($new, $len, '0', STR_PAD_LEFT);
        }

        // pattern like CHK-0001
        if (preg_match('/^(.*?)(\d+)$/', $base, $m)) {
            $prefix = $m[1];
            $digits = $m[2];
            $len = strlen($digits);
            $num = (int) $digits;

            $newDigits = str_pad((string) ($num + $offset), $len, '0', STR_PAD_LEFT);
            return $prefix . $newDigits;
        }

        // fallback
        return $base;
    }

    public function render()
    {
        return view('livewire.entries.create', [
            'accounts' => $this->accounts,
        ]);
    }
}
