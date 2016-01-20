<?php

namespace app\controllers;

use app\services\FactorizationService;
use yii\base\Exception;
use yii\redis\Connection;
use yii\web\Controller;

class FactorizationController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProcess($num, FactorizationService $factorizationService, Connection $redis)
    {

        $errorMsg = $factors = '';

        if (PHP_INT_SIZE === 4) {
            $maxInt = "2147483647";
        } else {
            $maxInt = "9223372036854775807";
        }
        try {
            if (preg_match('#\D#', $num)) {
                throw new Exception('В числе должны быть только цифры.');
            }
            if (0 < strnatcmp((string)$num, (string)$maxInt)) {
                throw new Exception('Слишком большое число.');
            }
            if (!$factors = $redis->hget('factorization', $num)) {
                $factors = implode(',', $factorizationService->trialDivision((int)$num));
                $redis->hset('factorization', $num, $factors);
            }
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }
        return json_encode(['data' => explode(',', $factors), 'error' => $errorMsg], JSON_UNESCAPED_UNICODE);
    }

}
