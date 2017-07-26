<?php

$this->title = 'syncSources';
$this->params['breadcrumbs'][] = $this->title;

//header('X-Accel-Buffering: no');

$cmd = "ping -c 1 127.0.0.1";

//while (@ ob_end_flush()); // end all output buffers if any

$proc = popen($cmd, 'r');
echo '<pre>';
while (!feof($proc))
{
    echo fread($proc, 4096);
//    @ flush();
}
echo '</pre>';
