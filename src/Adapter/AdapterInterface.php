<?php

/**
 * (c) BRS software - Tomasz Borys <t.borys@brs-software.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Brs\Html2Pdf\Adapter;

use Brs\Stdlib\File\FileInterface;

/**
 * @author Tomasz Borys <t.borys@brs-software.pl>
 * @version 1.0
 */
interface AdapterInterface
{
    /**
     * @throws Brs\Html2Pdf\Exception\RuntimeException when environment is not pass the test
     */
    public static function testEnv();

    /**
     * @param FileInterface $file html file to convert
     * @return Brs\Stdlib\File\Type\Pdf
     * @throws Brs\Html2Pdf\Exception\RuntimeException when there is a problem with conversion
     */
    public function convertFile(FileInterface $file);

    /**
     * @param string $url
     * @return Brs\Stdlib\File\Type\Pdf
     * @throws Brs\Html2Pdf\Exception\RuntimeException when there is a problem with conversion
     */
    public function convertUrl($url);
}