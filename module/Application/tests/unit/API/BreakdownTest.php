<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 12/25/14
 * Time: 8:44 AM
 */

namespace unit\Application\API;


use Application\API\Breakdown;
use Application\Double\API\BreakdownSpy;
use Application\Double\API\WeekService;
use Application\View\Error;

class BreakdownTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WeekService
     */
    private $reporter;

    /**
     * @var BreakdownSpy
     */
    private $controller;

    /**
     * @before
     */
    function itCanConstruct()
    {
        $this->reporter = new WeekService();
        $this->controller = new BreakdownSpy($this->reporter);
    }

    /**
     * @test
     */
    function anInvalidTypeWillShowBadRequest()
    {
        $double =  $this->controller;

        $double->typeToReturn("Invalid");

        $response = $double->get(1);

        $this->assertBadRequest($double);
        $this->assertTrue($response instanceof Error);


    }

    /**
     * @test
     */
    function validTypeWillShowOk()
    {
        $double = $this->controller;

        $this->requestWith($double, "week");
        $this->assertResponseIsOk($double);

        $this->requestWith($double, "month");
        $this->assertResponseIsOk($double);
    }

    /**
     * @test
     */
    function  ifYearIsNotPresentWeHaveABadRequest()
    {
        $double = $this->controller;
        $double->typeToReturn("month");
        $double->yearWillReturn(null);

        $response = $double->get(1);
        $this->assertTrue($response instanceof Error);

        $this->assertBadRequest($double);
    }

    /**
     * @test
     */
    function givenTypeWeek_controllerWillGetTheWeekList()
    {
        $service = new WeekService();
        $controller = new BreakdownSpy($service);
        $controller->typeToReturn('week');
        $controller->get(1);
        $this->assertTrue($service->weekWasCalledWith(2014,1));
    }


    /**
     * @test
     */
    function givenTypeMonth_controllerWillRetrieveTheMonthList()
    {

        $service = new WeekService();
        $controller = new BreakdownSpy($service);
        $controller->typeToReturn('month');
        $controller->get(2);
        $this->assertTrue($service->monthWasCalledWith(2014,2));
    }

    /**
     * @param $double
     */
    protected function assertBadRequest(BreakdownSpy $double)
    {
        $this->assertTrue($double->setBadRequestWasSet());
    }

    /**
     * @param $double
     * @param $typeToReturn
     */
    protected function requestWith($double, $typeToReturn)
    {
        $double->typeToReturn($typeToReturn);
        return $double->get(1);
    }

    /**
     * @param $double
     */
    protected function assertResponseIsOk($double)
    {
        $this->assertEquals(200, $double->getResponse()->getStatusCode());
    }
}

