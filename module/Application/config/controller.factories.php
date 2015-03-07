<?php
return [
    'factories' => array(
        'Application\API\Overview' => function ($sm) {
            $locator    = $sm->getServiceLocator();
            return new \Application\API\Overview($locator->get('Reporter\Overview'));
        },

        'Application\API\Merchant' => function ($sm) {
            $locator    = $sm->getServiceLocator();
            return new \Application\API\Merchant($locator->get('\Database\Merchant\Repository'));
        },

        'Application\API\TimeView' => function ($sm) {
            $locator    = $sm->getServiceLocator();
            return new \Application\API\TimeView($locator->get('\Reporter\TimeMaster'));
        },

        'Application\API\Breakdown\Month' => function ($sm) {
            $locator    = $sm->getServiceLocator();

            $breakdown = new \Application\API\BreakDown\Month($locator->get('\Finance\Reporter\Breakdown'));
            return $breakdown;
        },

        'Application\API\Breakdown\Week' => function ($sm) {
            $locator    = $sm->getServiceLocator();

            return new \Application\API\BreakDown\Week($locator->get('\Finance\Reporter\Breakdown'));
        },

        'Application\API\Breakdown\Year' => function ($sm) {
            $locator    = $sm->getServiceLocator();

            return new \Application\API\BreakDown\Year($locator->get('\Finance\Reporter\Breakdown'));
        },
    ),
];
