<?php

namespace Drenso\Shared\Helper;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class SpreadsheetHelper
{

  /** @var TranslatorInterface */
  private $translator;

  /**
   * SpreadsheetHelper constructor.
   *
   * @param TranslatorInterface $translator
   */
  public function __construct(TranslatorInterface $translator)
  {
    $this->translator = $translator;
  }

  /**
   * Create an Excel response from a spreadsheet
   *
   * @param Spreadsheet $spreadsheet
   * @param string      $filename
   *
   * @return StreamedResponse
   */
  public function createExcelResponse(Spreadsheet $spreadsheet, string $filename)
  {
    // Create writer
    $writer   = new Xlsx($spreadsheet);
    $response = new StreamedResponse(
        function () use ($writer) {
          $writer->save('php://output');
        });

    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
    self::contentDisposition($response, $filename . '.xlsx');

    return $response;
  }

  /**
   * Create a CSV response from a spreadsheet
   *
   * @param Spreadsheet $spreadsheet
   * @param string      $filename
   *
   * @return StreamedResponse
   */
  public function createCsvResponse(Spreadsheet $spreadsheet, string $filename)
  {
    $writer = (new Csv($spreadsheet))
        ->setDelimiter(';')
        ->setEnclosure('')
        ->setUseBOM(true)
        ->setSheetIndex(0);

    $response = new StreamedResponse(function () use ($writer) {
      $writer->save('php://output');
    });
    $response->headers->set('Content-Type', 'application/csv; charset=utf-8');
    self::contentDisposition($response, $filename . '.csv');

    return $response;
  }

  /**
   * Creates a new sheet with the specified name
   *
   * @param Spreadsheet $spreadsheet
   * @param string      $name
   *
   * @return Worksheet
   *
   * @throws Exception
   */
  public function createSheet(Spreadsheet $spreadsheet, string $name): Worksheet
  {
    $sheet = new Worksheet($spreadsheet, $this->translator->trans($name));
    $spreadsheet->addSheet($sheet);

    return $sheet;
  }

  /**
   * @param Worksheet $sheet
   * @param int       $column
   * @param int       $row
   * @param bool      $value
   * @param bool      $bold
   */
  public function setCellBooleanValue(Worksheet &$sheet, int $column, int $row, bool $value, bool $bold = false)
  {
    $this->setCellTranslatedValue($sheet, $column, $row, $value ? 'excel.boolean.yes' : 'excel.boolean.no', $bold, 'drenso_shared');
  }

  /**
   * @param Worksheet $sheet
   * @param int       $column
   * @param int       $row
   * @param string    $value
   * @param bool      $bold
   * @param string    $translationDomain
   */
  public function setCellTranslatedValue(Worksheet &$sheet, int $column, int $row, string $value, bool $bold = false, string $translationDomain = 'messages')
  {
    $this->setCellValue($sheet, $column, $row, $this->translator->trans($value, [], $translationDomain), $bold);
  }

  /**
   * @param Worksheet $sheet
   * @param int       $column
   * @param int       $row
   * @param mixed     $value
   * @param bool      $bold
   */
  public function setCellValue(Worksheet &$sheet, int $column, int $row, $value, bool $bold = false)
  {
    $sheet->setCellValueByColumnAndRow($column, $row, $value);

    if ($bold) {
      $sheet->getStyleByColumnAndRow($column, $row)->getFont()->setBold(true);
    }
  }

  /**
   * @param Worksheet $sheet
   * @param int       $column
   * @param int       $row
   * @param string    $value
   *
   * @throws Exception
   */
  public function setCellExplicitString(Worksheet &$sheet, int $column, int $row, string $value)
  {
    $cell = $sheet->getCellByColumnAndRow($column, $row);
    $cell->setValueExplicit($value, DataType::TYPE_STRING);
  }

  /**
   * @param Worksheet         $sheet
   * @param int               $column
   * @param int               $row
   * @param DateTimeInterface $dateTime
   * @param bool              $leftAligned
   * @param bool              $bold
   */
  public function setCellDateTime(Worksheet &$sheet, int $column, int $row, DateTimeInterface $dateTime, bool $leftAligned = false, bool $bold = false)
  {
    $this->setCellValue($sheet, $column, $row, Date::PHPToExcel($dateTime), $bold);
    $sheet->getStyleByColumnAndRow($column, $row)->getNumberFormat()->setFormatCode('dd/mm/yyyy hh:mm');

    if ($leftAligned) {
      $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }
  }

  /**
   * @param Worksheet         $sheet
   * @param int               $column
   * @param int               $row
   * @param DateTimeInterface $dateTime
   * @param bool              $leftAligned
   * @param bool              $bold
   */
  public function setCellDate(Worksheet &$sheet, int $column, int $row, DateTimeInterface $dateTime, bool $leftAligned = false, bool $bold = false)
  {
    $this->setCellValue($sheet, $column, $row, Date::PHPToExcel($dateTime), $bold);
    $sheet->getStyleByColumnAndRow($column, $row)->getNumberFormat()->setFormatCode('dd/mm/yyyy');

    if ($leftAligned) {
      $sheet->getStyleByColumnAndRow($column, $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }
  }

  /**
   * @param Worksheet $sheet
   * @param int       $column
   * @param int       $row
   * @param           $value
   * @param bool      $bold
   */
  public function setCellCurrency(Worksheet &$sheet, int $column, int $row, $value, bool $bold = false)
  {
    $this->setCellValue($sheet, $column, $row, $value, $bold);
    $sheet->getStyleByColumnAndRow($column, $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_EUR);
  }

  public static function convertToLocalTimezone(DateTimeInterface $dateTime): DateTimeInterface
  {
    $timezone = (new DateTime())->getTimezone();

    if (PHP_MAJOR_VERSION >= 8) {
      /** @noinspection PhpElementIsNotAvailableInCurrentPhpVersionInspection */
      /** @phan-suppress-next-line PhanUndeclaredStaticMethod */
      return DateTime::createFromInterface($dateTime)->setTimezone($timezone);
    } else if ($dateTime instanceof DateTime) {
      return (clone $dateTime)->setTimezone($timezone);
    } else if ($dateTime instanceof DateTimeImmutable) {
      return $dateTime->setTimezone($timezone);
    }

    throw new RuntimeException('Local timezone could not be set');
  }

  /**
   * Create a correct content disposition
   *
   * @param Response $response
   * @param string   $filename
   */
  public static function contentDisposition(Response $response, string $filename): void
  {
    // Set locale required for the iconv conversion to work correctly
    setlocale(LC_CTYPE, 'en_US.UTF-8');
    $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        self::sanitizeFilename($filename)
    ));
  }

  /**
   * @param string $filename
   *
   * @return false|mixed|string|string[]|null
   */
  private static function sanitizeFilename(string $filename)
  {
    return mb_strtolower(preg_replace('/[^A-Z\d.]/ui', '_', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $filename)));
  }

}
