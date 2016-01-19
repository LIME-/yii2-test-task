<?php

require getcwd() . '/services/FactorizationService.php';

class FactorizationServiceTest extends \PHPUnit_Framework_TestCase
{
    private $f, $maxInt;

    protected function setUp()
    {
        $this->f = new \app\services\FactorizationService;
        if (PHP_INT_SIZE === 4) {
            $this->maxInt = 2147483647;
        } else {
            $this->maxInt = 9223372036854775807;
        }
    }

    protected function tearDown()
    {
    }

    // tests
    public function testTrialDivision()
    {
        $this->assertEquals([2], $this->f->trialDivision(2));
        $this->assertEquals([2, 5], $this->f->trialDivision(10));
    }

    public function testTrialDivisionBigest()
    {
        if (PHP_INT_SIZE === 4) {
            $this->assertEquals([2147483647], $this->f->trialDivision($this->maxInt));
        } else {
            $this->assertEquals([2, 2, 2, 2, 2, 2, 2, 2, 2, 3, 3, 3, 3, 7, 19, 73, 87211, 262657], $this->f->trialDivision($this->maxInt));
        }

    }

    public function testTrialDivisionLarger()
    {
        $this->setExpectedException('\yii\base\Exception', 'Невалидное число.');
        if (PHP_INT_SIZE === 4) {
            $this->f->trialDivision(2147483648);
        } else {
            $this->f->trialDivision(9223372036854775808);
        }

    }

    public function testTrialDivisionLess()
    {
        $this->setExpectedException('\yii\base\Exception', 'Невалидное число.');
        $this->f->trialDivision(0);
    }

    public function testTrialDivisionWrongParam()
    {
        $this->setExpectedException('\yii\base\Exception', 'Невалидное число.');
        $this->f->trialDivision('wrong value');
    }
}
