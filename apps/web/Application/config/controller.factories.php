<?php
return [
    'factories' => array(

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

            $breakdown = new \Application\API\Breakdown\Month($locator->get('\Reporter\Cashflow'));
            return $breakdown;
        },

        'Application\API\Breakdown\Week' => function ($sm) {
            $locator    = $sm->getServiceLocator();

            return new \Application\API\Breakdown\Week($locator->get('\Reporter\Cashflow'));
        },

        'Application\API\Breakdown\Year' => function ($sm) {
            $locator    = $sm->getServiceLocator();
            return new \Application\API\Breakdown\Year($locator->get('\Reporter\Cashflow'));
        },
    ),
];
