<?php

namespace app\controllers;
use Yii;

class MigrationController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMigrate()
    {
        $oldApp = \Yii::$app;

        // fcgi doesn't have STDIN and STDOUT defined by default
        defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
        defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));

        /** @noinspection PhpIncludeInspection */
	$config = require(__DIR__ . '/../config/console.php');
        /*$config = \yii\helpers\ArrayHelper::merge(
            require(__DIR__ . '/../config/console.php')
        );*/

        $consoleApp = new \yii\console\Application($config);
        $controllerNamespace=null;
        $params=[];
        $route = 'migrate/up';

        if (!is_null($controllerNamespace)) {
            $consoleApp->controllerNamespace = $controllerNamespace;
        }

        try {
            // use current connection to DB
            \Yii::$app->set('db', $oldApp->db);

            ob_start();

            $exitCode = $consoleApp->runAction(
                $route,
                ['interactive' => false, 'color' => false]
            );

            $result = ob_get_clean();

            \Yii::trace($result, 'console');

        } catch (\Exception $e) {
            \Yii::warning($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine(), 'console');
            //echo $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
            $exitCode = 1;
        }

        \Yii::$app = $oldApp;

        return $exitCode;
    }

}
