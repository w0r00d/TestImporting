<?php

namespace App\Filament\Exports;

use App\Models\EmpView;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EmpViewExporter extends Exporter
{
    protected static ?string $model = EmpView::class;

    public static function getColumns(): array
    {
        return [
         
            ExportColumn::make('name'),
            ExportColumn::make('national_id'),
            ExportColumn::make('join_date'),
            ExportColumn::make('salary'),

        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your emp VIEW export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
