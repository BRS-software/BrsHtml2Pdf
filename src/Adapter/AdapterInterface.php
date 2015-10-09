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
     * @param FileInterface $file html file to convert
     * @return Brs\Stdlib\File\Type\Pdf
     * @throws Exception\RuntimeException when there is a problem with conversion
     */
    public function convert(FileInterface $file);
}