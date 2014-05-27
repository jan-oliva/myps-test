<?php

namespace controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class BuildController extends BaseController
{

    private $reportsDir;

    private $xslDir;

    private $reportTypes;

    public function __construct()
    {
        $this->reportsDir = './builds/reports/';
        $this->xslDir = './silex/views/build/report/';
        $this->reportTypes = array(
            'docs' => -1,
            'apigen.xml' => -1,
            'phpcpd.xml' => -1,
            'phpcs.xml' => -1,
            'phplint.txt' => -1,
            'phpmd.xml' => -1
        );
    }

    public function renderRun(Application $app, Request $req)
    {
        $path = $req->get('path');
        $command = sprintf('sudo -i -n -u vagrant bash -c "cd project && phing -verbose -logger phing.listener.DefaultLogger -Dpath=%s analyze 2>&1"', escapeshellarg($path));
        return $this->execCommand($app, $command);
    }

    public function renderList(Application $app)
    {
        $dir = $this->reportsDir;
        $reports = $this->reportTypes;

        $dirs = array();
        if (is_dir($dir)) {
            $maxReports = count($reports);
            foreach (new \FilesystemIterator($dir) as $fileInfo) {
                $itReports = new \FilesystemIterator($fileInfo->getPathname());
                $numReports = iterator_count($itReports);
                $dirs[$fileInfo->getFileName()] = array(
                    'time' => date("d.m.Y H:i:s", $fileInfo->getCTime()),
                    'count' => $numReports,
                    'max' => $maxReports
                );
            }
            arsort($dirs);
        }

        return $app['twig']->render('build/list.html.twig', array(
                'dirs' => $dirs
        ));
    }

    public function renderDelete($dir, Application $app)
    {
        $this->rrmdir($this->reportsDir . $dir);
        return $app->redirect('/build/list/');
    }

    public function renderReports($dir, Application $app)
    {
        return $app['twig']->render('build/reports.html.twig', array(
                'dir' => $dir,
                'reports' => $this->getAllReportStatus($this->reportTypes, $this->reportsDir . $dir)
        ));
    }

    public function renderReport($dir, $file, Application $app)
    {
        $inputFile = $this->reportsDir . $dir . '/' . $file . '.xml';
        switch ($file) {
            case 'phplint':
                $inputFile = pathinfo($inputFile, PATHINFO_DIRNAME) . '/' . pathinfo($inputFile, PATHINFO_FILENAME) . '.txt';
                return file_get_contents($inputFile);
            case 'apigen':
            case 'phpcs':
                $xslFile = $this->xslDir . '/checkstyle.xslt';
                break;
            default:
                $xslFile = $this->xslDir . '/' . $file . '.xslt';
                break;
        }

        if (!is_file($inputFile) || !is_file($xslFile)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Požadovaný soubor neexistuje.');
        }

        return $this->xslTransformation($inputFile, $xslFile);
    }

    private function rrmdir($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $it = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
        rmdir($dir);
    }

    private function xslTransformation($xmlFile, $xslFile)
    {
        $xml = new \DOMDocument('1.0', 'utf-8');
        $xml->load($xmlFile);

        $xsl = new \DOMDocument('1.0', 'utf-8');
        $xsl->load($xslFile);

        $xslt = new \XSLTProcessor();
        $xslt->importStylesheet($xsl);
        return $xslt->transformToXML($xml);
    }

    private function getAllReportStatus($reports, $dir)
    {
        if (!is_dir($dir)) {
            return $reports;
        }

        $it = new \FilesystemIterator($dir, \FilesystemIterator::SKIP_DOTS);
        foreach ($it as $fileInfo) {
            $reports[$fileInfo->getFileName()] = $fileInfo->getSize();
        }

        return $reports;
    }
}
