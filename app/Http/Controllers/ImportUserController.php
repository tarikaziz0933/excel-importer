<?php

namespace App\Http\Controllers;

use App\Exports\ArrayExport;
use App\Models\ImportUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;

class ImportUserController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $headers = array_map('strtolower', $rows[0]);
        unset($rows[0]);

        $validRows = [];
        $invalidRows = [];

        foreach ($rows as $index => $row) {
            $rowData = array_combine($headers, $row);
            $validator = Validator::make($rowData, [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string',
                'gender' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $invalidRows[] = array_merge($rowData, [
                    'row' => $index + 2,
                    'errors' => implode(', ', $validator->errors()->all())
                ]);
            } else {
                $validRows[] = $rowData;
            }
        }

        foreach ($validRows as $user) {
            ImportUser::create($user);
        }

        if (!empty($invalidRows)) {
            $export = new ArrayExport($invalidRows);
            Excel::store($export, 'failed_rows.xlsx', 'public');
        }

        return response()->json([
            'summary' => [
                'total_rows' => count($rows),
                'valid_rows' => count($validRows),
                'invalid_rows' => count($invalidRows)
            ],
            'download_failed_url' => count($invalidRows) > 0 ? asset('storage/failed_rows.xlsx') : null
        ]);
    }
}
