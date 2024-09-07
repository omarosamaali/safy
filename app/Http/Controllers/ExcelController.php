<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelController extends Controller
{
  

    public function readExcel()
    {
        // Replace 'example.xlsx' with your actual file path
        $filePath = public_path('excel/nutri.xlsx');

        try {
            // Load the Excel spreadsheet
            $spreadsheet = IOFactory::load($filePath);

            // Select the first worksheet
            $worksheet = $spreadsheet->getActiveSheet();

            // Get data from a specific cell (e.g., B2)
            $cellValue = $worksheet->getCell('B2')->getValue();

            // Pass the data to the view
            return view('formulas', ['cellValue' => $cellValue]);
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            // Handle error if the file cannot be loaded
            return "Error loading Excel file: " . $e->getMessage();
        }
    }
}

