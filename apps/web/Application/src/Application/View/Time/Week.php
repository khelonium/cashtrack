<?php
/**
 * Created by PhpStorm.
 * User: cdordea
 * Date: 1/18/15
 * Time: 9:27 PM
 */

namespace Application\View\Time;


use Zend\View\Model\JsonModel;

class Week extends JsonModel
{
    const WEEKS = 51;

    public function __construct($variables = null, $options = null)
    {

        $out   = [];

        $proto = [
            'unit_nr' =>0,
            'amount' => 0,
        ];

        for ($i =1; $i<= $this->getUnitRange(); $i++) {
            $found = false;
            foreach($variables as $entry) {
                if ($entry['unit_nr'] == $i) {
                    $found = [
                        'unit_nr'=> (int)$entry['unit_nr'],
                        'amount' => (float)$entry['amount']
                    ];
                }
            }

            if (false !== $found) {
                $out[] = $found;
            } else {
                $proto['unit_nr'] = $i;
                $out[] = $proto;
            }
        }

        parent::__construct($out, $options);
    }

    /**
     * @return int
     */
    protected function getUnitRange()
    {
        return self::WEEKS;
    }
}