<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Html2Pdf;

use Brs\Html2Pdf\Exception;
use Brs\Html2Pdf\Adapter\AdapterInterface;
use Brs\Stdlib\File\FileInterface;
use Brs\Stdlib\File\Type\Text as TextFile;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
class Html2Pdf
{
    protected $adapter;
    protected $htmlDocument;
    protected $htmlFile;
    protected $outputFile;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function setHtmlDocument($htmlDocument)
    {
        $this->htmlDocument = $htmlDocument;
        return $this;
    }

    public function getHtmlDocument()
    {
        if (empty($this->htmlDocument)) {
            throw new Exception\RuntimeException('Html document not set');
        }
        return $this->htmlDocument;
    }

    public function setHtmlFile($htmlFile)
    {
        if ($htmlFile instanceof FileInterface) {
            $this->htmlFile = $htmlFile;
        } elseif (is_string($htmlFile)) {
            $this->htmlFile = new TextFile($htmlFile);
            $this->htmlFile->setType(TextFile::TYPE_HTML);
        } else {
            throw new Exception\InvalidArgumentException('Argument must be instanceof Brs\Stdlib\File\Text or path to the file');
        }
        return $this;
    }

    public function getHtmlFile()
    {
        if (! $this->htmlFile) {
            $file = new TextFile();
            $file
                ->setContents($this->getHtmlDocument())
                ->setType(TextFile::TYPE_HTML)
            ;
            $this->setHtmlFile($file);
        }
        return $this->htmlFile;
    }

    public function getPdfFile()
    {
        return $this->adapter->convert($this->getHtmlFile());
    }
}