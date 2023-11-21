<?php

namespace Drenso\Shared\Helper;

use DateTimeInterface;
use PhpOffice\PhpSpreadsheet\Cell\CellAddress;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\RowRange;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use ZipStream\CompressionMethod;
use ZipStream\ZipStream;

class SpreadsheetHelper
{
  public function __construct(private readonly ?TranslatorInterface $translator)
  {
  }

  /** Create an Excel response from a spreadsheet. */
  public function createExcelResponse(Spreadsheet $spreadsheet, string $filename): StreamedResponse
  {
    $writer = $this->createXlsxWriter($spreadsheet);

    $response = new StreamedResponse(fn () => $writer->save('php://output'));
    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
    self::contentDisposition($response, $filename . '.xlsx');

    return $response;
  }

  /**
   * Create a CSV response from a spreadsheet. If the Spreadsheet contains multiple worksheets, only the first sheet
   * will be exported. When the `multipleWorksheetsAsZipArchive` is set to true, all available sheets will be converted
   * into a dedicated CSV file and packed into a single ZIP archive.
   */
  public function createCsvResponse(
    Spreadsheet $spreadsheet,
    string $filename,
    bool $multipleWorksheetsAsZipArchive = false): StreamedResponse
  {
    if ($multipleWorksheetsAsZipArchive && $spreadsheet->getSheetCount() > 1) {
      return $this->createZippedCsvResponse($spreadsheet, $filename);
    }

    $writer = $this->createCsvWriter($spreadsheet);

    $response = new StreamedResponse(fn () => $writer->save('php://output'));
    $response->headers->set('Content-Type', 'application/csv; charset=utf-8');
    self::contentDisposition($response, $filename . '.csv');

    return $response;
  }

  /** Create a ZIP response containing a CSV file for each worksheet */
  public function createZippedCsvResponse(Spreadsheet $spreadsheet, string $zipName): StreamedResponse
  {
    $response = new StreamedResponse(
      function () use ($spreadsheet): void {
        // Create the archive
        $zip = new ZipStream(
          defaultCompressionMethod: CompressionMethod::STORE,
          defaultEnableZeroHeader: false,
          sendHttpHeaders: false
        );

        // Loop the spreadsheet worksheets
        for ($i = 0; $i < $spreadsheet->getSheetCount(); ++$i) {
          $writer   = $this->createCsvWriter($spreadsheet, $i);
          $tempFile = @tempnam(File::sysGetTempDir(), 'phpxltmp');
          $writer->save($tempFile);
          $zip->addFileFromPath(self::sanitizeFilename($spreadsheet->getSheet($i)->getTitle() . '.csv'), $tempFile);
        }

        // Finalize the zip file
        $zip->finish();
      });

    $response->headers->set('Content-Type', 'application/octet-stream; charset=utf-8');
    self::contentDisposition($response, $zipName . '.zip');

    return $response;
  }

  /**
   * Create a ZIP response container multiple Excel spreadsheets.
   *
   * @param array $spreadSheets array with ['sheet' => Spreadsheet object, 'filename' => string filename]
   */
  public function createZippedExcelResponse(array $spreadSheets, string $zipName): StreamedResponse
  {
    $response = new StreamedResponse(
      function () use ($spreadSheets): void {
        // Create the archive
        $zip = new ZipStream(
          defaultCompressionMethod: CompressionMethod::STORE,
          defaultEnableZeroHeader: false,
          sendHttpHeaders: false,
        );

        // Loop the supplied spreadsheets
        foreach ($spreadSheets as $spreadSheet) {
          $writer   = $this->createXlsxWriter($spreadSheet['sheet']);
          $tempFile = @tempnam(File::sysGetTempDir(), 'phpxltmp');
          $writer->save($tempFile);
          $zip->addFileFromPath(self::sanitizeFilename($spreadSheet['filename'] . '.xlsx'), $tempFile);
        }

        // Finalize the zip file
        $zip->finish();
      });

    $response->headers->set('Content-Type', 'application/octet-stream; charset=utf-8');
    self::contentDisposition($response, $zipName . '.zip');

    return $response;
  }

  /** Create a default CSV writer */
  public function createCsvWriter(Spreadsheet $spreadsheet, int $sheetIndex = 0): Csv
  {
    return (new Csv($spreadsheet))
      ->setDelimiter(';')
      ->setEnclosure('')
      ->setUseBOM(true)
      ->setSheetIndex($sheetIndex);
  }

