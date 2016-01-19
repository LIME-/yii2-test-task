<?php

namespace app\controllers;

use app\services\FactorizationService;
use yii\base\Exception;
use yii\caching\Cache;
use yii\web\Controller;

class FactorizationController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProcess($num, FactorizationService $factorizationService, Cache $cache)
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
            if (!$factors = $cache->get('factorization:' . $num)) {
                $factors = $factorizationService->trialDivision((int)$num);
                $cache->set('factorization:' . $num, $factors);
            }
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }
        return json_encode(['data' => $factors, 'error' => $errorMsg], JSON_UNESCAPED_UNICODE);
    }

}
