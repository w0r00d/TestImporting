<?php

namespace App\Livewire;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithFileUploads;

class Importer extends Component
{
    use WithFileUploads;

    public $fileHasHeader = true;

    public $csv_file;

    public $csv_data = [];

    public $db_fields = [];

    public $csv_header_cols = [];

    public $match_fields;

    public $data;

    public $failed = [];

    public $imported = false;

    public function rules()
    {
        return ['csv_file' => 'required|file'];
    }

    public function parseFile()
    {
        $cols = Schema::getColumnListing('employees');
        // dump($cols);
        $this->db_fields = array_diff($cols, ['id', 'created_at', 'updated_at']);
        // array_unshift($this->db_fields, 'Skip');

        $path = $this->csv_file->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $this->data = $data;

        if (count($data) > 0) {
            dump($data);
            $this->csv_header_cols = [];

            if ($this->fileHasHeader) {
                foreach ($data[0] as $key => $value) {
                    $this->csv_header_cols[] = $key;
                }
                $this->csv_data = array_slice($data, 0, 2);
            } else {
                $this->csv_data = array_slice($data, 0, 1);
            }
        } else {
            $this->dispatch('error', 'No data found in your file');
        }
        $this->match_fields = [];
    }

    public function processImport()
    {
        if (empty($this->match_fields) || count($this->match_fields) < count($this->csv_header_cols)) {
            $this->dispatch('error', __('All columns must be matched'));

            return;
        }

        $errors = [];

        foreach ($this->data as $key => $row) {
            if ($this->fileHasHeader && $key == 0) {
                continue;
            }
            $employee = new Employee;
            if (empty($this->csv_header_cols)) {
                foreach ($this->match_fields as $k => $mf) {
                    $this->csv_header_cols[$mf] = $k;
                }
            }

            foreach ($this->csv_header_cols as $header_col) {

                $field = $this->match_fields[$header_col] ?? null;
                if (is_null($field)) {
                    continue;
                }

                $value = $row[$header_col];
                if ($field == 'Skip') {
                    continue;
                }

                if ($field == 'join_date') {
                    try {
                        $value = Carbon::parse($value)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $value = null;
                    }
                }
                if (empty($field)) {
                    continue;
                } // skip headings

                $employee->$field = $value;
            }

        }
        if (empty($errors)) {

            $this->csv_file = null;
            $this->csv_data = [];
            $this->db_fields = [];
            $this->csv_header_cols = [];
            $this->match_fields = null;
            $this->data = null;
            $this->failed = [];
            $this->imported = true;
            $this->dispatch('success', __('Employees imported'));
            $this->dispatch('confetti');
            dump('success');
        } else {
            $this->failed = $errors;
            $this->dispatch('error', 'Error saving some records');
            dump('Error saving some records');
        }
    }

    public function render()
    {
        return view('livewire.importer');
    }
}
