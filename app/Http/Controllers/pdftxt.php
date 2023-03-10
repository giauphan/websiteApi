<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
// use setasign\Fpdi\Tcpdf\Fpdi;

use Illuminate\Support\Str;
class pdftxt extends Controller
{
    public function index()
    {
        // $pdfPath = 'upload/pdf/pdf-convert.pdf';
        // $texts = (new Pdf(getenv('PDFTOTEXT_PATH')))->setPdf($pdfPath)->text();
        $parser = new Parser();
        $pdf    = $parser->parseFile('upload/pdf/pdf-convert.pdf');
        $texts   = $pdf->getText();
        // $texts = new Pdf(getenv('PDFTOTEXT_PATH'));
        // $texts->getText('upload/pdf/pdf-convert.pdf'); 

        
        //   echo Pdf::getText($pdfPath, $pdftotextPath);
        $file = storage_path('app/converted-text.txt');
        file_put_contents($file, $texts);
        // txt to json
        $filePath = storage_path('app/converted-text.txt');
        $text = file_get_contents($filePath);
        $encoding = mb_detect_encoding($text);

        // Convert the text to UTF-8 and then to an array
        $utf8Text = iconv($encoding, 'UTF-8', $text);

        $dataArray = explode("\n", $utf8Text);
        $dataArray =  str_replace("\r", "", $dataArray);
        // $result = [];
        // foreach ($dataArray as $line) {
        //     $parts = explode(':', $line, 2);
        //     if (count($parts) == 2) {
        //         $result[$parts[0]] = $parts[1];
        //     }
        // }


        // Initialize an array to store the data
        $data = [];

        // Loop through each line and extract the data
        foreach ($dataArray as $line) {
            $line = trim($line);
            if (!empty($line)) {
                // Split the line by the colon
                $parts = explode(':', $line, 2);

                // Extract the key and value
                if (sizeof($parts) >= 2) {
                    $key = trim($parts[0]);
                    $value = trim($parts[1]);
                    $data[$key] = $value;
                } else  if (sizeof($parts) >= 1) {
                    $key = trim($parts[0]);
                    $data[$key] = '';
                }


                // Add the key-value pair to the data array

            }
        }
        echo '<pre>';
        print_r($data);
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        // save the JSON to a new file
        // $newFilename = 'file.json'; // your new JSON file name
        // Storage::put($newFilename, $jsonData);
     //   Return a view with the JSON data
        return view('convert', [
            'jsonData' => $data,
            'arr' => $dataArray
        ]);
    }
    public function pdfToJson(Request $request)
    {
        // xu ly file 
        $file = $request->file('pdf_file');
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
        $data = [
            "company_name" => [
                "vietnamese" => "",
                "foreign" => "",
                "abbreviation" => ""
            ],
            "tax_code" => "",
            "establishment_date" => "",
            "headquarters_address" => [
                "street" => "",
                "ward" => "",
                "district" => "",
                "city" => "",
                "country" => "Vietnam",
                "phone" => "",
                "fax" => "",
                "Email" => "",
                "website" => ""
            ],
            "business_lines" => [
                'main_industry_code' => ''

            ],
            "charter_capital" => "",
            "owner_info" => [
                "full_name" => "",
                "gender" => "",
                "birthday" => "",
                "ethnicity" => "",
                "nationality" => "Vietnam",
                "legal_document_type" => "",
                "legal_document_number" => "",
                "legal_document_date" => "",
                "legal_document_place" => "",
                "permanent_address" => "",
                "contact_address" => ""
            ],
            "legal_representative" => [
                "full_name" =>  "",
                "gender" => "",
                "position" =>  "",
                "birthday" =>  "",
                "ethnicity" =>  "",
                "nationality" =>  "",
                "legal_document_type" =>  "",
                "legal_document_number" =>  "",
                "legal_document_date" =>  "",
                "legal_document_place" =>  "",
                "permanent_address" =>  "",
                "contact_address" =>  ""
            ],
            "registration_office" =>  ""
        ];
        // xu ly m???c 1 -4 and 6 
        for ($i = 0; $i < sizeof($dataArray); $i++) {
            $line = trim($dataArray[$i]);

            if (strpos($line, 'C??NG B??? N???I DUNG ????NG K?? TH??NH L???P M???I') !== false) {
                continue;
            }

            if (strpos($line, 'M?? s??? doanh nghi???p') !== false) {

                $data['tax_code'] = trim($dataArray[$i - 1]);
                //    trim(str_replace('M?? s??? doanh nghi???p:', '', $line));
            } elseif (strpos($line, '?????a ch??? tr??? s??? ch??nh') !== false) {
                $address = trim($dataArray[$i + 1]) . trim($dataArray[$i + 2]);
                $address_parts = explode(',', $address);
                if (sizeof($address_parts) >= 4) {
                    $data['headquarters_address']['street'] = trim($address_parts[0]);
                    $data['headquarters_address']['ward'] = trim($address_parts[1]);
                    $data['headquarters_address']['district'] = trim($address_parts[2]);
                    $data['headquarters_address']['city'] = trim($address_parts[3]); # code...
                }
            } elseif (strpos($line, '??i???n tho???i') !== false) {
                $data['headquarters_address']['phone'] = trim(str_replace('??i???n tho???i:', '', $line));
            } elseif (strpos($line, 'Fax') !== false) {
                $data['headquarters_address']['fax'] = trim(str_replace('Fax:', '', $line));
            } elseif (strpos($line, 'Email') !== false) {
                $data['headquarters_address']['Email'] = trim(str_replace('Email:', '', $line));
            } elseif (strpos($line, '??i???n tho???i') !== false) {
                $data['headquarters_address']['website'] = trim(str_replace('Website:', '', $line));
            } elseif (strpos($line, 'Ng??y th??nh l???p') !== false) {

                $data['establishment_date'] = trim($dataArray[$i - 1]);

                //trim(str_replace('Ng??y; th??nh l???p:', '', $line));
            } elseif (strpos($line, 'T??n c??ng ty vi???t b???ng ti???ng Vi???t') !== false) {
                $data['company_name']['vietnamese'] = trim(str_replace('T??n c??ng ty vi???t b???ng ti???ng Vi???t:', '', $line));
            } elseif (strpos($line, 'T??n c??ng ty vi???t b???ng ti???ng n?????c ngo??i') !== false) {
                $data['company_name']['foreign'] = trim(str_replace('T??n c??ng ty vi???t b???ng ti???ng n?????c ngo??i:', '', $line));
            } elseif (strpos($line, 'T??n c??ng ty vi???t t???t') !== false) {
                $data['company_name']['abbreviation'] = trim(str_replace('T??n c??ng ty vi???t t???t:', '', $line));
            } elseif (strpos($line, 'Ng??nh, ngh??? kinh doanh') !== false) {
                continue;
            } elseif (strpos($line, ' VN??') !== false) {
                $data['charter_capital'] = trim($line);
            }
        }
        // table
        for ($i =  0; $i < sizeof($dataArray); $i++) {
            $line = trim($dataArray[$i]);
            if (strpos($line, 'Chi ti???t:') !== false) {
                $position = $i;
                for ($i =  $position; $i < sizeof($dataArray); $i++) {
                    $arr = trim($dataArray[$i]);
                    if (is_numeric($dataArray[$i])) {
                        if (strpos($arr, '.') === false) {
                            $data['business_lines'][] = [
                                'industry_code' => $dataArray[$i]
                            ];
                        }
                    }
                    if (strpos($arr, '(Ch??nh)') !== false) {
                        $data['business_lines']['main_industry_code'] = trim(str_replace('(Ch??nh)', '', $arr));
                    }
                    if (strpos($arr, 'Th??ng tin v??? ch??? s??? h???u') !== false) {
                        break;
                    }
                }
            }
        }
        // end table

        // owner info
        for ($i =  0; $i < sizeof($dataArray); $i++) {
            $line = trim($dataArray[$i]);
            if (strpos($line, 'Th??ng tin v??? ch??? s??? h???u') !== false) {
                $position = $i;
                for ($i =  $position; $i < sizeof($dataArray); $i++) {
                    $line = trim($dataArray[$i]);
                    if (strpos($line, 'H??? v?? t??n') !== false && strpos($line, '* H??? v?? t??n') == false) {
                        $parts = explode(':', $line);
                        if (sizeof($parts) >= 2) {
                            $data['owner_info']['full_name'] = $parts[1];
                        }
                        // $data['owner_info']['full_name'] = trim(str_replace('H??? v?? t??n:', '', $line));
                    } elseif (strpos($line, 'Gi???i t??nh') !== false) {
                        $data['owner_info']['gender'] = trim(str_replace('Gi???i t??nh:', '', $line));
                    } elseif (strpos($line, 'Sinh ng??y') !== false) {
                        $data['owner_info']['birthday'] = trim(str_replace('Sinh ng??y:', '', $line));
                    } elseif (strpos($line, 'D??n t???c') !== false) {
                        $data['owner_info']['ethnicity'] = trim(str_replace('D??n t???c:', '', $line));
                    } elseif (strpos($line, 'Qu???c t???ch') !== false) {
                        $data['owner_info']['nationality'] = trim(str_replace('Qu???c t???ch:', '', $line));
                    } elseif (strpos($line, 'Lo???i gi???y t??? ph??p l?? c???a c?? nh??n') !== false) {
                        $parts = explode(':', $line);
                        if (sizeof($parts) >= 2) {
                            $data['owner_info']['legal_document_type'] = $parts[1];
                        }
                    } elseif (strpos($line, 'S??? gi???y t??? ph??p l?? c???a c?? nh??n') !== false) {
                        $parts = explode(':', $line);
                        if (sizeof($parts) >= 2) {
                            $data['owner_info']['legal_document_number'] = $parts[1];
                        }
                        $data['owner_info']['legal_document_number'] = trim(str_replace('S??? gi???y t??? ph??p l?? c???a c?? nh??n: ', ' ', $line));
                    } elseif (strpos($line, 'Ng??y c???p') !== false) {
                        $data['owner_info']['legal_document_date'] = trim(str_replace('Ng??y c???p: ', '', $line));
                    } elseif (strpos($line, 'N??i c???p') !== false) {
                        if (strpos($dataArray[$i + 1], ':') !== false) {
                            $legal = trim($dataArray[$i]);
                        } else {
                            $legal = trim($dataArray[$i]) . " " . trim($dataArray[$i + 1]);
                        }
                        $legal_representative = explode(':', $legal);
                        if (sizeof($legal_representative) >= 2) {
                            $data['owner_info']['legal_document_place'] = trim($legal_representative[1]);
                        }
                    } elseif (strpos($line, '?????a ch??? th?????ng tr??') !== false) {
                        if (strpos($dataArray[$i + 1], ':') !== false) {
                            $legal = trim($dataArray[$i]);
                        } else {
                            $legal = trim($dataArray[$i]) . "  " . trim($dataArray[$i + 1]);
                        }
                        $legal_representative = explode(':', $legal);
                        if (sizeof($legal_representative) >= 2) {
                            $data['owner_info']['permanent_address'] = $legal_representative[1];
                        }
                    } elseif (strpos($line, '?????a ch??? li??n l???c') !== false) {
                        if (strpos($dataArray[$i + 1], ':') !== false) {
                            $legal = trim($dataArray[$i]);
                        } else {
                            $legal = trim($dataArray[$i]) . trim($dataArray[$i + 1]);
                        }
                        $legal_representative = explode(':', $legal);
                        if (sizeof($legal_representative) >= 2) {
                            $data['owner_info']['contact_address'] = trim($legal_representative[1]);
                        }
                    }
                    // echo '<pre>';
                    // print_r($dataArray[$i]);
                    if (strpos($line, 'Ng?????i ?????i di???n theo ph??p lu???t') !== false) {
                        break;
                    }
                }
            }
        }
        //end owner info
        // legal representative 
        for ($i =  0; $i < sizeof($dataArray); $i++) {
            $line = trim($dataArray[$i]);
            if (strpos($line, '* H??? v?? t??n') !== false) {
                $parts = explode(':', $line);
                if (sizeof($parts) >= 2) {
                    $data['legal_representative']['full_name'] = $parts[1];
                }
                // $data['legal_representative']['full_name'] = trim(str_replace('* H??? v?? t??n:', '', $line));
            } elseif (strpos($line, 'Ch???c danh') !== false) {
                $data['legal_representative']['position'] = trim(str_replace('Ch???c danh:', '', $line));
            } elseif (strpos($line, 'Gi???i t??nh') !== false) {
                $data['legal_representative']['gender'] = trim(str_replace('Gi???i t??nh:', '', $line));
            } elseif (strpos($line, 'Sinh ng??y') !== false) {
                $parts = explode(':', $line);
                if (sizeof($parts) >= 2) {

                    $data['legal_representative']['birthday'] = $parts[1];
                }
                //    $data['legal_representative']['birthday'] = trim(str_replace('Sinh ng??y:', '', $line));
            } elseif (strpos($line, 'D??n t???c') !== false) {
                $data['legal_representative']['ethnicity'] = trim(str_replace('D??n t???c:', '', $line));
            } elseif (strpos($line, 'Qu???c t???ch') !== false) {
                $data['legal_representative']['nationality'] = trim(str_replace('Qu???c t???ch:', '', $line));
            } elseif (strpos($line, 'Lo???i gi???y t??? ph??p l?? c???a c?? nh??n') !== false) {
                $data['legal_representative']['legal_document_type'] = trim(str_replace('Lo???i gi???y t??? ph??p l?? c???a c?? nh??n: ', '', $line));
            } elseif (strpos($line, 'S??? gi???y t??? ph??p l?? c???a c?? nh??n') !== false) {
                if ($dataArray[$i + 1] != "") {
                    $data['legal_representative']['legal_document_number'] = trim($dataArray[$i + 1]);
                } else   if ($dataArray[$i + 2] != "") {
                    $data['legal_representative']['legal_document_number'] = trim($dataArray[$i + 2]);
                } else {
                    $data['legal_representative']['legal_document_number'] = trim(str_replace('S??? gi???y t??? ph??p l?? c???a c?? nh??n: ', '', $line));
                }
            } elseif (strpos($line, 'Ng??y c???p') !== false) {
                if ($dataArray[$i + 1] != "") {
                    $data['legal_representative']['legal_document_date'] = trim($dataArray[$i + 1]);
                } else   if ($dataArray[$i + 2] != "") {
                    $data['legal_representative']['legal_document_date'] = trim($dataArray[$i + 2]);
                } else {
                    $data['legal_representative']['legal_document_date'] = trim(str_replace('Ng??y c???p: ', '', $line));
                }
            } elseif (strpos($line, 'N??i c???p') !== false) {
                if (strpos($dataArray[$i + 1], ':') !== false) {
                    $legal = trim($dataArray[$i]);
                } else {
                    $legal = trim($dataArray[$i]) . " " . trim($dataArray[$i + 1]);
                }
                $legal_representative = explode(':', $legal);
                if (sizeof($legal_representative) >= 2) {
                    $data['legal_representative']['legal_document_place'] = trim($legal_representative[1]);
                }
            } elseif (strpos($line, '?????a ch??? th?????ng tr??') !== false) {
                if (strpos($dataArray[$i + 1], ':') !== false) {
                    $legal = trim($dataArray[$i]);
                } else {
                    $legal = trim($dataArray[$i]) . "  " . trim($dataArray[$i + 1]);
                }
                $legal_representative = explode(':', $legal);
                if (sizeof($legal_representative) >= 2) {
                    $data['legal_representative']['permanent_address'] = $legal_representative[1];
                }
            } elseif (strpos($line, '?????a ch??? li??n l???c') !== false) {
                if (strpos($dataArray[$i + 1], ':') !== false) {
                    $legal = trim($dataArray[$i]);
                } else {
                    $legal = trim($dataArray[$i]) . trim($dataArray[$i + 1]);
                }
                $legal_representative = explode(':', $legal);
                if (sizeof($legal_representative) >= 2) {
                    $data['legal_representative']['contact_address'] = trim($legal_representative[1]);
                }
            }
            if (strpos($arr, 'Ng?????i ?????i di???n theo ph??p lu???t') !== false) {
                break;
            }
        }
        //end owner info legal representative


        for ($i =  0; $i < sizeof($dataArray); $i++) {
            $line = trim($dataArray[$i]);
            if (strpos($line, 'N??i ????ng k??') !== false) {
                if ($dataArray[$i + 1] != "") {
                    $data['registration_office'] = trim($dataArray[$i + 1]);
                } else   if ($dataArray[$i + 2] != "") {
                    $data['registration_office'] = trim($dataArray[$i + 2]);
                }
            }
        }
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);


        $newFilename =   $newName  . $today . '.json'; // your new JSON file name
        Storage::put($newFilename, $jsonData);
        Storage::delete($newName  . $today . '.txt');
        // Return a view with the JSON data
        return view('convert', [
            'jsonData' => $jsonData,
            'arr' => $data
        ]);
    }

}
