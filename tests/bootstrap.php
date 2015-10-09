<?php
chdir(__DIR__);
require '../../brs-stdlib/src/TestSuite/BootstrapHelper.php';
Brs\Stdlib\TestSuite\BootstrapHelper::findComposerAutoloader(__DIR__);