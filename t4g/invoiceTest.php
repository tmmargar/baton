<?php
namespace Baton\T4g;
use Konekt\PdfInvoice\InvoicePrinter;
require_once "bootstrap.php";
// include "../src/InvoicePrinter.php";
// size: A4/Letter/Legal, currency: any string, language: any language in inc/languages folder
$invoicePrinter = new InvoicePrinter(size: "Letter", currency: "$", language: "en");
// decimals_sep: any string, thousands_sep: any string, alignment: left/right, space: true/false, negativeParenthesis: true/false
$invoicePrinter->setNumberFormat(decimals_sep: ".", thousands_sep: ",", alignment: "left", space: false, negativeParenthesis: true);
// custom color
$invoicePrinter->setColor(rgbcolor: "#007fff");
// maxWidth: optional #, maxHeight; optional #)
$invoicePrinter->setLogo(logo: "images/sample1.jpg");
// title shown in upper right corner
$invoicePrinter->setType(title: "Invoice");
// invoice # shown in upper right corner
$invoicePrinter->setReference(reference: "INV-55033645");
$invoicePrinter->setDate(date: date("M dS ,Y", time()));
$invoicePrinter->setTime(time: date("h:i:s A", time()));
$invoicePrinter->setDue(date: date("M dS ,Y", strtotime("+3 months")));
//$invoicePrinter->addCustomHeader(title: "title here", content: "content here");
// company details (array first element will be bolded can have as many lines as needed)
$invoicePrinter->setFrom(["Seller Name", "Sample Company Name", "128 AA Juanita Ave", "Glendora , CA 91740", "United States of America"]);
// client details (array first element will be bolded can have as many lines as needed)
$invoicePrinter->setTo(["Purchaser Name", "Sample Company Name", "128 AA Juanita Ave", "Glendora , CA 91740", "United States of America"]);
// switch horizontal position of company and client (default is company on left)
//$invoicePrinter->flipflop();
// hide issuer and client header row
//$invoicePrinter->hideToFromHeaders();
// item, description: use <br> or \n for line break, quantity: #, vat: string percent or actual # amount, price: #, discount: string percent or actual # amount, total: #
// only name required pass false to disable any other options
$invoicePrinter->addItem(item: "AMD Athlon X2DC-7450", description: "2.4GHz/1GB/160GB/SMP-DVD/VB", quantity: 6, vat: false, price: 580, discount: false, total: 3480);
$invoicePrinter->addItem(item: "PDC-E5300", description: "2.6GHz/1GB/320GB/SMP-DVD/FDD/VB", quantity: 4, vat: false, price: 645, discount: false, total: 2580);
// description font size default is 7
//$invoicePrinter->setFontSizeProductDescription(9);
// alignment: horizontal/vertical
$invoicePrinter->setTotalsAlignment(alignment: "horizontal");
// add row for calculations/totals unlimited # of rows
// name, value, colored: true/false true set theme color as background color of row
$invoicePrinter->addTotal(name: "Total due", value: 16846.6, colored: true);
// add badge below products/services (e.g. show paid)
// badge, color: hex code
$invoicePrinter->addBadge(badge: "Payment Paid");
// add title/paragraph at bottom such as payment details or shipping info
$invoicePrinter->addTitle(title: "Important Notice");
// add title/paragraph at bottom such as payment details or shipping info
$invoicePrinter->addParagraph(paragraph: "No item will be replaced or refunded if you don\"t have the invoice with you. You can refund within 2 days of purchase.");
// override term from language file
//$invoicePrinter->changeLanguageTerm(term: "date", new: "Confirmation Date");
// small text to display in bottom left corder
$invoicePrinter->setFooternote(note: "My Company Name Here");
// name, destination: I => Display on browser, D => Force Download, F => local path save, S => return document path
$invoicePrinter->render(name: "example" . date("mdyhis") . ".pdf", destination: "D");