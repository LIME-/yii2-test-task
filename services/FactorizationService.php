<?php
/**
 * Created by PhpStorm.
 * User: lime
 * Date: 17.01.16
 * Time: 7:31
 */

namespace app\services;
use yii\base\Exception;


/**
 * Class FactorizationService
 * @package app\services
 */
class FactorizationService
{
    /**
     * Убрав из перебора кратные 2 и 3 варианты мы сократим колво на 2/3
     *
     * @return \Generator
     */
    private function getNext()
    {
        yield 2;
        yield 3;
        yield 5;
        $prime = 5;
        while(1){
            yield $prime += 2;
            yield $prime += 4;
        }
    }

    /**
     * Перебор делителей
     * https://ru.wikipedia.org/wiki/%D0%9F%D0%B5%D1%80%D0%B5%D0%B1%D0%BE%D1%80_%D0%B4%D0%B5%D0%BB%D0%B8%D1%82%D0%B5%D0%BB%D0%B5%D0%B9
     *
     * @param $num
     * @return array
     * @throws Exception
     */
    public function trialDivision($num)
    {

        if (!is_int($num) || $num < 2) {
            throw new Exception('Невалидное число.');
        }

        $factors = [];

        while ($num !== 1){
            $numSqrt = floor(sqrt($num));
            foreach ($this->getNext() as $prime) {
                if ($prime > $numSqrt) {
                    $factors[] = $num;
                    break 2;
                } elseif (0 == $num % $prime) {
                    $factors[] = $prime;
                    $num = round($num / $prime);
                    break;
                }
            }
        }
        sort($factors);
        return $factors;
    }

}