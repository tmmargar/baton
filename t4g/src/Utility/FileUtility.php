<?php
namespace Baton\T4g\Utility;
abstract class FileUtility {
  public static function getFileNames(string $directory, string $excludeFileNames): string {
    $validFileNames = array_diff(array: scandir(directory: $directory), arrays: $excludeFileNames);
    $result = "";
    foreach ($validFileNames as $fileName) {
      $name = explode(separator: ".", string: $fileName);
      $result .= "<br/>" . HtmlUtility::buildLink(href: (is_dir(filename: $name[0]) ? $name[0] . "\MainTest.class.php" : $fileName), target: NULL, title: $name[0], text: $name[0]);
    }
    return $result;
  }
}