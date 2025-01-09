<?php

namespace App\Livewire;

use App\Filament\Imports\EmployeeImporter;
use App\Models\Employee;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Emp extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $var = 'worood';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function changeV()
    {
        $this->var = 'john';
        $this->dispatch('refreshComponent');
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make('import')
                    ->importer(EmployeeImporter::class),

            ])->striped()
            ->heading('Employees')
            ->query(Employee::where('name', 'like', $this->var))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('national_id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('join_date'),
                Tables\Columns\TextColumn::make('salary')
                    ->searchable()
                    ->sortable(),
            ]);

    }

    protected function getTableQuery(): Builder
    {
        if (! $this->showSearch) {
            $this->query = ''; // query
        } else {
            $this->query = ''; // another query
        }

        return $this->query;
    }

    public function render()
    {
        return view('livewire.emp');
    }
}
