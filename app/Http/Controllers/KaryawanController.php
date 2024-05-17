<?php

namespace App\Http\Controllers;

use Storage;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Mpdf\Mpdf;





class KaryawanController extends Controller
{

    public function index(Request $request)
    {
        try {
            $defaultLimit = $request->limit ?? 5;
            $defaultPage = $request->page ?? 1;
            $offset = ($defaultPage - 1) * $defaultLimit;



            $data = Karyawan::query()->orderBy('created_at', 'DESC');


            if (isset($request->id)) {
                $data = $data->where('id', $request->id);
            }

            $search = $request->search;
            if (isset($request->search)) {
                $data = $data->where(function ($query) use ($search) {
                    $query->where('nama', 'LIKE', "%" . $search . "%")
                        ->orWhere('nomor', 'LIKE', "%" . $search . "%");
                });
            }



            $count = $data->count();
            if ($defaultLimit != -1) {
                $data = $data->offset($offset)->limit($defaultLimit);
            }
            $result = $data->get()->toArray();

            return response()->json(['status_code' => 200, 'success' => true, 'data' => $result, 'page' => intval($defaultPage), 'limit' => intval($defaultLimit), 'count' => $count], 200);
        } catch (\Exception $e) {

            return response()->json(['status_code' => 400, 'success' => false, 'message' => $e->getMessage()], 400);
        }
    }


    public function store(Request $request)
    {
        try {

            $validator = Facades\Validator::make($request->all(), [
                'nama' => 'required|max:50|string',
                'nomor' => 'required|max:20|string',
                'jabatan' => 'required|max:50|string',
                'departement' => 'required|max:50',
                'foto' => 'nullable|max:100',
                'tanggal_masuk' => 'required|date',
                'status' => 'required|in:kontrak,tetap,probation'
            ]);

            if ($validator->fails()) {
                return response()->json(['status_code' => 400, 'success' => false, 'message' => $validator->errors()], 400);
            }

            $karyawan = Karyawan::where('nomor', $request->nomor)->first();

            if ($karyawan) {
                return response()->json(['status_code' => 400, 'success' => false, 'message' => 'Nomor karyawan sudah terdaftar.'], 400);
            }

            $data = Karyawan::create([
                'nama' => $request->nama,
                'nomor' => $request->nomor,
                'jabatan' => $request->jabatan,
                'departement' => $request->departement,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status' => $request->status,
                'foto' => $request->foto,
            ]);

            return response()->json(['status_code' => 201, 'success' => true, 'data' => $data], 201);
        } catch (\Exception $e) {
            return response()->json(['status_code' => 400, 'success' => false, 'message' => $e->getMessage()], 400);
        }
    }



