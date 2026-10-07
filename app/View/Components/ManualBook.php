<?php

namespace App\View\Components;

use App\Models\Informasi;
use Illuminate\View\Component;
use Illuminate\View\View;

class ManualBook extends Component
{
    public ?Informasi $manualBook;

    public function __construct()
    {
        $role = auth()->user()?->role;

        $this->manualBook = $role
            ? Informasi::active()
                ->forRole($role)
                ->where('jenis', 'manual_book')
                ->latest()
                ->first()
            : null;
    }

    public function render(): View
    {
        return view('components.manual-book');
    }
}