<?php
namespace integration\jobs;


use Jobs\Double\WeeklyEmailIntegration;

class CheckWeeklyTest extends \PHPUnit_Framework_TestCase
{
    /**
    * @test email integration
    */
    public function weeklyIntegratesWithEmail()
    {
        $integration = new WeeklyEmailIntegration();
        $integration->setUp();
        $integration->perform();
        $this->assertTrue($integration->mailer->called);
        $this->assertEquals("Weekly limit exceeded", $integration->mailer->getSubject());
    }
}
