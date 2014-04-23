<?php
$sourceBaseDir = dirname(__FILE__);
$sourceNoEcho = true;
echo CSource::printSource($sourceBaseDir, $sourceNoEcho, $this->pr->data['style']);