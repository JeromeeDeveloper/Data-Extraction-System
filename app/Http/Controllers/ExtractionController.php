<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\CisaProduct;
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
            $sheet->setTitle('Microbanker Data Export');

            // Original headers as requested
            $requiredFields = [
                'Record Type', 'Provider Code', 'Branch Code', 'Subject Reference Date', 'Provider Subject No',
                'Title', 'First Name', 'Last Name', 'Middle Name', 'Suffix', 'Previous Last Name',
                'Gender', 'Date of Birth', 'Place of Birth', 'Country of Birth (Code)', 'Nationality',
                'Resident', 'Civil Status', 'Number of Dependents', 'Car/s Owned',
                'Spouse First Name', 'Spouse Last Name', 'Spouse Middle Name', 'Mother\'s Maiden FULL NAME',
                'Father First Name', 'Father Last Name', 'Father Middle Name', 'Father Suffix',
                'Address 1: Address Type', 'Address 1: FullAddress', 'Address 2: Address Type', 'Address 2: FullAddress',
                'Identification 1: Type', 'Identification 1: Number', 'Identification 2: Type', 'Identification 2: Number',
                'ID 1: Type', 'ID 1: Number', 'ID 1: IssueDate', 'ID 1: IssueCountry', 'ID 1: ExpiryDate', 'ID 1: Issued By',
                'ID 2: Type', 'ID 2: Number', 'ID 2: IssueDate', 'ID 2: IssueCountry', 'ID 2: ExpiryDate', 'ID 2: Issued By',
                'Contact 1: Type', 'Contact 1: Value', 'Contact 2: Type', 'Contact 2: Value',
                'Employment: Trade Name', 'Employment: PSIC', 'Employment: OccupationStatus', 'Employment: Occupation',
                'Trade Name', 'Role', 'Provider Contract No', 'Contract Type', 'Contract Phase',
                'Currency', 'Original Currency', 'Contract Start Date', 'Contract End Planned Date', 'Contract End Actual Date',
                'Financed Amount', 'Installments Number', 'Transaction Type / Sub-facility', 'Payment Periodicity',
                'Monthly Payment Amount', 'Last payment amount', 'Next Payment Date', 'Outstanding Payments Number',
                'Outstanding Balance', 'Overdue Payments Number', 'Overdue Payments Amount', 'Overdue Days', 'Credit Limit'
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
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3498db']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            // Get branch information
            $branchInfo = DB::connection('sqlsrv2')->select("SELECT Br, BrName, SystemCode, ManagerName, Address, FinYear FROM Microbanker.dbo.BRPARMS")[0] ?? null;
            $branchCode = $branchInfo->Br ?? '';
            $branchName = $branchInfo->BrName ?? '';
            $systemCode = $branchInfo->SystemCode ?? '';
            $managerName = $branchInfo->ManagerName ?? '';
            $address = $branchInfo->Address ?? '';
            $financialYear = $branchInfo->FinYear ?? '';

            // Determine available CIFAddInfo columns to avoid invalid column errors
            $aiColumns = DB::connection('sqlsrv2')->select("SELECT COLUMN_NAME FROM Microbanker.INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'CIFAddInfo'");
            $aiCols = array_map(function($o){ return strtoupper($o->COLUMN_NAME); }, $aiColumns);

            $col = function(array $candidates) use ($aiCols) {
                foreach ($candidates as $c) {
                    if (in_array(strtoupper($c), $aiCols, true)) { return "ai.$c"; }
                }
                return 'NULL';
            };

            $aiPrevLastNameExpr   = $col(['PrevLastName','PreviousLastName','PrevSurname']);
            $aiNumDependentsExpr  = $col(['NoOfDependents','NumDependents']);
            $aiPlaceOfBirthExpr   = $col(['PlaceOfBirth']);
            $aiEmployerExpr       = $col(['Employer']);
            $aiEmpIndCodeExpr     = $col(['EmpIndCode']);
            $aiEmpOccuStatusExpr  = $col(['EmpOccuStatus']);
            $aiEmpOccupationExpr  = $col(['EmpOccupation']);
            $aiMotherMaidenExpr   = $col(['MotherMaidenName','MothersMaidenName']);
            // Mother's maiden name parts (custom variants)
            $aiMotherFirstExpr    = $col(['MoMdFirstname','MotherFirstName','MothersFirstName']);
            $aiMotherLastExpr     = $col(['MoMdLastName','MotherLastName','MothersLastName']);
            $aiMotherMiddleExpr   = $col(['MoMdMiddleName','MotherMiddleName','MothersMiddleName']);
            // Father variants including Fatr*
            $aiFatherFNameExpr    = $col(['FatherFirstName','FatherFName','FatrFirstName']);
            $aiFatherLNameExpr    = $col(['FatherLastName','FatherLName','FatrLasttName','FatrLastName']);
            $aiFatherMNameExpr    = $col(['FatherMiddleName','FatherMName','FatrMiddlename','FatrMiddleName']);
            $aiFatherSuffixExpr   = $col(['FatherSuffix','FatrSuffix']);
            // Spouse variants including Sp*
            $aiSpouseFNameExpr    = $col(['SpouseFirstName','SpouseFName','SpFirstName']);
            $aiSpouseLNameExpr    = $col(['SpouseLastName','SpouseLName','SpLastName']);
            $aiSpouseMNameExpr    = $col(['SpouseMiddleName','SpouseMName','SpMiddleName']);
            $aiAddr1TypeExpr      = $col(['Address1Type','Addr1Type']);
            $aiAddr1FullExpr      = $col(['Address1Full','Addr1Full']);
            $aiAddr2TypeExpr      = $col(['Address2Type','Addr2Type']);
            $aiAddr2FullExpr      = $col(['Address2Full','Addr2Full']);

            // Main query - get customers with loan accounts
            $query = "
                SELECT
                    c.CID,
                    c.DisplayName,
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
                    c.Nid,
                    c.RegisterDate,
                    c.LastChangeDate,
                    c.Type as CIFType,
                    -- CIF additional info (only selecting columns that exist)
                    $aiPrevLastNameExpr as aiPrevLastName,
                    $aiNumDependentsExpr as aiNumDependents,
                    $aiPlaceOfBirthExpr as aiPlaceOfBirth,
                    $aiEmployerExpr as aiEmployer,
                    $aiEmpIndCodeExpr as aiEmpIndCode,
                    $aiEmpOccuStatusExpr as aiEmpOccuStatus,
                    $aiEmpOccupationExpr as aiEmpOccupation,
                    $aiMotherMaidenExpr as aiMotherMaidenName,
                    $aiMotherFirstExpr as aiMotherFirstName,
                    $aiMotherLastExpr as aiMotherLastName,
                    $aiMotherMiddleExpr as aiMotherMiddleName,
                    $aiFatherFNameExpr as aiFatherFirstName,
                    $aiFatherLNameExpr as aiFatherLastName,
                    $aiFatherMNameExpr as aiFatherMiddleName,
                    $aiFatherSuffixExpr as aiFatherSuffix,
                    $aiSpouseFNameExpr as aiSpouseFirstName,
                    $aiSpouseLNameExpr as aiSpouseLastName,
                    $aiSpouseMNameExpr as aiSpouseMiddleName,
                    $aiAddr1TypeExpr as aiAddr1Type,
                    $aiAddr1FullExpr as aiAddr1Full,
                    $aiAddr2TypeExpr as aiAddr2Type,
                    $aiAddr2FullExpr as aiAddr2Full,
                    -- ID info (top 2 IDs)
                    id1.IDTypeCode as ID1TypeCode,
                    id1.IDDocNo as ID1Number,
                    id1.IDIssueDate as ID1IssueDate,
                    id1.IDIssueCountry as ID1IssueCountry,
                    id1.IDExpiry as ID1ExpiryDate,
                    id1.IDIssuedBy as ID1IssuedBy,
                    id2.IDTypeCode as ID2TypeCode,
                    id2.IDDocNo as ID2Number,
                    id2.IDIssueDate as ID2IssueDate,
                    id2.IDIssueCountry as ID2IssueCountry,
                    id2.IDExpiry as ID2ExpiryDate,
                    id2.IDIssuedBy as ID2IssuedBy,
                    -- Address info (top 2 addresses)
                    a1.addrtype as Addr1TypeCode,
                    LTRIM(RTRIM(COALESCE(a1.addr_info_Sub,'') + ' ' + COALESCE(a1.addr_info,''))) as Addr1FullText,
                    a2.addrtype as Addr2TypeCode,
                    LTRIM(RTRIM(COALESCE(a2.addr_info_Sub,'') + ' ' + COALESCE(a2.addr_info,''))) as Addr2FullText,
                    -- Installment aggregates
                    instagg.NextDueDate as NextInstDueDate,
                    instagg.OutstandingCount as OutstandingInstCount,
                    instagg.OverdueCount as OverdueInstCount,
                    instagg.OverdueAmount as OverdueInstAmount,
                    l.Acc as LoanAcc,
                    l.Chd as LoanChd,
                    l.PrType,
                    l.AccStatus,
                    l.AccStatusDate,
                    l.OpenDate,
                    l.MatDate,
                    l.GrantedAmt,
                    l.BalAmt,
                    l.IntRate,
                    l.FixAmt,
                    l.InstNo,
                    l.UnExInstNo,
                    l.FreqType,
                    l.LateDaysNo,
                    l.GrantedAmtOrig,
                    l.GLCode,
                    l.OdueIntAmt,
                    l.OduePriAmt,
                    l.CcyType,
                    l.LastTrnDate,
                    l.LastTrn,
                    t.TrnDesc,
                    t.TrnAmt,
                    t.TrnDate,
                    t.TrnType,
                    lh.LnOpenDate,
                    lh.LnMatDate,
                    lh.CloseDate,
                    lh.LnPrincipalAmt,
                    lh.PerformRatio,
                    lh.LnLateDaysNo
                FROM Microbanker.dbo.CIF c
                LEFT JOIN Microbanker.dbo.CIFAddInfo ai ON ai.CID = c.CID
                OUTER APPLY (
                    SELECT TOP 1 * FROM (
                        SELECT CID, IDTypeCode, IDDocNo, IDIssueDate, IDIssueCountry, IDExpiry, IDIssuedBy,
                               ROW_NUMBER() OVER (PARTITION BY CID ORDER BY ISNULL(IDIssueDate,'1900-01-01') DESC, IDTypeCode) rn
                        FROM Microbanker.dbo.CIFIDINFO
                    ) z WHERE z.CID = c.CID AND z.rn = 1
                ) id1
                OUTER APPLY (
                    SELECT TOP 1 * FROM (
                        SELECT CID, IDTypeCode, IDDocNo, IDIssueDate, IDIssueCountry, IDExpiry, IDIssuedBy,
                               ROW_NUMBER() OVER (PARTITION BY CID ORDER BY ISNULL(IDIssueDate,'1900-01-01') DESC, IDTypeCode) rn
                        FROM Microbanker.dbo.CIFIDINFO
                    ) z WHERE z.CID = c.CID AND z.rn = 2
                ) id2
                OUTER APPLY (
                    SELECT TOP 1 * FROM (
                        SELECT cid, addrtype, addr_info, addr_info_Sub,
                               ROW_NUMBER() OVER (PARTITION BY cid ORDER BY addrtype) rn
                        FROM Microbanker.dbo.CIFADDRINFO
                    ) a WHERE a.cid = c.CID AND a.rn = 1
                ) a1
                OUTER APPLY (
                    SELECT TOP 1 * FROM (
                        SELECT cid, addrtype, addr_info, addr_info_Sub,
                               ROW_NUMBER() OVER (PARTITION BY cid ORDER BY addrtype) rn
                        FROM Microbanker.dbo.CIFADDRINFO
                    ) a WHERE a.cid = c.CID AND a.rn = 2
                ) a2
                LEFT JOIN Microbanker.dbo.RELACC r ON c.CID = r.CID
                LEFT JOIN Microbanker.dbo.LNACC l ON r.ACC = l.Acc AND r.Chd = l.Chd
                LEFT JOIN (
                    SELECT li.Acc, li.Chd,
                           MIN(CASE WHEN li.PaidDate IS NULL THEN li.DueDate END) as NextDueDate,
                           SUM(CASE WHEN li.PaidDate IS NULL THEN 1 ELSE 0 END) as OutstandingCount,
                           SUM(CASE WHEN li.PaidDate IS NULL AND li.DueDate < GETDATE() THEN 1 ELSE 0 END) as OverdueCount,
                           SUM(CASE WHEN li.PaidDate IS NULL AND li.DueDate < GETDATE() THEN COALESCE(li.PriAmt,0)+COALESCE(li.IntAmt,0)+COALESCE(li.ChargesAmt,0) ELSE 0 END) as OverdueAmount
                    FROM Microbanker.dbo.LNINST li
                    GROUP BY li.Acc, li.Chd
                ) instagg ON instagg.Acc = l.Acc AND instagg.Chd = l.Chd
                LEFT JOIN Microbanker.dbo.TRNHIST t ON l.Acc = t.Acc AND l.Chd = t.Chd
                LEFT JOIN Microbanker.dbo.LNHIST lh ON l.Acc = lh.Acc AND l.Chd = lh.Chd
                WHERE c.Type = '001' AND l.Acc IS NOT NULL
                ORDER BY c.CID, l.Acc
            ";

            $totalCountQuery = "SELECT COUNT(*) as total FROM Microbanker.dbo.CIF c
                               LEFT JOIN Microbanker.dbo.RELACC r ON c.CID = r.CID
                               LEFT JOIN Microbanker.dbo.LNACC l ON r.ACC = l.Acc AND r.Chd = l.Chd
                               WHERE c.Type = '001' AND l.Acc IS NOT NULL";
            $totalCount = DB::connection('sqlsrv2')->select($totalCountQuery)[0]->total ?? 0;

            Log::info('Starting export of ' . $totalCount . ' records');

            $row = 2;
            $processedCount = 0;

            $data = DB::connection('sqlsrv2')->select($query);

            Log::info('Retrieved ' . count($data) . ' records for processing');

            foreach ($data as $record) {
                $col = 1;
                $processedCount++;

                // Get lookup values from database configurations
                $titleDesc = $this->getTitleFromConfig($record->TitleCode);
                $genderDesc = $this->getGenderFromConfig($record->GenderType);
                $civilStatusDesc = $this->getCivilStatusCode($record->CivilStatusCode);
                $productTypeDesc = $this->getLookupValue('41', $record->PrType);
                $frequencyDesc = $this->getLookupValue('FRE', $record->FreqType);
                $transactionDesc = $this->getLookupValue('TX', $record->TrnType);

                // Normalize ID issue countries (01 -> PH) and dates
                $id1IssueCountry = '';
                if (!empty($record->ID1IssueCountry)) {
                    $ic = trim($record->ID1IssueCountry);
                    $id1IssueCountry = $ic === '01' ? 'PH' : $ic;
                }
                $id2IssueCountry = '';
                if (!empty($record->ID2IssueCountry)) {
                    $ic2 = trim($record->ID2IssueCountry);
                    $id2IssueCountry = $ic2 === '01' ? 'PH' : $ic2;
                }

                $addr1Full = $record->aiAddr1Full ?? ($record->Addr1FullText ?? $address);
                $addr2Full = $record->aiAddr2Full ?? ($record->Addr2FullText ?? '');

                // Determine residency status (1 if Resident, 0 if Non-Resident)
                // Since this is a Philippine microbanker system, assume residents are those with Philippine addresses
                $isResident = 1; // Default to resident for Philippine microbanker
                // Could be enhanced to check address country codes if available

                // Convert numeric value to text for display
                $residentText = $isResident == 1 ? 'Resident' : 'Non Resident';

                // Build Mother's Maiden FULL NAME from parts if full not provided
                $motherFull = $record->aiMotherMaidenName ?? '';
                if ($motherFull === '' || $motherFull === null) {
                    $parts = [];
                    if (!empty($record->aiMotherFirstName)) { $parts[] = trim($record->aiMotherFirstName); }
                    if (!empty($record->aiMotherMiddleName)) { $parts[] = trim($record->aiMotherMiddleName); }
                    if (!empty($record->aiMotherLastName)) { $parts[] = trim($record->aiMotherLastName); }
                    $motherFull = trim(implode(' ', $parts));
                }

                // Determine Record Type from CISA Products (CI for installment, CN for non-installment). Skip if no match
                $resolvedRecordType = null;
                try {
                    $matchedProduct = null;
                    // Try to match PrType (contract type) against gl_code in CisaProductGlCode
                    if (!empty($record->PrType)) {
                        $glCodeMatch = \App\Models\CisaProductGlCode::where('gl_code', trim($record->PrType))->first();
                        if ($glCodeMatch) {
                            $matchedProduct = $glCodeMatch->cisaProduct;
                        }
                    }
                    // If not found by PrType, try to match GLCode against gl_code in CisaProductGlCode
                    if (!$matchedProduct && !empty($record->GLCode)) {
                        $glCodeMatch = \App\Models\CisaProductGlCode::where('gl_code', trim($record->GLCode))->first();
                        if ($glCodeMatch) {
                            $matchedProduct = $glCodeMatch->cisaProduct;
                        }
                    }
                    if ($matchedProduct) {
                        $resolvedRecordType = $matchedProduct->type === 'installment' ? 'CI' : 'CN';
                    }
                } catch (\Throwable $e) {
                    \Log::error("Error in record type determination: " . $e->getMessage());
                }

                // If no CISA product matched, skip this record
                if ($resolvedRecordType === null) {
                    continue;
                }

                $comprehensiveData = [
                    'Record Type' => $resolvedRecordType,
                    'Provider Code' => 'MB001',
                    'Branch Code' => $branchCode,
                    'Subject Reference Date' => $record->RegisterDate ? date('dmY', strtotime($record->RegisterDate)) : '',
                    'Provider Subject No' => ($record->CID ?? '') . $branchCode,
                    'Title' => $titleDesc,
                    'First Name' => $record->FirstName ?? '',
                    'Last Name' => $record->LastName ?? '',
                    'Middle Name' => $record->MiddleName ?? '',
                    'Suffix' => $record->Suffix ?? '',
                    'Previous Last Name' => $record->aiPrevLastName ?? '',
                    'Gender' => $genderDesc,
                    'Date of Birth' => $record->BirthDate ? date('dmY', strtotime($record->BirthDate)) : '',
                    'Place of Birth' => $record->aiPlaceOfBirth ?? '',
                    'Country of Birth (Code)' => 'PH',
                    'Nationality' => 'Filipino',
                    'Resident' => $residentText,
                    'Civil Status' => $civilStatusDesc,
                    'Number of Dependents' => $record->aiNumDependents ?? '',
                    'Car/s Owned' => '',
                    'Spouse First Name' => $record->aiSpouseFirstName ?? '',
                    'Spouse Last Name' => $record->aiSpouseLastName ?? '',
                    'Spouse Middle Name' => $record->aiSpouseMiddleName ?? '',
                    'Mother\'s Maiden FULL NAME' => $motherFull,
                    'Father First Name' => $record->aiFatherFirstName ?? '',
                    'Father Last Name' => $record->aiFatherLastName ?? '',
                    'Father Middle Name' => $record->aiFatherMiddleName ?? '',
                    'Father Suffix' => $record->aiFatherSuffix ?? '',
                    'Address 1: Address Type' => $this->getAddressTypeCode($record->Addr1TypeCode ?? '0'),
                    'Address 1: FullAddress' => $addr1Full,
                    'Address 2: Address Type' => $this->getAddressTypeCode($record->Addr2TypeCode ?? '1'),
                    'Address 2: FullAddress' => $addr2Full,
                    'Identification 1: Type' => $record->ID1TypeCode ?? '',
                    'Identification 1: Number' => $record->ID1Number ?? ($record->Nid ?? ''),
                    'Identification 2: Type' => $record->ID2TypeCode ?? '',
                    'Identification 2: Number' => $record->ID2Number ?? '',
                    'ID 1: Type' => $record->ID1TypeCode ?? '',
                    'ID 1: Number' => $record->ID1Number ?? ($record->Nid ?? ''),
                    'ID 1: IssueDate' => !empty($record->ID1IssueDate) ? date('dmY', strtotime($record->ID1IssueDate)) : '',
                    'ID 1: IssueCountry' => $id1IssueCountry,
                    'ID 1: ExpiryDate' => !empty($record->ID1ExpiryDate) ? date('dmY', strtotime($record->ID1ExpiryDate)) : '',
                    'ID 1: Issued By' => $record->ID1IssuedBy ?? '',
                    'ID 2: Type' => $record->ID2TypeCode ?? '',
                    'ID 2: Number' => $record->ID2Number ?? '',
                    'ID 2: IssueDate' => !empty($record->ID2IssueDate) ? date('dmY', strtotime($record->ID2IssueDate)) : '',
                    'ID 2: IssueCountry' => $id2IssueCountry,
                    'ID 2: ExpiryDate' => !empty($record->ID2ExpiryDate) ? date('dmY', strtotime($record->ID2ExpiryDate)) : '',
                    'ID 2: Issued By' => $record->ID2IssuedBy ?? '',
                    'Contact 1: Type' => $this->getContactTypeCode('Mobile'),
                    'Contact 1: Value' => $record->Mobile1 ?? '',
                    'Contact 2: Type' => $this->getContactTypeCode('Email'),
                    'Contact 2: Value' => $record->Email1 ?? '',
                    'Employment: Trade Name' => $record->aiEmployer ?? '',
                    'Employment: PSIC' => $record->aiEmpIndCode ?? '',
                    'Employment: OccupationStatus' => $record->aiEmpOccuStatus ?? '',
                    'Employment: Occupation' => $record->aiEmpOccupation ?? '',
                    'Trade Name' => trim(($record->LastName ?? '') . ' ' . ($record->FirstName ?? '')),
                    'Role' => 'B',
                    'Provider Contract No' => $branchCode . $record->LoanAcc . $record->LoanChd,
                    'Contract Type' => $productTypeDesc,
                    'Contract Phase' => $this->getContractPhaseCode($record->AccStatus ?? '', $record->MatDate, $record->AccStatusDate),
                    'Currency' => $record->CcyType ?? '',
                    'Original Currency' => $record->CcyType ?? '',
                    'Contract Start Date' => $record->OpenDate ? date('dmY', strtotime($record->OpenDate)) : '',
                    'Contract End Planned Date' => $record->MatDate ? date('dmY', strtotime($record->MatDate)) : '',
                    'Contract End Actual Date' => $record->AccStatusDate ? date('dmY', strtotime($record->AccStatusDate)) : '',
                    'Financed Amount' => $record->GrantedAmt ?? 0,
                    'Installments Number' => $record->InstNo ?? 0,
                    'Transaction Type / Sub-facility' => 'NA',
                    'Payment Periodicity' => $this->getPaymentPeriodicityCode($record->FreqType ?? '', $record->InstNo ?? 0),
                    'Monthly Payment Amount' => $record->FixAmt ?? 0,
                    'Last payment amount' => $record->TrnAmt ?? 0,
                    'Next Payment Date' => !empty($record->NextInstDueDate) ? date('dmY', strtotime($record->NextInstDueDate)) : '',
                    'Outstanding Payments Number' => $record->UnExInstNo ?? 0,
                    'Outstanding Balance' => round(($record->BalAmt ?? 0) / 100),
                    'Overdue Payments Number' => isset($record->OverdueInstCount) ? (int)$record->OverdueInstCount : 0,
                    'Overdue Payments Amount' => round((($record->OdueIntAmt ?? 0) + ($record->OduePriAmt ?? 0)) / 100),
                    'Overdue Days' => $record->LateDaysNo ?? 0,
                    'Credit Limit' => $record->GrantedAmtOrig ?? 0
                ];

                try {
                    foreach ($requiredFields as $field) {
                        $value = $comprehensiveData[$field] ?? '';
                        $sheet->setCellValueByColumnAndRow($col, $row, $value);
                        $col++;
                    }
                    $row++;

                    if ($processedCount % 50 == 0) {
                        Log::info('Processed ' . $processedCount . ' of ' . $totalCount . ' records');
                    }
                } catch (\Exception $e) {
                    Log::error('Error processing record ' . $processedCount . ': ' . $e->getMessage());
                    continue;
                }
            }

            // Auto-size columns
            foreach (range('A', \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($requiredFields))) as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Apply borders to all data
            $dataRange = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(count($requiredFields)) . ($row - 1);
            $sheet->getStyle($dataRange)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            // Save file
            $filename = 'microbanker_data_export_' . date('Y-m-d_H-i-s') . '.xlsx';
            $writer = new Xlsx($spreadsheet);
            $filePath = storage_path('app/temp/' . $filename);

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $writer->save($filePath);

            Log::info('Export completed successfully. File saved to: ' . $filePath);
            Log::info('Total records processed: ' . $processedCount);

            return response()->download($filePath, $filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Export failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Export failed: ' . $e->getMessage() . ' - Check logs for details');
        }
    }

    private function getLookupValue($lookupId, $lookupCode)
    {
        try {
            $result = DB::connection('sqlsrv2')->select("
                SELECT FullDesc
                FROM Microbanker.dbo.USERLOOKUP
                WHERE LookUpId = ? AND LookUpCode = ?
            ", [$lookupId, $lookupCode]);

            return $result[0]->FullDesc ?? $lookupCode;
        } catch (\Exception $e) {
            return $lookupCode;
        }
    }

    private function getTitleFromConfig($titleCode)
    {
        if (empty($titleCode)) {
            return '';
        }

        $titleConfig = \App\Models\TitleConfiguration::where('title_code', trim($titleCode))->first();

        if ($titleConfig) {
            return $titleConfig->title;
        }

        // If no database configuration found, return the original code
        return $titleCode;
    }

    private function getGenderFromConfig($genderType)
    {
        if (empty($genderType)) {
            return '';
        }

        $genderConfig = \App\Models\GenderConfiguration::where('gender_code', trim($genderType))->first();

        if ($genderConfig) {
            return $genderConfig->gender;
        }

        // If no database configuration found, return the original code
        return $genderType;
    }

    private function getGenderCode($genderType)
    {
        // Map gender type codes to gender codes as per requirements
        $genderMapping = [
            '000' => 'Others',
            '001' => 'M', // Male
            '002' => 'F', // Female
        ];

        return $genderMapping[$genderType] ?? $genderType;
    }

    private function getCivilStatusCode($civilStatusCode)
    {
        // Map civil status codes to numeric codes as per requirements
        $civilStatusMapping = [
            'D00' => '3', // Divorced
            'M00' => '2', // Married
            'S00' => '1', // Single
            'SEP' => '3', // Separated
            'W00' => '4', // Widowed
        ];

        return $civilStatusMapping[$civilStatusCode] ?? $civilStatusCode;
    }

    private function getTitleAcronymCode($titleCode)
    {
        // Map title codes to numeric acronym codes as per requirements
        $titleMapping = [
            '000' => '', // Unknown
            '001' => '10', // Mr
            '002' => '11', // Ms
            '003' => '13', // Mrs
            '004' => '14', // Dr
            '005' => '15', // Prof
            '006' => '', // Atty (no code specified)
            '007' => '21', // Rev
            '008' => '21', // Fr
            '009' => '16', // Hon
            '010' => '10', // Engr
            '011' => '12', // Sr
        ];

        return $titleMapping[$titleCode] ?? $titleCode;
    }

    private function getAddressTypeCode($addrType)
    {
        // Map address type codes to address type codes as per requirements
        $addressTypeMapping = [
            '0' => 'MI', // Main
            '1' => 'AI', // Alternate
        ];

        return $addressTypeMapping[$addrType] ?? $addrType;
    }

    private function getContactTypeCode($contactType)
    {
        // Map contact types to numeric codes as per requirements
        $contactTypeMapping = [
            'Mobile' => '3',
            'Email' => '7',
        ];

        return $contactTypeMapping[$contactType] ?? $contactType;
    }

    private function getContractPhaseCode($accStatus, $matDate, $accStatusDate)
    {
        // Map account status to contract phase codes as per requirements
        if ($accStatus == '01') {
            return 'AC'; // Active
        } elseif ($accStatus == '99') {
            // Check if closed in advance
            if ($matDate && $accStatusDate && strtotime($matDate) < strtotime($accStatusDate)) {
                return 'CA'; // Closed in Advance
            } else {
                return 'CL'; // Closed
            }
        }

        return $accStatus; // Return original if no mapping found
    }

    private function getPaymentPeriodicityCode($freqType, $instNo)
    {
        // If installment number is 1, return P
        if ($instNo == 1) {
            return 'P';
        }

        // Map frequency type codes to periodicity codes as per requirements
        $periodicityMapping = [
            '001' => 'Y', // Annual
            '002' => 'S', // Semi-Annual
            '003' => 'T', // 3x Per Year
            '004' => 'Q', // Quarterly
            '006' => 'B', // Every 2 months
            '012' => 'M', // Monthly
            '013' => '', // Four Weekly (no code specified)
            '024' => 'F', // Semi-Monthly
            '026' => '', // Every 2 weeks (no code specified)
            '052' => 'W', // Weekly
            '365' => 'D', // Daily
        ];

        return $periodicityMapping[$freqType] ?? $freqType;
    }


    // public function testExport()
    // {
    //     try {
    //         $testConnection = DB::connection('sqlsrv2')->select("SELECT COUNT(*) as count FROM Microbanker.dbo.CIF");

    //         if (empty($testConnection)) {
    //             return response()->json(['error' => 'Database connection failed']);
    //         }

    //         $spreadsheet = new Spreadsheet();
    //         $sheet = $spreadsheet->getActiveSheet();
    //         $sheet->setCellValue('A1', 'Test Export');
    //         $sheet->setCellValue('A2', 'Database Records: ' . $testConnection[0]->count);
    //         $sheet->setCellValue('A3', 'Date: ' . date('Y-m-d H:i:s'));

    //         $writer = new Xlsx($spreadsheet);
    //         $filePath = storage_path('app/temp/test_export.xlsx');

    //         if (!file_exists(storage_path('app/temp'))) {
    //             mkdir(storage_path('app/temp'), 0755, true);
    //         }

    //         $writer->save($filePath);

    //         return response()->download($filePath, 'test_export.xlsx')->deleteFileAfterSend(true);

    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()]);
    //     }
    // }
}


