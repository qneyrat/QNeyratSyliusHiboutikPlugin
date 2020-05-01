<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Client;

use Fig\Http\Message\RequestMethodInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

const HIBOUTIK_URL_API = 'https://%s.hiboutik.com/api/';
const PRODUCTS_ROUTE = "products";
const PRODUCT_BY_ID_ROUTE =  "products/%d";
const STOCK_AVAILABLE_BY_PRODUCT_ID = "stock_available/product_id/%d";
const STOCK_AVAILABLE_BY_PRODUCT_VARIANT_ID = "stock_available/product_id_size/%d/%d";

class HiboutikClient implements HiboutikClientInterface {

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    public function __construct(string $account, string $user, string $apiKey, HttpClientInterface $client = null)
    {
        if($client === null) {
            $client = HttpClient::create();
        }

        $this->client = ScopingHttpClient::forBaseUri(
            $client,
            sprintf(HIBOUTIK_URL_API, $account),
            ['auth_basic' => [$user, $apiKey]],
        );
    }

    public function products()
    {
        return $this->get(PRODUCTS_ROUTE);
    }

    public function productById(int $hiboutikProductId)
    {
        return current($this->get(sprintf(PRODUCT_BY_ID_ROUTE, $hiboutikProductId)));
    }

    public function stocksAvailableByProductId(int $hiboutikProductId)
    {
        return $this->get(sprintf(STOCK_AVAILABLE_BY_PRODUCT_ID, $hiboutikProductId));
    }

    public function stocksAvailableByProductVariantId(int $hiboutikProductId, int $hiboutikProductVariantId)
    {
        return current($this->get(sprintf(STOCK_AVAILABLE_BY_PRODUCT_VARIANT_ID, $hiboutikProductId, $hiboutikProductVariantId)));
    }

    private function get(string $ressource)
    {
        return json_decode($this->client->request(RequestMethodInterface::METHOD_GET, $ressource)->getContent(), true);
    }
}
