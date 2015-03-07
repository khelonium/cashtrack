<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/25/14
 * Time: 8:44 AM
 */

namespace unit\Application\API;


use Application\Double\API\BreakdownDummy;
use Application\Double\API\CashtrackDummy;
use Application\Double\API\MonthSpy;
use Application\Double\API\WeekService;
use Application\View\Error;

class BreakdownTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WeekService
     */
    private $reporter;

    /**
     * @var BreakdownDummy
     */
    private $controller;

    /**
     * @before
     */
    public function itCanConstruct()
    {
        $this->reporter = new CashtrackDummy();
        $this->controller = new BreakdownDummy($this->reporter);
    }



    /**
     * @test
     */
    public function ifYearIsNotPresentWeHaveABadRequest()
    {
        $double = $this->controller;
        $double->yearWillReturn(null);

        $response = $double->get(1);
        $this->assertTrue($response instanceof Error);

        $this->assertBadRequest($double);
    }


    /**
     * @test
     */
    public function validRequestWillCallForTheBreakdown()
    {

        $this->controller->yearWillReturn(2014);
        $this->controller->get(1);

        $this->assertFalse($this->controller->hasBadRequest());
        $this->assertTrue($this->controller->breakDownIsCalled());
    }



    /**
     * @param $double
     */
    protected function assertBadRequest(BreakdownDummy $double)
    {
        $this->assertTrue($double->hasBadRequest());
    }



    /**
     * @param $double
     */
    protected function assertResponseIsOk($double)
    {
        $this->assertEquals(200, $double->getResponse()->getStatusCode());
    }
}