  /** Create a default Xlsx writer */
  public function createXlsxWriter(Spreadsheet $spreadsheet): Xlsx
  {
    return new Xlsx($spreadsheet);
  }

  /**
   * Creates a new sheet with the specified name.
   *
   * @throws Exception
   */
  public function createSheet(Spreadsheet $spreadsheet, string $name): Worksheet
  {
    $sheet = new Worksheet($spreadsheet, self::sanitizeSheetName($this->translator?->trans($name) ?? $name));
    $spreadsheet->addSheet($sheet);

    return $sheet;
  }

  public function setCellBooleanValue(Worksheet $sheet, int $column, int $row, bool $value, bool $bold = false): void
  {
    $this->setCellTranslatedValue($sheet, $column, $row, $value ? 'excel.boolean.yes' : 'excel.boolean.no', $bold, 'drenso_shared');
  }

  public function setCellTranslatedValue(
    Worksheet $sheet,
    int $column,
    int $row,
    string $value,
    bool $bold = false,
    string $translationDomain = 'messages'): void
  {
    $this->setCellValue($sheet, $column, $row, $this->translator?->trans($value, [], $translationDomain) ?? $value, $bold);
  }

  public function setCellValue(Worksheet $sheet, int $column, int $row, mixed $value, bool $bold = false): void
  {
    $coordinate = CellAddress::fromColumnAndRow($column, $row);
    $sheet->setCellValue($coordinate, $value);

    if ($bold) {
      $sheet->getStyle($coordinate)->getFont()->setBold(true);
    }
  }

  public function setCellMultilineValue(Worksheet $sheet, int $column, int $row, array $lines, bool $bold = false): void
  {
    $this->setCellValue($sheet, $column, $row, implode("\n", $lines), $bold);
    $sheet->getStyle(CellAddress::fromColumnAndRow($column, $row))->getAlignment()->setWrapText(true);
    $sheet->getStyle(new RowRange($row))->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
  }

  /** @throws Exception */
  public function setCellExplicitString(Worksheet $sheet, int $column, int $row, string $value, bool $bold = false): void
  {
    $coordinate = CellAddress::fromColumnAndRow($column, $row);
    $sheet->getCell($coordinate)->setValueExplicit($value, DataType::TYPE_STRING);

    if ($bold) {
      $sheet->getStyle($coordinate)->getFont()->setBold(true);
    }
  }

  public function setCellDateTime(
    Worksheet $sheet,
    int $column,
    int $row,
    ?DateTimeInterface $dateTime,
    bool $leftAligned = false,
    bool $bold = false): void
  {
    if ($dateTime !== null) {
      $this->setCellValue($sheet, $column, $row, Date::PHPToExcel($dateTime), $bold);
    }

    $coordinate = CellAddress::fromColumnAndRow($column, $row);
    $sheet->getStyle($coordinate)->getNumberFormat()->setFormatCode('dd/mm/yyyy hh:mm');

    if ($leftAligned) {
      $sheet->getStyle($coordinate)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }
  }

  public function setCellDate(
    Worksheet $sheet,
    int $column,
    int $row,
    ?DateTimeInterface $dateTime,
    bool $leftAligned = false,
    bool $bold = false): void
  {
    if ($dateTime !== null) {
      $this->setCellValue($sheet, $column, $row, Date::PHPToExcel($dateTime), $bold);
    }
    $coordinate = CellAddress::fromColumnAndRow($column, $row);
    $sheet->getStyle($coordinate)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

    if ($leftAligned) {
      $sheet->getStyle($coordinate)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }
  }

  public function setCellCurrency(Worksheet $sheet, int $column, int $row, mixed $value, bool $bold = false): void
  {
    $this->setCellValue($sheet, $column, $row, $value, $bold);
    $sheet->getStyle(CellAddress::fromColumnAndRow($column, $row))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_EUR);
  }

  /** Create a correct content disposition. */
  public static function contentDisposition(Response $response, string $filename): void
  {
    // Set locale required for the iconv conversion to work correctly
    setlocale(LC_CTYPE, 'en_US.UTF-8');
    $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
      ResponseHeaderBag::DISPOSITION_ATTACHMENT,
      self::sanitizeFilename($filename)
    ));
  }

  public static function sanitizeSheetName(string $sheetName): string
  {
    return str_replace(Worksheet::getInvalidCharacters(), '_', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $sheetName));
  }

  public static function sanitizeFilename(string $filename): string
  {
    return mb_strtolower(preg_replace('/[^A-Z\d.]/ui', '_', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $filename)));
  }
}
