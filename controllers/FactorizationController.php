<?php

namespace app\controllers;

use app\services\FactorizationService;
use yii\base\Exception;
use yii\redis\Connection;
use yii\web\Controller;
use yii\web\Response;

class FactorizationController extends Controller
{
    const MAX_HASH_LEN = 3;

    const HASH_KEY = 'factorization';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProcess($num, FactorizationService $factorizationService, Connection $redis, Response $response)
    {
        $factors = $errorMsg = '';
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
            if ($factors = $redis->hget(self::HASH_KEY, $num)){
                $factors = unserialize($factors);
            } else {
                $factors = $factorizationService->trialDivision((int)$num);
                if($redis->hlen(self::HASH_KEY) == self::MAX_HASH_LEN){
                    $redis->del(self::HASH_KEY);
                }
                $redis->hset(self::HASH_KEY, $num, serialize($factors));
            }
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }
        $response->format = Response::FORMAT_JSON;
        return ['data' => $factors, 'error' => $errorMsg];
    }

    public function actionDeleteCache(Connection $redis, Response $response)
    {
        $data = $errorMsg = '';
        try {
            $redis->del(self::HASH_KEY);
            $data = 'Кэш сброшен';
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
        }
        $response->format = Response::FORMAT_JSON;
        return ['data' => $data, 'error' => $errorMsg];
    }

}
