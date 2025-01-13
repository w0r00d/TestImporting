<?php

namespace App\Livewire;

// use App\Filament\Imports\PendingEmployeeImporter;
use App\Filament\Exports\EmpViewExporter;
use App\Filament\Imports\EmployeeImporter;
use App\Models\Employee;
use App\Models\PendingEmployee;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
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

    public $changeQ = false;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function changeV()
    {
        // $this->var = 'laila';

        // $this->getTableQuery();
        if ($this->changeQ) {
            $this->changeQ = false;
        } else {
            $this->changeQ = true;
        }
        $this->dispatch('refreshComponent');

    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make('import')
                    ->importer(EmployeeImporter::class)
                    ->label('Upload P Employees data'),

            ])->striped()
            ->heading('Employees')
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

            ])
            // ->query(Employee::query());
            ->modifyQueryUsing(function (Builder $query) {
                if ($this->changeQ) {

                    return PendingEmployee::getDups();
                }// $query->where('name', 'john');}

                return $query;
            })

            ->query(PendingEmployee::query())
            ->bulkActions([
                ExportBulkAction::make()
                    ->exporter(EmpViewExporter::class),
            ]);

    }

    public function clearing()
    {

        Employee::destroy(Employee::all());
    }

    public function render()
    {
        return view('livewire.emp');
    }
}
