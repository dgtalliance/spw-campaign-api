<?php

namespace App\Controller;

use App\Services\Utils;
use Exception;
use Recurly\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends AbstractController
{
    private $dbname;
    private $user;
    private $password;
    private $host;
    private $driver;
    private $connection;
    private $port;
    private $apiKey;
    private $client;

    public function __construct($dbname, $user, $password, $host, $driver, $port)
    {
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->driver = $driver;
        $this->port = $port;

        $connectionParams = array(
            'dbname' => $this->dbname,
            'user' => $this->user,
            'password' => $this->password,
            'host' => $this->host,
            'port' => $this->port,
            'driver' => $this->driver,
            'charset' => 'UTF8'
        );
        $this->connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);

        $this->apiKey = "5674bd584b9d469aa23519a0ffa9452c";
        $this->client = new Client($this->apiKey);
    }


    /**
     * @Route("/api/ad_account_creation", name="/api/ad_account_creation", methods={"POST"}, defaults={"_format":"json"})
     * @param Request $request
     * @param Utils $utils
     * @return JsonResponse
     * @throws Exception
     */
    public function adAccountCreation(Request $request, Utils $utils)
    {
        $jsonParams = json_decode($request->getContent(), true);
        $jsonParams['created_on'] = date('Y-m-d H:i:s');
        $jsonParams['updated_on'] = date('Y-m-d H:i:s');

        try {
            if (isset($jsonParams['user_id']) && isset($jsonParams['ad_account_id'])) {
                $filtersSearch['user_id'] = $jsonParams['user_id'];
                $accountToUser = $utils->getAccountByFilters($this->connection, $filtersSearch);

                if (count($accountToUser) > 0) {
                    return $this->json('User already has an account.', 403);
                }
                $jsonParams['general_amount'] = 0;
                /*1-Creo la cuenta*/
                $this->connection->insert('campaign_single_property', $jsonParams);


                /*2-Creo la primera operacion */
                $jsonParamsWallet['ad_account_id'] = $jsonParams['ad_account_id'];
                $jsonParamsWallet['amount_operation'] = 0;
                $jsonParamsWallet['created_on'] = date('Y-m-d H:i:s');
                $jsonParamsWallet['operation_type_id'] = 'deposit';

                $return = $this->connection->insert('campaign_operation_account', $jsonParamsWallet);
                $itemId = $this->connection->lastInsertId($return);

                $response['ad_account_id'] = $jsonParams['ad_account_id'];
                $response['wallet'] = [
                    'id' => $itemId,
                    'amount' => 0
                ];
                $response['status'] = 'success';

                return $this->json($response, 200);
            }
            return $this->json('Incorrect Parameter.', 403);
        } catch (Exception $exc) {
            return $this->json($exc->getMessage(), 403);
        }
    }


    /**
     * @Route("/api/ad_account_retrieval/{userId}", name="/ad_account_retrieval", methods={"GET"}, defaults={"_format":"json"})
     * @param $userId
     * @param Request $request
     * @param Utils $utils
     * @return JsonResponse
     */
    public function adAccountRetrieval($userId, Request $request, Utils $utils)
    {
        try {
            $filters['user_id'] = $userId;
            $account = $utils->getAccountByFilters($this->connection, $filters);
            if (isset($account[0])) {

                $response['user_id'] = $userId;
                $response['ad_account_id'] = $account[0]['ad_account_id'];
                $response['wallet'] = [
                    'amount' => $account[0]['general_amount']
                ];
                return $this->json($response, 200);
            }
            return $this->json('Item Not Found', 404);

        } catch (Exception $exc) {
            return $this->json($exc->getMessage(), 403);
        }
    }

}