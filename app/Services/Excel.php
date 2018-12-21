<?php

namespace Piemeram\Services;

use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column\Rule;
use PhpOffice\PhpSpreadsheet\Worksheet\AutoFilter\Column;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class Excel implements
    WithColumnFormatting,
    FromCollection,
    ShouldAutoSize,
    WithHeadings,
    WithEvents,
    WithTitle,
    WithMapping
{
    use
        RegistersEventListeners,
        Exportable;

    protected $title;
    protected static $headings;
    protected static $collection;

    public function __construct(
        $title = '',
        $headings = [],
        $collection = null
    ) {
        $this->title = $title;
        self::$headings = $headings;
        self::$collection = $collection;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return array_keys(self::$headings);
    }

    public function columnFormats(): array
    {
        $validFormats = [
            null => 'General',
            'text' => '@',
            'number' => '0',
            'date' => 'yyyy-mm-dd',
            'datetime' => 'yyyy-mm-dd HH:mm:ss',
        ];
        $formats = [];
        foreach ($this->formats() as $i => $format) {
            $formats[range('A', 'Z')[$i]] =
                array_key_exists($format, $validFormats)
                    ? $validFormats[$format]
                    : $validFormats[null];
        }

        return $formats;
    }

    public function collection()
    {
        return self::$collection;
    }

    public function map($data): array
    {
        foreach (array_merge(
            array_keys($this->formats(), 'date'),
            array_keys($this->formats(), 'datetime')
        ) as $column) {
            if ($data[$column]) {
                $data[$column] = Date::dateTimeToExcel(Carbon::parse($data[$column]));
            }
        }
        foreach (array_keys($this->formats(), 'number') as $column) {
            $data[$column] = "$data[$column]";
        }
        return $data;
    }

    public static function afterSheet(AfterSheet $event)
    {
        $sheet = $event->sheet;
        $sheet->freezePane('A2');
        $sheet->getTabColor()->setRGB('209CEE');
        $range = 'A1:' . range('A', 'Z')[count(self::$headings) - 1];
        $sheet->getStyle($range . 1)->getFont()->setBold(true);
        $sheet->setAutoFilter($range . (count(self::$collection) + 1));
        self::setFilters($sheet->getAutoFilter());
    }

    public static function beforeExport(BeforeExport $event)
    {
        $writer = $event->writer->getProperties();
        $author = config('app.name');
        $writer->setCompany($author);
        $writer->setLastModifiedBy($author);
        $writer->setCreator($author);
    }

    protected function formats(): array
    {
        return collect(array_values(self::$headings))
            ->transform(function ($options) {
                if (!$options or !array_key_exists('format', $options)) {
                    return null;
                }
                return $options['format'];
            })
            ->toArray();
    }

    protected static function filters(): array
    {
        return collect(array_values(self::$headings))
            ->transform(function ($options, $i) {
                if ($options and array_key_exists('filter', $options)) {
                    return $options['filter'];
                }
            })
            ->reject(function ($filter) {
                return empty($filter);
            })
            ->toArray();
    }

    protected static function setFilters($autofilter)
    {
        foreach (self::filters() as $i => $rules) {
            $column = range('A', 'Z')[$i];
            switch (key($rules)) {
                case 'contains':
                    if (!$rules['contains']) { break; }

                    $column = $autofilter->getColumn($column);
                    $column->setFilterType(Column::AUTOFILTER_FILTERTYPE_CUSTOMFILTER);
                    foreach ($rules['contains'] as $rule) {
                        $column->createRule()
                            ->setRuleType(Rule::AUTOFILTER_RULETYPE_CUSTOMFILTER)
                            ->setRule(Rule::AUTOFILTER_COLUMN_RULE_EQUAL, "*$rule*");
                    }

                    break;
                case 'equal':
                    if (!$rules['equal']) { break; }

                    $column = $autofilter->getColumn($column);
                    $column->setFilterType(Column::AUTOFILTER_FILTERTYPE_FILTER);
                    $column->createRule()->setRule(Rule::AUTOFILTER_COLUMN_RULE_EQUAL, $rules['equal']);

                    break;
                case 'betweenDates':
                    if (!$rules['betweenDates']) { break; }
                    if (!$rules['betweenDates'][0]) { break; }
                    if (!$rules['betweenDates'][1]) { break; }

                    $column = $autofilter->getColumn($column);
                    $column->setFilterType(Column::AUTOFILTER_FILTERTYPE_CUSTOMFILTER);
                    $column->createRule()
                        ->setRule(
                            Rule::AUTOFILTER_COLUMN_RULE_LESSTHANOREQUAL,
                            Date::dateTimeToExcel(Carbon::parse($rules['betweenDates'][0]))
                        )
                        ->setRuleType(Rule::AUTOFILTER_RULETYPE_CUSTOMFILTER);
                    $column->setJoin(Column::AUTOFILTER_COLUMN_JOIN_AND);
                    $column->createRule()
                        ->setRule(
                            Rule::AUTOFILTER_COLUMN_RULE_GREATERTHANOREQUAL,
                            Date::dateTimeToExcel(Carbon::parse($rules['betweenDates'][1]))
                        )
                        ->setRuleType(Rule::AUTOFILTER_RULETYPE_CUSTOMFILTER);

                    break;
            }
        }
        $autofilter->showHideRows();
    }
}
