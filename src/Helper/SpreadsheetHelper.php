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
use ZipStream\Option\Archive;
use ZipStream\ZipStream;

class SpreadsheetHelper
{
  /** SpreadsheetHelper constructor. */
  public function __construct(private readonly ?TranslatorInterface $translator)
  {
  }

  /** Create an Excel response from a spreadsheet. */
  public function createExcelResponse(Spreadsheet $spreadsheet, string $filename): StreamedResponse
  {
    // Create writer
    $writer   = new Xlsx($spreadsheet);
    $response = new StreamedResponse(fn () => $writer->save('php://output'));

    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
    self::contentDisposition($response, $filename . '.xlsx');

    return $response;
  }

  /** Create a CSV response from a spreadsheet. */
  public function createCsvResponse(Spreadsheet $spreadsheet, string $filename): StreamedResponse
  {
    $writer = (new Csv($spreadsheet))
        ->setDelimiter(';')
        ->setEnclosure('')
        ->setUseBOM(true)
        ->setSheetIndex(0);

    $response = new StreamedResponse(fn () => $writer->save('php://output'));
    $response->headers->set('Content-Type', 'application/csv; charset=utf-8');
    self::contentDisposition($response, $filename . '.csv');

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
        function () use ($spreadSheets) {
          // Create the archive
          $zipOptions = new Archive();
          $zipOptions->setSendHttpHeaders(true);
          $zip = new ZipStream(null, $zipOptions);

          // Loop the supplied spreadsheets
          foreach ($spreadSheets as $spreadSheet) {
            $writer   = new Xlsx($spreadSheet['sheet']);
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

  /**
   * Creates a new sheet with the specified name.
   *
   * @throws Exception
   */
  public function createSheet(Spreadsheet $spreadsheet, string $name): Worksheet
  {
    $sheet = new Worksheet($spreadsheet, $this->translator?->trans($name) ?? $name);
    $spreadsheet->addSheet($sheet);

    return $sheet;
  }

  public function setCellBooleanValue(Worksheet &$sheet, int $column, int $row, bool $value, bool $bold = false): void
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
    $sheet->setCellValueByColumnAndRow($column, $row, $value);

    if ($bold) {
      $sheet->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
    }
  }

  public function setCellMultilineValue(Worksheet $sheet, int $column, int $row, array $lines, bool $bold = false): void
  {
    $this->setCellValue($sheet, $column, $row, implode("\n", $lines), $bold);
    $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setWrapText(true);
    $sheet->getStyle(new RowRange($row))->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
  }

  /** @throws Exception */
  public function setCellExplicitString(Worksheet $sheet, int $column, int $row, string $value): void
  {
    $cell = $sheet->getCellByColumnAndRow($column, $row);
    $cell->setValueExplicit($value, DataType::TYPE_STRING);
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
    $sheet->getStyleByColumnAndRow($column, $row)->getNumberFormat()->setFormatCode('dd/mm/yyyy hh:mm');

    if ($leftAligned) {
      $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
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
    $sheet->getStyleByColumnAndRow($column, $row)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

    if ($leftAligned) {
      $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }
  }

  public function setCellCurrency(Worksheet $sheet, int $column, int $row, $value, bool $bold = false): void
  {
    $this->setCellValue($sheet, $column, $row, $value, $bold);
    $sheet->getStyleByColumnAndRow($column, $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_EUR);
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

  /** @return false|string|string[]|null */
  private static function sanitizeFilename(string $filename): string|array|false|null
  {
    return mb_strtolower(preg_replace('/[^A-Z\d.]/ui', '_', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $filename)));
  }
}
