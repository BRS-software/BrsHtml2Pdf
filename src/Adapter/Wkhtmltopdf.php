<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Html2Pdf\Adapter;

use Brs\Stdlib\File\FileInterface;
use Brs\Stdlib\File\Type\Pdf as PdfFile;
use Brs\Stdlib\File\Type\Generic as GenericFile;
use Brs\Html2Pdf\Exception;
use Symfony\Component\Process\ProcessBuilder;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
class Wkhtmltopdf implements AdapterInterface
{
    private $xvfbCmd;
    private $wkhtmltopdfCmd;
    private $lastProcess;

    public static function testEnv()
    {
        $test = function ($bin) {
            try {
                $testBin = new ProcessBuilder(['which', $bin]);
                $testBin->getProcess()->mustRun();

            } catch (ProcessFailedException $e) {
                throw new Exception\RuntimeException($bin . ' command not found on this server - install it before using this adapter', 0, $e);
            }
        };
        $test('wkhtmltopdf');
        $test('xvfb-run');
    }

    public function __construct()
    {
        $this->xvfbCmd = new ProcessBuilder();
        $this->xvfbCmd
            ->setPrefix('/usr/bin/xvfb-run')
            ->setArguments(['--server-args=-screen 0, 1024x768x24'])
        ;

        $this->wkhtmltopdfCmd = new ProcessBuilder();
        $this->wkhtmltopdfCmd
            ->setPrefix('/usr/bin/wkhtmltopdf')
        ;
    }

    public function getWkhtmltopdfCmd()
    {
        return $this->wkhtmltopdfCmd;
    }

    public function getXvfbCmd()
    {
        return $this->xvfbCmd;
    }

    public function getLastProcess()
    {
        if (null === $this->lastProcess) {
            throw new Exception\LogicException('Convert not was runned yet');
        }
        return $this->lastProcess;
    }

    public function convertFile(FileInterface $inputFile)
    {
        // file must be saved
        $inputFile->save();

        // wkhtmtopdf doesn't handle local files with non-typical extensions
        $pi = pathinfo($inputFile->getPath());
        if (empty($pi['extension']) || $pi['extension'] !== 'html') {
            $tmp =  new GenericFile;
            $tmp->setContents($inputFile->read());
            $tmp->saveAs($tmp->getPath() . '.html');
            $inputFile = $tmp;
        }
        return $this->convert($inputFile->getPath());
    }

    public function convertUrl($url)
    {
        return $this->convert($url);
    }

    private function convert($input)
    {
        $outputFile = new PdfFile;
        $this->wkhtmltopdfCmd
            ->add($input)
            ->add($outputFile->getPath())
        ;

        $this->lastProcess = new Process($this->xvfbCmd->getProcess()->getCommandLine(). ' ' . $this->wkhtmltopdfCmd->getProcess()->getCommandLine());

        try {
            $this->lastProcess->mustRun();
        } catch (ProcessFailedException $e) {
            throw new Exception\RuntimeException($e->getMessage(), 0, $e);

        }

        return $outputFile;
    }
}