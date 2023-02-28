<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\PdfToText\Pdf;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
// use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'INPUT_DATA_INCORRECT',
        ])->header('Content-Type', 'application/json; charset=utf-8');
    }
  
    public function process(Request $request)
    {
        try {
            $user = $request->user();
            // Validate the uploaded file
            $request->validate([
                'file' => 'required|mimes:pdf|max:2048',
            ]);
            
            // Process the file and generate the required output
            $output = $this->processFile($request->file('file'));
            // Ensure that the data is encoded in UTF-8


            // Return the output in JSON format
            return response()->json([
                'status' => 'Thành công',
                'output' => $output,
            ])->header('Content-Type', 'application/json; charset=utf-8');
            // return view('convert', [
            //     'status' => 'Thành công',
            //     'output' => $output,

            // ]);
        } catch (\Exception $e) {
            // Return error message and status code in case of an error
            return response()->json([
                'status' => 'error',
                'message' => 'INPUT_DATA_INCORRECT',
            ])->header('Content-Type', 'application/json; charset=utf-8');
            
            // return view('convert', [
            //     'status' => 'error',
            // ]);
        }
    }


    public function processFile($file)
    {
        // Store the file in the storage folder
        // $path = $file->store('public');

        // Get the original file name
        $fileName = $file->getClientOriginalName();

        // Process the file using any third-party libraries or tools
        $pdftotextPath = getenv('PDFTOTEXT_PATH');
        $fileName = $file->getClientOriginalName();
        // Get the path to the temporary file created by Laravel
        $filePath = $file->getRealPath();
        // Remove any special characters from the file name
        $cleanName = Str::slug(pathinfo($fileName, PATHINFO_FILENAME), '-');

        // Get the file extension
        $extension = $file->getClientOriginalExtension();

        // Create the new file name
        $newName = $cleanName . '.' . $extension;

        $process = new Process([$pdftotextPath, $filePath]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        $texts = (new Pdf(getenv('PDFTOTEXT_PATH')))->setPdf($filePath)->text();

        // end xu ly file

        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $mydate = getdate(date("U"));
        $today = "$mydate[year]$mydate[mon]$mydate[mday]$mydate[hours]$mydate[minutes]$mydate[seconds]";

        //   echo Pdf::getText($pdfPath, $pdftotextPath);
        $files = storage_path('app/' .  $newName  . $today . '.txt');
        file_put_contents($files, $texts);
        // txt to json
        $filePath = storage_path('app/' . $newName  . $today . '.txt');
        $text = file_get_contents($filePath);
        $encoding = mb_detect_encoding($text);
        // set utf 8
        $utf8Text = iconv($encoding, 'UTF-8', $text);
        $dataArray = explode("\n", $utf8Text);
        $dataArray =  str_replace("\r", "", $dataArray);
        // create data 
        $data = [];
        // // Loop through each line and extract the data
        //1
        $group = 'company_name';
        $pattern = '/\d+\.\s+/'; // matches digits followed by a dot and one or more spaces
        $replacement = ''; // replace with empty string
        for ($i =  0; $i < sizeof($dataArray); $i++) {
            $line = trim($dataArray[$i]);
            // Check if the line contains a colon (':')
            if (strpos($line, ':') !== false) {
                if (strpos($line, 'Mã số doanh nghiệp') !== false) {
                    break;
                } else {
                    $parts = explode(':', $line, 2);
                    if (is_numeric(Str::slug(pathinfo(trim($line), PATHINFO_FILENAME), '_'))) {
                        $cleanText = preg_replace($pattern, $replacement, $text);
                        $group =      $cleanText;
                    }
                    // Split the line into key and value

                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    // Add the key-value pair to the data array
                    $data[$group][Str::slug(pathinfo($key, PATHINFO_FILENAME), '_')] = $value;
                }
            }
        }
        // 2 - 7
        for ($i =  0; $i < sizeof($dataArray); $i++) {
            $line = trim($dataArray[$i]);

            if (strpos($line, 'Mã số doanh nghiệp') !== false) {
                $position = $i;
                for ($i =  $position; $i < sizeof($dataArray); $i++) {
                    $line = trim($dataArray[$i]);
                    // Check if the line contains a colon (':')
                    if (strpos($line, ':') !== false) {
                        if (strpos($line, 'Người đại diện theo pháp luật') !== false) {
                            break;
                        } else if (strpos($line, 'Vốn điều lệ') !== false || strpos($line, 'Ngành, nghề kinh doanh') !== false || strpos($line, 'Ngày thành lập') !== false || strpos($line, 'Mã số doanh nghiệp') !== false || strpos($line, 'Chi tiết') !== false) {
                            continue;
                        } else {
                            $parts = explode(':', $line, 2);
                            if (is_numeric(Str::slug(pathinfo(trim($line), PATHINFO_FILENAME), '_'))) {
                                if (strpos($line, '. ') !== false) {
                                    $cleanText = preg_replace($pattern, $replacement, $dataArray[$i]);
                                    $group =   Str::slug(pathinfo($cleanText, PATHINFO_FILENAME), '_');
                                }
                            }
                            // Split the line into key and value
                            $key = preg_replace($pattern, $replacement, trim($parts[0]));
                            if (strpos($dataArray[$i + 1], ':') !== false) {
                                $value = trim($dataArray[$i]);
                            } else    if (strpos($dataArray[$i + 2], ':') !== false) {
                                $value = trim($dataArray[$i]) . " " . trim($dataArray[$i + 1]);
                            } else {
                                $value = trim($dataArray[$i]) . " " . trim($dataArray[$i + 1]) . " " . trim($dataArray[$i + 2]);;
                            }
                            $legal_representative = explode(':', $value);
                            if (sizeof($legal_representative) >= 2) {
                                $data[$group][Str::slug(pathinfo($key, PATHINFO_FILENAME), '_')] =  trim($legal_representative[1]);
                            }
                        }
                    }
                }
            }
        }
        // table

        for ($i =  0; $i < sizeof($dataArray); $i++) {
            $line = trim($dataArray[$i]);
            if (strpos($line, 'Chi tiết:') !== false) {
                $position = $i;
                for ($i =  $position; $i < sizeof($dataArray); $i++) {
                    $arr = trim($dataArray[$i]);
                    if (strpos($arr, 'Thông tin về chủ sở hữu') !== false) {
                        break;
                    } else {
                        if (is_numeric($dataArray[$i])) {
                            if (strpos($arr, '.') === false) {
                                $data['business_lines'][] = [
                                    'industry_code' => $dataArray[$i]
                                ];
                            }
                        }
                        if (strpos($arr, '(Chính)') !== false) {
                            $data['business_lines']['main_industry_code'] = trim(str_replace('(Chính)', '', $arr));
                        }
                    }
                }
            }
        }
        // end table
        // legal representative 
        for ($i =  0; $i < sizeof($dataArray); $i++) {
            $line = trim($dataArray[$i]);
            if (strpos($line, 'Người đại diện theo pháp luật') !== false) {
                $position = $i;

                for ($i =  $position; $i < sizeof($dataArray); $i++) {
                    $line = trim($dataArray[$i]);
                    // Check if the line contains a colon (':')
                    if (strpos($line, ':') !== false) {
                        if (strpos($arr, 'Người đại diện theo pháp luật') !== false) {
                            continue;
                        }
                        $parts = explode(':', $line, 2);
                        if (is_numeric(Str::slug(pathinfo(trim($line), PATHINFO_FILENAME), '_'))) {
                            if (strpos($line, '. ') !== false) {

                                $cleanText = preg_replace($pattern, $replacement, $line);
                                $group =   Str::slug(pathinfo($cleanText, PATHINFO_FILENAME), '_');
                            }
                        }
                        // Split the line into key and value
                        $key = preg_replace($pattern, $replacement, trim($parts[0]));
                        if (strpos($dataArray[$i + 1], ':') !== false) {
                            $value = trim($dataArray[$i]);
                        } else    if (strpos($dataArray[$i + 2], ':') !== false) {
                            $value = trim($dataArray[$i]) . " " . trim($dataArray[$i + 1]);
                        } else {
                            $value = trim($dataArray[$i]) . " " . trim($dataArray[$i + 1]) . " " . trim($dataArray[$i + 2]);;
                        }
                        $legal_representative = explode(':', $value);
                        if (sizeof($legal_representative) >= 2) {
                            $data[$group][Str::slug(pathinfo($key, PATHINFO_FILENAME), '_')] =  trim($legal_representative[1]);
                        }
                    }
                }
            }
        }
        //end owner info legal representative
        // data can xu bat ng tat
        // xu ly mục 2-5
        for ($i = 0; $i < sizeof($dataArray); $i++) {
            $line = trim($dataArray[$i]);
            if (!empty($line)) {
                if (strpos($line, 'Mã số doanh nghiệp') !== false) {

                    $data['tax_code'] = trim($dataArray[$i - 1]);
                } elseif (strpos($line, 'Ngày thành lập') !== false) {
                    $data['establishment_date'] = trim($dataArray[$i - 1]);
                } elseif (strpos($line, ' VNĐ') !== false) {
                    $data['charter_capital'] = trim($line);
                }
            }
        }
        //end data

        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );


        $newFilename =   $newName  . $today . '.json'; // your new JSON file name
    //   Storage::put($newFilename, $jsonData);
        Storage::delete($newName  . $today . '.txt');

        // Return the processed data along with the file name
        return [
            'data' => $data,
        ];
    }

}
