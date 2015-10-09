# BrsHtml2Pdf
Common interface for converting HTML documents to PDF using adapters.

## Available adapters

 * Wkhtmltopdf (required programs installed on the server: xvfb, wkhtmltopdf)

## Examples

```php
<?php
use Brs\Html2Pdf\Html2Pdf;
use Brs\Html2Pdf\Adapter\Wkhtmltopdf;

$adapter = new Wkhtmltopdf;
$converter = new Html2Pdf($adapter);

// set html file to convert
$converter->setHtmlFile('document.html');
// or html string
$converter->setHtmlDocument('
<html>
    <body>
        <strong>test pdf</strong>
    </body>
</html>
');

// generate the pdf file
$pdf = $conv->getPdfFile();

// send to client for download
$pdf->sendToBrowser('file.pdf');

// or save as local file
$pdf->saveAs('some/path/to/file.pdf');
```

## Requirments

 * PHP 5.4 or higher