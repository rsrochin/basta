<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'ppi/PPI/init.php';

$siteManager            = new App\SiteManager();
$app                    = new PPI\App();
$app->ds                = false;
$app->siteMode          = $siteManager->getSiteMode();
$app->configBlock       = $siteManager->getConfigBlock();
$app->configFile        = $siteManager->getConfigFile();
$app->dsConnectionsPath = $siteManager->getConnectionsPath();
$app->boot();
$app->dispatch();