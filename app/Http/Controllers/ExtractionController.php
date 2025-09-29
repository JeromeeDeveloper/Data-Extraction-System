<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExtractionController extends Controller
{
    public function index()
    {
        try {
            // Get record counts for each table
            $cifCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.CIF")[0]->count;
            $lnaccCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.LNACC")[0]->count;
            $relaccCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.RELACC")[0]->count;
            $trnhistCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.TRNHIST")[0]->count;
            $brparmsCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.BRPARMS")[0]->count;
            $userlookupCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.USERLOOKUP")[0]->count;
            $lnhistCount = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.LNHIST")[0]->count;

            return view('admin.extraction.extraction', compact(
                'cifCount', 'lnaccCount', 'relaccCount', 'trnhistCount',
                'brparmsCount', 'userlookupCount', 'lnhistCount'
            ));
        } catch (\Exception $e) {
            return view('admin.extraction.extraction')->with('error', 'Unable to connect to database: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        try {
            // Increase execution time and memory limit significantly for large dataset
            set_time_limit(0); // No time limit
            ini_set('memory_limit', '2048M'); // 2GB memory for large dataset
            ini_set('max_execution_time', 0); // No execution time limit

            // Test database connection first
            $testConnection = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.CIF");
            if (empty($testConnection)) {
                throw new \Exception('Database connection failed');
            }

            // Create new spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Filtered Data Export');

            // All required fields as originally requested
            $requiredFields = [
                'Record Type',
                'Provider Code',
                'Branch Code',
                'Subject Reference Date',
                'Provider Subject No',
                'Title',
                'First Name',
                'Last Name',
                'Middle Name',
                'Suffix',
                'Previous Last Name',
                'Gender',
                'Date of Birth',
                'Place of Birth',
                'Country of Birth (Code)',
                'Nationality',
                'Resident',
                'Civil Status',
                'Number of Dependents',
                'Car/s Owned',
                'Spouse First Name',
                'Spouse Last Name',
                'Spouse Middle Name',
                'Mother\'s Maiden FULL NAME',
                'Father First Name',
                'Father Last Name',
                'Father Middle Name',
                'Father Suffix',
                'Address 1: Address Type',
                'Address 1: FullAddress',
                'Address 2: Address Type',
                'Address 2: FullAddress',
                'Identification 1: Type',
                'Identification 1: Number',
                'Identification 2: Type',
                'Identification 2: Number',
                'ID 1: Type',
                'ID 1: Number',
                'ID 1: IssueDate',
                'ID 1: IssueCountry',
                'ID 1: ExpiryDate',
                'ID 1: Issued By',
                'ID 2: Type',
                'ID 2: Number',
                'ID 2: IssueDate',
                'ID 2: IssueCountry',
                'ID 2: ExpiryDate',
                'ID 2: Issued By',
                'Contact 1: Type',
                'Contact 1: Value',
                'Employment: Trade Name',
                'Employment: PSIC',
                'Employment: OccupationStatus',
                'Employment: Occupation',
                'Trade Name',
                'Role',
                'Provider Contract No',
                'Contract Type',
                'Contract Phase',
                'Currency',
                'Original Currency',
                'Contract Start Date',
                'Contract End Planned Date',
                'Contract End Actual Date',
                'Financed Amount',
                'Installments Number',
                'Transaction Type / Sub-facility',
                'Payment Periodicity',
                'Monthly Payment Amount',
                'Last payment amount',
                'Next Payment Date',
                'Outstanding Payments Number',
                'Outstanding Balance',
                'Overdue Payments Number',
                'Overdue Payments Amount',
                'Overdue Days',
                'Credit Limit'
            ];

            // Set headers
            $col = 1;
            foreach ($requiredFields as $field) {
                $sheet->setCellValueByColumnAndRow($col, 1, $field);
                $col++;
            }

            // Style headers
            $headerRange = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($requiredFields)) . '1';
            $sheet->getStyle($headerRange)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '366092']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            // Get branch information
            $branchInfo = DB::connection('sqlsrv2')->select("SELECT Br, BrName FROM Microbanker.dbo.BRPARMS")[0] ?? null;
            $branchCode = $branchInfo->Br ?? '';
            $branchName = $branchInfo->BrName ?? '';

            // Get all CIF data with basic loan info
            $query = "
                SELECT
                    c.CID,
                    c.Name1 as LastName,
                    c.Name2 as FirstName,
                    c.Name3 as MiddleName,
                    c.Name4 as Suffix,
                    c.TitleCode,
                    c.GenderType,
                    c.BirthDate,
                    c.CivilStatusCode,
                    c.Mobile1,
                    c.Email1,
                    c.DisplayName,
                    c.Type as CIFType,
                    c.Nid,
                    c.RegisterDate,
                    c.LastChangeDate,
                    -- Basic loan info
                    l.Acc as LoanAcc,
                    l.PrType,
                    l.OpenDate as ContractStartDate,
                    l.MatDate as ContractEndPlannedDate,
                    l.GrantedAmt as FinancedAmount,
                    l.InstNo as InstallmentsNumber,
                    l.FreqType as PaymentPeriodicity,
                    l.FixAmt as MonthlyPaymentAmount,
                    l.BalAmt as OutstandingBalance,
                    l.LateDaysNo as OverdueDays,
                    l.CcyType as Currency,
                    l.AccStatus,
                    l.LastTrnDate,
                    l.LastTrn as TrnType
                FROM Microbanker.dbo.CIF c
                LEFT JOIN Microbanker.dbo.RELACC r ON c.CID = r.CID
                LEFT JOIN Microbanker.dbo.LNACC l ON r.ACC = l.Acc AND r.Chd = l.Chd
                WHERE c.Type = '001' -- Individual customers
                AND l.Acc IS NOT NULL -- Only customers with loan accounts
                ORDER BY c.CID, l.Acc
            ";

            // Get total count first
            $totalCountQuery = "SELECT COUNT(*) as total FROM Microbanker.dbo.CIF c
                               LEFT JOIN Microbanker.dbo.RELACC r ON c.CID = r.CID
                               LEFT JOIN Microbanker.dbo.LNACC l ON r.ACC = l.Acc AND r.Chd = l.Chd
                               WHERE c.Type = '001' AND l.Acc IS NOT NULL";
            $totalCount = DB::connection('sqlsrv2')->select($totalCountQuery)[0]->total ?? 0;

            \Log::info('Starting export of ' . $totalCount . ' records');

            // Process all data at once (SQL Server compatibility)
            $row = 2;
            $processedCount = 0;

            // Get all data in one query (compatible with older SQL Server)
            $data = DB::connection('sqlsrv2')->select($query);

            \Log::info('Retrieved ' . count($data) . ' records for processing');

            foreach ($data as $record) {
                $col = 1;
                $processedCount++;

                // Comprehensive field mapping with actual data
                $comprehensiveData = [
                    'Record Type' => 'Individual',
                    'Provider Code' => 'MB001',
                    'Branch Code' => $branchCode,
                    'Subject Reference Date' => $record->RegisterDate ? date('Y-m-d', strtotime($record->RegisterDate)) : '',
                    'Provider Subject No' => $record->CID,
                    'Title' => $record->TitleCode ?? '',
                    'First Name' => $record->FirstName ?? '',
                    'Last Name' => $record->LastName ?? '',
                    'Middle Name' => $record->MiddleName ?? '',
                    'Suffix' => $record->Suffix ?? '',
                    'Previous Last Name' => '', // Not available
                    'Gender' => $record->GenderType ?? '',
                    'Date of Birth' => $record->BirthDate ? date('Y-m-d', strtotime($record->BirthDate)) : '',
                    'Place of Birth' => '', // Not available
                    'Country of Birth (Code)' => 'PH',
                    'Nationality' => 'Filipino',
                    'Resident' => 'Yes',
                    'Civil Status' => $record->CivilStatusCode ?? '',
                    'Number of Dependents' => '', // Not available
                    'Car/s Owned' => '', // Not available
                    'Spouse First Name' => '', // Not available
                    'Spouse Last Name' => '', // Not available
                    'Spouse Middle Name' => '', // Not available
                    'Mother\'s Maiden FULL NAME' => '', // Not available
                    'Father First Name' => '', // Not available
                    'Father Last Name' => '', // Not available
                    'Father Middle Name' => '', // Not available
                    'Father Suffix' => '', // Not available
                    'Address 1: Address Type' => 'Primary',
                    'Address 1: FullAddress' => '', // Not available
                    'Address 2: Address Type' => '',
                    'Address 2: FullAddress' => '',
                    'Identification 1: Type' => 'National ID',
                    'Identification 1: Number' => $record->Nid ?? '',
                    'Identification 2: Type' => '',
                    'Identification 2: Number' => '',
                    'ID 1: Type' => 'National ID',
                    'ID 1: Number' => $record->Nid ?? '',
                    'ID 1: IssueDate' => '',
                    'ID 1: IssueCountry' => 'PH',
                    'ID 1: ExpiryDate' => '',
                    'ID 1: Issued By' => 'Government',
                    'ID 2: Type' => '',
                    'ID 2: Number' => '',
                    'ID 2: IssueDate' => '',
                    'ID 2: IssueCountry' => '',
                    'ID 2: ExpiryDate' => '',
                    'ID 2: Issued By' => '',
                    'Contact 1: Type' => 'Mobile',
                    'Contact 1: Value' => $record->Mobile1 ?? '',
                    'Employment: Trade Name' => '', // Not available
                    'Employment: PSIC' => '', // Not available
                    'Employment: OccupationStatus' => '', // Not available
                    'Employment: Occupation' => '', // Not available
                    'Trade Name' => $branchName,
                    'Role' => 'Customer',
                    'Provider Contract No' => $record->LoanAcc ?? '',
                    'Contract Type' => $record->PrType ?? '',
                    'Contract Phase' => $record->AccStatus ?? '',
                    'Currency' => $record->Currency ?? 'PHP',
                    'Original Currency' => $record->Currency ?? 'PHP',
                    'Contract Start Date' => $record->ContractStartDate ? date('Y-m-d', strtotime($record->ContractStartDate)) : '',
                    'Contract End Planned Date' => $record->ContractEndPlannedDate ? date('Y-m-d', strtotime($record->ContractEndPlannedDate)) : '',
                    'Contract End Actual Date' => '', // Not available in this query
                    'Financed Amount' => $record->FinancedAmount ?? 0,
                    'Installments Number' => $record->InstallmentsNumber ?? 0,
                    'Transaction Type / Sub-facility' => $record->TrnType ?? '',
                    'Payment Periodicity' => $record->PaymentPeriodicity ?? '',
                    'Monthly Payment Amount' => $record->MonthlyPaymentAmount ?? 0,
                    'Last payment amount' => 0, // Not available in this query
                    'Next Payment Date' => $record->LastTrnDate ? date('Y-m-d', strtotime($record->LastTrnDate)) : '',
                    'Outstanding Payments Number' => 0, // Not available in this query
                    'Outstanding Balance' => $record->OutstandingBalance ?? 0,
                    'Overdue Payments Number' => 0, // Not available in this query
                    'Overdue Payments Amount' => 0, // Not available in this query
                    'Overdue Days' => $record->OverdueDays ?? 0,
                    'Credit Limit' => 0 // Not available
                ];

                try {
                    foreach ($requiredFields as $field) {
                        $value = $comprehensiveData[$field] ?? '';
                        $sheet->setCellValueByColumnAndRow($col, $row, $value);
                        $col++;
                    }
                    $row++;

                    // Log progress every 50 records
                    if ($processedCount % 50 == 0) {
                        \Log::info('Processed ' . $processedCount . ' of ' . $totalCount . ' records');
                    }
                } catch (\Exception $e) {
                    \Log::error('Error processing record ' . $processedCount . ': ' . $e->getMessage());
                    continue;
                }
            }

            // Auto-size columns
            foreach (range('A', \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($requiredFields))) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Add borders to data
            $dataRange = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($requiredFields)) . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            // Generate filename
            $filename = 'filtered_microbanker_export_' . date('Y-m-d_H-i-s') . '.xlsx';

            // Create writer and save to storage
            $writer = new Xlsx($spreadsheet);
            $filePath = storage_path('app/temp/' . $filename);

            // Ensure temp directory exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $writer->save($filePath);

            // Log completion
            \Log::info('Export completed successfully. File saved to: ' . $filePath);
            \Log::info('Total records processed: ' . $processedCount);

            // Return file download response
            return response()->download($filePath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Export failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()->with('error', 'Export failed: ' . $e->getMessage() . ' - Check logs for details');
        }
    }

    private function mapDataToFields($record, $branchCode, $branchName)
    {
        // Helper function to format dates
        $formatDate = function($date) {
            return $date ? date('Y-m-d', strtotime($date)) : '';
        };

        // Simplified lookup - just return the code for now to avoid database calls
        $getLookupValue = function($code, $lookupId) {
            return $code ?: '';
        };

        return [
            'Record Type' => 'Individual',
            'Provider Code' => 'MB001', // You can customize this
            'Branch Code' => $branchCode,
            'Subject Reference Date' => $formatDate($record->RegisterDate),
            'Provider Subject No' => $record->CID,
            'Title' => $getLookupValue($record->TitleCode, 'TIT'),
            'First Name' => $record->FirstName ?? '',
            'Last Name' => $record->LastName ?? '',
            'Middle Name' => $record->MiddleName ?? '',
            'Suffix' => $record->Suffix ?? '',
            'Previous Last Name' => '', // Not available in current schema
            'Gender' => $getLookupValue($record->GenderType, 'GEN'),
            'Date of Birth' => $formatDate($record->BirthDate),
            'Place of Birth' => '', // Not available in current schema
            'Country of Birth (Code)' => 'PH', // Default to Philippines
            'Nationality' => 'Filipino', // Default
            'Resident' => 'Yes', // Default
            'Civil Status' => $getLookupValue($record->CivilStatusCode, 'CIV'),
            'Number of Dependents' => '', // Not available
            'Car/s Owned' => '', // Not available
            'Spouse First Name' => '', // Not available
            'Spouse Last Name' => '', // Not available
            'Spouse Middle Name' => '', // Not available
            'Mother\'s Maiden FULL NAME' => '', // Not available
            'Father First Name' => '', // Not available
            'Father Last Name' => '', // Not available
            'Father Middle Name' => '', // Not available
            'Father Suffix' => '', // Not available
            'Address 1: Address Type' => 'Primary',
            'Address 1: FullAddress' => '', // Not available in current schema
            'Address 2: Address Type' => '',
            'Address 2: FullAddress' => '',
            'Identification 1: Type' => 'National ID',
            'Identification 1: Number' => $record->Nid ?? '',
            'Identification 2: Type' => '',
            'Identification 2: Number' => '',
            'ID 1: Type' => 'National ID',
            'ID 1: Number' => $record->Nid ?? '',
            'ID 1: IssueDate' => '',
            'ID 1: IssueCountry' => 'PH',
            'ID 1: ExpiryDate' => '',
            'ID 1: Issued By' => 'Government',
            'ID 2: Type' => '',
            'ID 2: Number' => '',
            'ID 2: IssueDate' => '',
            'ID 2: IssueCountry' => '',
            'ID 2: ExpiryDate' => '',
            'ID 2: Issued By' => '',
            'Contact 1: Type' => 'Mobile',
            'Contact 1: Value' => $record->Mobile1 ?? '',
            'Employment: Trade Name' => '', // Not available
            'Employment: PSIC' => '', // Not available
            'Employment: OccupationStatus' => '', // Not available
            'Employment: Occupation' => '', // Not available
            'Trade Name' => $branchName,
            'Role' => 'Customer',
            'Provider Contract No' => $record->LoanAcc ?? '',
            'Contract Type' => $getLookupValue($record->PrType, 'LNT'),
            'Contract Phase' => $record->AccStatus ?? '',
            'Currency' => $record->Currency ?? 'PHP',
            'Original Currency' => $record->Currency ?? 'PHP',
            'Contract Start Date' => $formatDate($record->ContractStartDate),
            'Contract End Planned Date' => $formatDate($record->ContractEndPlannedDate),
            'Contract End Actual Date' => $formatDate($record->ContractEndActualDate),
            'Financed Amount' => $record->FinancedAmount ?? 0,
            'Installments Number' => $record->InstallmentsNumber ?? 0,
            'Transaction Type / Sub-facility' => $getLookupValue($record->TrnType, 'TRN'),
            'Payment Periodicity' => $getLookupValue($record->PaymentPeriodicity, 'FRE'),
            'Monthly Payment Amount' => $record->MonthlyPaymentAmount ?? 0,
            'Last payment amount' => $record->LastPaymentAmount ?? 0,
            'Next Payment Date' => $formatDate($record->LastPaymentDate),
            'Outstanding Payments Number' => $record->LnInstNo ?? 0,
            'Outstanding Balance' => $record->OutstandingBalance ?? 0,
            'Overdue Payments Number' => $record->LnLateDaysNo ?? 0,
            'Overdue Payments Amount' => 0, // Calculate if needed
            'Overdue Days' => $record->OverdueDays ?? 0,
            'Credit Limit' => 0 // Not available in current schema
        ];
    }

    public function testExport()
    {
        try {
            // Test database connection
            $testConnection = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.CIF");

            if (empty($testConnection)) {
                return response()->json(['error' => 'Database connection failed']);
            }

            // Test PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Test Export');
            $sheet->setCellValue('A2', 'Database Records: ' . $testConnection[0]->count);
            $sheet->setCellValue('A3', 'Date: ' . date('Y-m-d H:i:s'));

            $writer = new Xlsx($spreadsheet);
            $filePath = storage_path('app/temp/test_export.xlsx');

            // Ensure temp directory exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $writer->save($filePath);

            return response()->download($filePath, 'test_export.xlsx')->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
