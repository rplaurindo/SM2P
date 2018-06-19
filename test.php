<?php

require_once('./autoload.php');

use SM2P\SMTP\OutlookStrategy;
use SM2P\SMTPStrategyContext;

$options = array(

);

$strategy = new OutlookStrategy('', $options);
$smtpStrategyContext = new SMTPStrategyContext($strategy);

$strategy->setPassword('');
$strategy->addTo('', '');
$strategy->addTo('');
$strategy->setSubject('subject test');
$strategy->setBody('<b>body test</b>');

if ($smtpStrategyContext->send()) {
    echo "email sent.\n";
} else {
    echo "email doesn\'t sent.\n";
}
