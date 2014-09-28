<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;



use Application\View\JsonOverview;

class Overview extends AbstractController
{


    /**
     * @var \Reporter\Overview
     */
    private $reporter = null;

    public function get($id)
    {
        $expand   = $this->params()->fromQuery('expand');

        return new JsonOverview(
            $this->getOverviewReporter()->getOverview($id, $expand?explode(',', $expand):[])
        );
    }

    public function setOverviewReporter(\Reporter\Overview $reporter)
    {
        $this->reporter = $reporter;
    }

    /**
     * @return \Reporter\Overview
     */
    private function getOverviewReporter()
    {
        return $this->reporter;

    }


}