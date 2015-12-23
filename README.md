# BrsHtml2Pdf
Common interface for converting HTML documents to PDF using adapters.

## Available adapters

 * Wkhtmltopdf - for debian and ubuntu install: http://download.gna.org/wkhtmltopdf/0.12/0.12.2.1/wkhtmltox-0.12.2.1_linux-precise-amd64.deb. Version available in official packages is obsolete and require xvfb-run.

## Examples

### Example for Wkhtmltopdf adapter
```php
<?php
use Brs\Html2Pdf\Html2Pdf;
use Brs\Html2Pdf\Adapter\Wkhtmltopdf;

// you can optionally test the environment
Wkhtmltopdf::testEnv();

$adapter = new Wkhtmltopdf;

// you can change path to binary on your server
$adapter->getWkhtmltopdfCmd()
    ->setPrefix('/special/path/to/whhtmltopdf')
;

// add wkhtmltopdf parameter
$adapter->getWkhtmltopdfCmd()
    ->add('--ignore-load-errors')
    ->add('--lowquality')
;

// if you must to use xvfb-run you can configure it
$adapter->getXvfbCmd()
    ->setPrefix('/special/path/to/xvfb-run')
    ->setArguments(['--server-args=-screen 0, 800x600x24'])
;

$converter = new Html2Pdf($adapter);

// set html file to convert
$converter->setHtmlFile('path/to/document.html');
// or html string
$converter->setHtmlDocument('
<html>
    <body>
        <strong>test pdf</strong>
    </body>
</html>
');
// or url
$converter->setUrl('http://google.com');

// generate the pdf file
$pdf = $converter->getPdfFile();

// send to client for download
$pdf->sendToBrowser('file.pdf');

// or save as local file
$pdf->saveAs('some/path/to/file.pdf');
```

## Requirments

 * PHP 5.4 or higher
