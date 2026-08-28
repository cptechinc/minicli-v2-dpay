<?php namespace Dpay\Stripe\Endpoints;

// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\Product;
// Dpay
use Dpay\Stripe\ApiClient;

/**
 * Wrapper for Stripe API to interface with the products Endpoint
 */
class Products extends AbstractEndpoint
{
    #[\Override]
    public static string $errorMsg;

    /**
     * Return if Product Exists
     */
    public static function exists(string $id): bool
    {
        $stripe = ApiClient::instance();

        try {
            $stripe->products->retrieve($id, []);
        } catch (ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Return Product
     */
    public static function fetch(string $id): Product
    {
        $stripe = ApiClient::instance();

        try {
            $product = $stripe->products->retrieve($id, []);
        } catch (ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return new Product();
        }
        return $product;
    }

    /**
     * Create Product
     */
    public static function create(Product $data): Product
    {
        $stripe = ApiClient::instance();

        try {
            $product = $stripe->products->create($data->toArray());
        } catch (ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return new Product();
        }
        return $product;
    }
}
