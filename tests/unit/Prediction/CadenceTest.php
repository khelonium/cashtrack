<?php
namespace Prediction;

use DateTime;
use Finance\Cashflow\AccountTotal;
use Library\Collection;
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
            new Collection(
                [
                    new DateTime()
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


        $cadence = $this->buildWithAMonthDifferenceOf($expectedCadence);

        $this->assertEquals($expectedCadence, $cadence->getCadence(), " uniform cadence failed");

    }

    /**
     * @test
     */
    public function testWithRandomDistribution()
    {
        $start = new DateTime();
        $second = (new DateTime())->sub(new \DateInterval('P1M'));
        $last = (new DateTime())->sub(new \DateInterval('P3M'));

        $cadence = new Cadence(
            new Collection(
                [
                    $start,
                    $second,
                    $last
                ]
            )
        );

        $this->assertEquals(1.5, $cadence->getCadence());
    }

    /**
     * @test
     */
    public function threeEntriesInSixMonthsHaveACadenceOfThree()
    {
        $start = new DateTime();
        $second = (new DateTime())->sub(new \DateInterval('P6M'));
        $last = (new DateTime())->sub(new \DateInterval('P2M'));

        $cadence = new Cadence(
            new Collection(
                [
                    $start,
                    $second,
                    $last
                ]
            )
        );

        $this->assertEquals(3, $cadence->getCadence());
    }


    /**
     * @param $expectedCadence
     * @return Cadence
     */
    protected function buildWithAMonthDifferenceOf($expectedCadence)
    {
        $start = new DateTime();
        $end = (new DateTime())->sub(new \DateInterval('P' . $expectedCadence . 'M'));

        $cadence = new Cadence(
            new Collection(
                [
                    $start,
                    $end
                ]
            )
        );
        return $cadence;
    }
}

