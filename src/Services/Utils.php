<?php

namespace App\Services;

use Recurly\Client;
use Recurly\Resources\Account;
use Recurly\Resources\AddOn;
use Recurly\Resources\Invoice;
use Recurly\Resources\PlanPricing;
use Recurly\Resources\Address;

/**
 * @Service("agent.utils")
 */
class Utils
{
    public function __construct()
    {

    }

    public function getAccountByFilters($connection, $filters)
    {
        $query = "SELECT * FROM campaign_single_property";
        if (is_array($filters) && count(array_keys($filters)) > 0) {
            $where = " WHERE ";
            $conditions = [];
            foreach ($filters as $key => $value) {
                $conditions[] = $key . '=' . $value;
            }
            $where .= implode(' AND ', $conditions);
        }
        $query .= $where;
        $query .= " ORDER BY created_on";
        $data = $connection->fetchAll($query);
        return $data;
    }

}