    public function update(Request $request, string $id)
    {
        try {

            $validator = Facades\Validator::make($request->all(), [
                'nama' => 'required|max:50|string',
                'nomor' => 'required|max:20|string',
                'jabatan' => 'required|max:50|string',
                'departement' => 'required|max:50',
                'foto' => 'nullable|max:100',
                'tanggal_masuk' => 'required|date',
                'status' => 'required|in:kontrak,tetap,probation'
            ]);

            if ($validator->fails()) {
                return response()->json(['status_code' => 400, 'success' => false, 'message' => $validator->errors()], 400);
            }

            $karyawan = Karyawan::where('nomor', $request->nomor)->first();

            if ($karyawan && $karyawan->id != $id) {
                return response()->json(['status_code' => 400, 'success' => false, 'message' => 'Nomor karyawan sudah terdaftar.'], 400);
            }

            $data = Karyawan::where('id', $id)->update([
                'nama' => $request->nama,
                'nomor' => $request->nomor,
                'jabatan' => $request->jabatan,
                'departement' => $request->departement,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status' => $request->status,
                'foto' => $request->foto,
            ]);

            return response()->json(['status_code' => 200, 'success' => true, 'message' => 'sukses update data.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status_code' => 400, 'success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function destroy(string $id)
    {
        try {

            Karyawan::where('id', $id)->delete();

            return response()->json(['status_code' => 200, 'success' => true, 'message' => 'sukses delete data.'], 200);
        } catch (\Exception $e) {
            return response()->json(['status_code' => 400, 'success' => false, 'message' => $e->getMessage()], 400);
        }
    }


    public function import(Request $request)
    {


        $validator = Facades\Validator::make(request()->all(), [
            'file' => 'required|file',
        ])->setAttributeNames([
            'file' => 'File',
        ]);

        if ($validator->fails()) {
            return response()->json(['status_code' => 400, 'success' => false, 'messages' => $validator->messages()], 400);
        }

        $file = $request->file('file');
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $size = $size / 1024 / 1024;


        if ($mimeType != "text/csv") {
            return response()->json(['status_code' => 400, 'success' => false, 'messages' => 'Please choose .csv file'], 400);
        }
        if ($size > 2) {
            return response()->json(['status_code' => 400, 'success' => false, 'messages' => 'Max size 2MB'], 400);
        }


        $path = Storage::disk('local')->putFile(
            'excelImport',
            $request->file('file')
        );
        // dd($path);
        $exist = Storage::disk('local')->exists($path);

        if ($exist) {
            $at = date("Y-m-d H:i:s");
            DB::beginTransaction();
            try {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv;;
                $spreadsheet = $reader->setReadDataOnly(true)->load(storage_path("app/" . $path));
                $sheetData = $spreadsheet->getSheet(0)->toArray();


                $excelRowValidation = [];
                foreach ($sheetData as $k => $v) {
                    if ($k >= 1) {


                        if ($v[0] != null) {
                            $insert = [];
                            $insert['nama'] = $v[0];
                            $insert['nomor'] = $v[1];
                            $insert['jabatan'] = $v[2];
                            $insert['departement'] = $v[3];
                            $insert['tanggal_masuk'] = $v[4];
                            $insert['foto'] = $v[5];
                            $insert['status'] = $v[6];


                            $validator = Facades\Validator::make($insert, [
                                'nama' => ['required', 'max:50', 'string'],
                                'nomor' => ['required', 'max:20', 'string'],
                                'jabatan' => ['required', 'max:50', 'string'],
                                'departement' => ['required', 'max:50'],
                                'foto' => ['nullable', 'max:150'],
                                'tanggal_masuk' => ['required', 'date'],
                                'status' => ['required', 'in:kontrak,tetap,probation']
                            ]);


                            $db =
                                DB::table(DB::raw("karyawans AS e"))
                                ->whereRaw("e.nomor='" . $insert['nomor'] . "'")
                                ->whereNull("e.deleted_at")
                                ->limit(1);
                            $db = $db->get();
                            $total = count($db);
                            // dd($total);
                            if ($total > 0) {
                                $excelRowValidation[] = [
                                    'excelRow' => $k + 1,
                                    'excelValidation' => 'Nomor karyawan sudah terdaftar.'
                                ];
                            }
                            if ($validator->fails()) {
                                $excelRowValidation[] = [
                                    'excelRow' => $k + 1,
                                    'excelValidation' => $validator->messages()
                                ];
                            } else {
                                $insert['tanggal_masuk'] = date("Y-m-d", strtotime($insert['tanggal_masuk']));
                                $insert['created_at'] = $at;
                                $insert['updated_at'] = $at;
                                DB::table('karyawans')->insert($insert);
                            }
                        }
                    }
                }

                $cancelIfNotValid = 1;
                if ($cancelIfNotValid == 1) {
                    if (count($excelRowValidation) > 0) {
                        return response()->json([
                            'status_code' => 400,
                            'success' => false,
                            'messages' => "Excel value is not valid.",
                            'excel_error' => true,
                            'excel_error_array' => $excelRowValidation
                        ], 400);
                    } else {
                        DB::commit();
                        return response()->json([
                            'status_code' => 200,
                            'success' => true,
                            'messages' => "Import Success.",
                            'excel_error' => false
                        ], 200);
                    }
                } else {
                    if (count($excelRowValidation) > 0) {
                        DB::commit();
                        return response()->json([
                            'status_code' => 200,
                            'success' => true,
                            'messages' => "Import Success With Error.",
                            'excel_error' => true,
                            'excel_error_array' => $excelRowValidation
                        ], 200);
                    } else {
                        DB::commit();
                        return response()->json([
                            'status_code' => 200,
                            'success' => true,
                            'messages' => "Import Success.",
                            'excel_error' => false
                        ], 200);
                    }
                }
            } catch (\Exception $ex) {
                throw $ex;
                DB::rollback();
                return response()->json([
                    'status_code' => 400,
                    'success' => false,
                    'messages' => "Import Success.",
                    'excel_error' => false
                ], 400);
            }

            Storage::disk('local')->delete($path);
        } else {
            return response()->json([
                'status_code' => 400,
                'success' => false,
                'messages' => 'Form data is not valid.',
                'form_error' => true,
                'form_error_array' => 'Upload file not succee, please try again.'
            ], 400);
        }
    }

    public function exportCsv(Request $request)
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Nomor');
        $sheet->setCellValue('D1', 'Jabatan');
        $sheet->setCellValue('E1', 'Departement');
        $sheet->setCellValue('F1', 'Tanggal Masuk');
        $sheet->setCellValue('G1', 'Foto');
        $sheet->setCellValue('H1', 'Status');

        $data = Karyawan::get();

        foreach ($data as $key => $value) {
            $loop = $key + 1;
            $rows = $key + 2;
            $rowA = 'A' . $rows;
            $rowB = 'B' . $rows;
            $rowC = 'C' . $rows;
            $rowD = 'D' . $rows;
            $rowE = 'E' . $rows;
            $rowF = 'F' . $rows;
            $rowG = 'G' . $rows;
            $rowH = 'H' . $rows;
       
          

         
            $sheet->setCellValue($rowA, $loop);
            $sheet->setCellValue($rowB, $value->nama);
            $sheet->setCellValue($rowC, $value->nomor);
            $sheet->setCellValue($rowD, $value->jabatan);
            $sheet->setCellValue($rowE, $value->departement);
            $sheet->setCellValue($rowF, $value->tanggal_masuk);
            $sheet->setCellValue($rowG, $value->foto);
            $sheet->setCellValue($rowH, $value->status);
        }




        $fileName = 'Karyawan-' . time() . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Csv');
        $writer->save('php://output');
    }

    public function exportPdf(Request $request){
        $mpdf = new \Mpdf\Mpdf();
        $data = Karyawan::orderBy('created_at','desc')->get();
        $mpdf->WriteHTML(view("pdf.karyawan_pdf", compact('data')));
        $mpdf->Output('Data Karyawan.pdf','I');
    }
}
