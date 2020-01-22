<?php
namespace Platform\Exports;

use App\User;

use Carbon\Carbon;

use PhpOffice\PhpSpreadsheet\Style;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements ShouldAutoSize, FromCollection, WithColumnFormatting, WithHeadings, WithMapping
{
    use Exportable;

    private $columns = [
      'name',
      'email',
      'active',
      'created_at',
      'logins',
      'last_login'
    ];

    public function headings(): array
    {
      return [
        trans('app.name'),
        trans('app.email'),
        trans('app.active'),
        trans('app.created'),
        trans('app.logins'),
        trans('app.last_login')
      ];
    }

    public function map($row): array
    {
      return [
        $row->name,
        $row->email,
        $row->active,
        Carbon::parse($row->created_at, config('app.timezone'))->setTimezone(auth()->user()->getTimezone())->format('Y-m-d H:i'),
        ($row->logins === 0) ? '0' : $row->logins,
        ($row->last_login !== null) ? Carbon::parse($row->last_login, config('app.timezone'))->setTimezone(auth()->user()->getTimezone())->format('Y-m-d H:i') : null
      ];
    }

    public function columnFormats(): array
    {
        return [
          'E' => Style\NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::withoutGlobalScopes()->where('created_by', auth()->user()->id)->get();
    }
}