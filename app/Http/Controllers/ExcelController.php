<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ExcelExport;
use App\Imports\ExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use Response;

class ExcelController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportxlsx()
    {
        return Excel::download(new ExcelExport, date('Y-m-d-H-i-s') . '.xlsx');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportcsv()
    {
        return Excel::download(new ExcelExport, date('Y-m-d-H-i-s') . '.csv');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function import(Request $request)
    {
        if ($request->has('fileupload'))
        {
            $uploadfile = $request->file('fileupload');
            $new_file_name = rand() . '.' . $uploadfile->getClientOriginalExtension();
            $file_path = realpath($uploadfile->move(public_path('storage'), $new_file_name));
            Excel::import(new ExcelImport, $file_path);

            $result = ['message' => 'import complete'];

        } else {
            $result = ['message' => 'not upload'];
        }
        return Response::json($result);
    }
}
