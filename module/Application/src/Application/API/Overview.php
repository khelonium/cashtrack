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

    public function __construct(\Reporter\Overview $reporter)
    {
        $this->reporter = $reporter;
    }

    public function get($month)
    {
        $expand   = $this->params()->fromQuery('expand');

        return new JsonOverview(
            $this->reporter->getOverview($month, $expand?explode(',', $expand):[])
        );
    }



}