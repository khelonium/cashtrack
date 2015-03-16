<?php
namespace Prediction;

use Finance\Cashflow\MonthTotal;
use Finance\Cashflow\MonthTotalCollection;
use Zend\Stdlib\ArrayObject;

require_once 'BuildsCashtrack.php';

class CadenceTest extends \PHPUnit_Framework_TestCase
{
    use BuildsCashtrack;

    /**
     * @test
     */
    public function withOneEntryCadenceIsZero()
    {
        $cadence = new Cadence(
            new MonthTotalCollection(
                [
                    $this->cashtrackWith(100, (new \DateTime())->format('Y-m-01'))
                ]
            )
        );
        $this->assertEquals(0, $cadence->getCadence());
    }



    /**
     * @test
     */
    public function testUniformDistribution()
    {

        $expectedCadence = 2;


        for ($i =1; $i <= 12; $i++) {
            $cadence = $this->buildWithAMonthDifferenceOf($expectedCadence);
            $this->assertEquals($expectedCadence, $cadence->getCadence(), " $i failed");
        }

    }

    /**
     * @test
     */
    public function testWithRandomDistribution()
    {
        $start = new \DateTime();
        $second = (new \DateTime())->sub(new \DateInterval('P1M'));
        $last = (new \DateTime())->sub(new \DateInterval('P3M'));

        $cadence = new Cadence(
            new MonthTotalCollection(
                [
                    $this->cashtrackWith(100, $start->format('Y-m-01')),
                    $this->cashtrackWith(100, $second->format('Y-m-01')),
                    $this->cashtrackWith(100, $last->format('Y-m-01'))
                ]
            )
        );

        $this->assertEquals(1.5, $cadence->getCadence());
    }

    /**
     * @param $expectedCadence
     * @return Cadence
     */
    protected function buildWithAMonthDifferenceOf($expectedCadence)
    {
        $start = new \DateTime();
        $end = (new \DateTime())->sub(new \DateInterval('P' . $expectedCadence . 'M'));

        $cadence = new Cadence(
            new MonthTotalCollection(
                [
                    $this->cashtrackWith(100, $start->format('Y-m-01')),
                    $this->cashtrackWith(100, $end->format('Y-m-01'))
                ]
            )
        );
        return $cadence;
    }
}

