<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Html2Pdf\Adapter;

use Brs\Stdlib\Console\CmdExec;
use Brs\Stdlib\File\FileInterface;
use Brs\Stdlib\File\Type\Pdf as PdfFile;
use Brs\Html2Pdf\Exception;

$testWkhtmltopdf = new CmdExec('which wkhtmltopdf');
if (empty($testWkhtmltopdf->execute()->getStdoutBuffer())) {
    throw new Exception\RuntimeException('wkhtmltopdf command not found on this server - install it before using this adapter');
}
$testXvfb = new CmdExec('which xvfb-run');
if (empty($testXvfb->execute()->getStdoutBuffer())) {
    throw new Exception\RuntimeException('xvfb command not found on this server - install it before using this adapter');
}

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
class Wkhtmltopdf implements AdapterInterface
{
    // protected $shellCmd = 'xvfb-run --server-args="-screen 0, 1024x768x24" wkhtmltopdf --ignore-load-errors %s %s';
    protected $shellCmd = 'xvfb-run --server-args="-screen 0, 1024x768x24" wkhtmltopdf %s %s';

    public function convert(FileInterface $inputFile)
    {
        // file must by saved
        $inputFile->save();
        // wkhtmtopdf doesn't handle local files with non-typical extensions
        $inputFile->saveAs($inputFile->getPath() . '.html');

        $outputFile = new PdfFile;
        $cmd = new CmdExec($this->shellCmd, $inputFile->getPath(), $outputFile->getPath());

        if (! $cmd->execute()->isSuccess()) {
            throw new Exception\RuntimeException($cmd->getStdoutBuffer());
        }

        return $outputFile;
    }
}