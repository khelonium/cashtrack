<?php
/**
 * Created by JetBrains PhpStorm.
 * User: logo
 * Date: 11/10/13
 * Time: 8:52 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Application\API;



use Reporter\TimeReporterInterface;
use Zend\View\Model\JsonModel;

class TimeView extends AbstractController
{


    /**
     * @var TimeReporterInterface
     */
    private $reporter = null;

    public function __construct($reporter)
    {
        $this->reporter = $reporter;
    }

    public function get($period)
    {
        $out = [];

        switch ($this->getType()) {
            case 'week':
                $out = $this->reporter->weekTotals($period);
                break;
            case 'month':
                $out = $this->reporter->monthTotals($period);

                break;
        }

        return new JsonModel($out);

    }

    private function getType()
    {
        return $this->params('type', 'week');
    }


}