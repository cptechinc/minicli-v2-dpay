<?php namespace Dpay\Stripe\Endpoints;
// Stripe API
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent as StripePaymentIntent;
// Dpay
use Dpay\Stripe\ApiClient;

/**
 * Wrapper for Stripe API to interface with the PaymentIntents Endpoint
 */
class Charges extends AbstractEndpoint {
    public static string $errorMsg;

/* =============================================================
    Public Processing
============================================================= */
    /**
     * Cancel Payment
     * @param  StripePaymentIntent $rqst
     * @return StripePaymentIntent
     */
    public static function cancel(StripePaymentIntent $rqst) : StripePaymentIntent
    {
        $stripe = ApiClient::instance();
        
        try {
            $charge = $stripe->paymentIntents->cancel($rqst->id);
        } catch(ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return new StripePaymentIntent('');
        }
        return $charge;
    }

     /**
      * Capture Payment
      * @param  StripePaymentIntent $rqst
      * @return StripePaymentIntent
      */
      public static function capturepreauth(StripePaymentIntent $rqst) : StripePaymentIntent
      {
        $stripe = ApiClient::instance();
        
        try {
            $charge = $stripe->paymentIntents->capture($rqst->id);
        } catch(ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return new StripePaymentIntent('');
        }
        return $charge;
    }

    /**
      * Confirm Payment
      * @param  StripePaymentIntent $rqst
      * @return StripePaymentIntent
      */
      public static function confirm(StripePaymentIntent $rqst) : StripePaymentIntent
      {
        $stripe = ApiClient::instance();
        
        try {
            $charge = $stripe->paymentIntents->confirm($rqst->id);
        } catch(ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return new StripePaymentIntent('');
        }
        return $charge;
    }

    /**
      * Create Stripe Charge
      * @param  StripePaymentIntent $rqst
      * @return StripePaymentIntent
      */
      public static function create(StripePaymentIntent $rqst) : StripePaymentIntent
      {
        $stripe = ApiClient::instance();
        
        try {
            $charge = $stripe->paymentIntents->create(self::requestArray($rqst));
        } catch(ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return new StripePaymentIntent('');
        }
        return $charge;
    }

    /**
      * Fetch Stripe Payment Intent
      * @param  string $id 
      * @return StripePaymentIntent
      */
      public static function fetchById($id) : StripePaymentIntent 
      {
        $stripe = ApiClient::instance();
        
        try {
            $charge = $stripe->paymentIntents->retrieve($id);
        } catch(ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return new StripePaymentIntent('');
        }
        return $charge;
    }

     /**
      * Create, Return Stripe Charge
      * @param  StripePaymentIntent $rqst
      * @return StripePaymentIntent
      */
    public static function preauth(StripePaymentIntent $rqst) : StripePaymentIntent
    {
        $stripe = ApiClient::instance();
        
        try {
            $charge = $stripe->paymentIntents->create(self::requestArray($rqst));
        } catch(ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return new StripePaymentIntent('');
        }
        return $charge;
    }

    /**
      * Update Stripe Charge
      * @param  StripePaymentIntent $rqst
      * @return StripePaymentIntent
      */
      public static function update(StripePaymentIntent $rqst) : StripePaymentIntent
      {
        $stripe = ApiClient::instance();
        
        try {
            $data = self::requestArray($rqst);
            if (array_key_exists('id', $data)) {
                unset($data['id']);
            }
            $charge = $stripe->paymentIntents->update($rqst->id, $data);
        } catch(ApiErrorException $e) {
            self::$errorMsg = $e->getMessage();
            return new StripePaymentIntent('');
        }
        return $charge;
    }
    
/* =============================================================
    Supplemental
============================================================= */
    /**
     * @param  StripePaymentIntent $rqst
     * @return array{amount?: int, amount_details?: null|array{discount_amount?: null|int, line_items?: null|array{discount_amount?: int, payment_method_options?: array{card?: array{commodity_code?: string}, card_present?: array{commodity_code?: string}, klarna?: array{image_url?: string, product_url?: string, subscription_reference?: string}, paypal?: array{category?: string, description?: string, sold_by?: string}}, product_code?: string, product_name: string, quantity: int, tax?: array{total_tax_amount: int}, unit_cost: int, unit_of_measure?: string}[], shipping?: null|array{amount?: null|int, from_postal_code?: null|string, to_postal_code?: null|string}, tax?: null|array{total_tax_amount: int}}, application_fee_amount?: null|int, capture_method?: string, currency?: string, customer?: string, customer_account?: string, description?: string, expand?: string[], fx_quote?: string, hooks?: array{inputs?: array{tax?: array{calculation: null|string}}}, mandate_data?: array{customer_acceptance: array{online: array{ip_address?: string, user_agent?: string}, type: string}}, metadata?: null|array<string, string>, payment_details?: null|array{car_rental?: array{affiliate?: array{name: string}, booking_number: string, car_class_code?: string, car_make?: string, car_model?: string, company?: string, customer_service_phone_number?: string, days_rented: int, delivery?: array{mode?: string, recipient?: array{email?: string, name?: string, phone?: string}}, distance?: array{amount?: int, unit?: string}, drivers?: array{driver_identification_number?: string, driver_tax_number?: string, name: string}[], extra_charges?: string[], no_show?: bool, pickup_address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, pickup_at: int, pickup_location_name?: string, rate_amount?: int, rate_interval?: string, renter_name?: string, return_address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, return_at: int, return_location_name?: string, tax_exempt?: bool, vehicle_identification_number?: string}, customer_reference?: null|string, event_details?: array{access_controlled_venue?: bool, address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, affiliate?: array{name: string}, company?: string, delivery?: array{mode?: string, recipient?: array{email?: string, name?: string, phone?: string}}, ends_at?: int, genre?: string, name: string, starts_at?: int}, flight?: array{affiliate?: array{name: string}, agency_number?: string, carrier?: string, delivery?: array{mode?: string, recipient?: array{email?: string, name?: string, phone?: string}}, passenger_name?: string, passengers?: array{name: string}[], segments: array{amount?: int, arrival_airport?: string, arrives_at?: int, carrier?: string, departs_at: int, departure_airport?: string, flight_number?: string, service_class?: string}[], ticket_number?: string}, lodging?: array{address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, adults?: int, affiliate?: array{name: string}, booking_number?: string, category?: string, checkin_at: int, checkout_at: int, customer_service_phone_number?: string, daily_room_rate_amount?: int, delivery?: array{mode?: string, recipient?: array{email?: string, name?: string, phone?: string}}, extra_charges?: string[], fire_safety_act_compliance?: bool, name?: string, no_show?: bool, number_of_rooms?: int, passengers?: array{name: string}[], property_phone_number?: string, room_class?: string, room_nights?: int, total_room_tax_amount?: int, total_tax_amount?: int}, order_reference?: null|string, subscription?: array{affiliate?: array{name: string}, auto_renewal?: bool, billing_interval?: array{count: int, interval: string}, ends_at?: int, name: string, starts_at?: int}}, payment_method?: string, payment_method_configuration?: string, payment_method_data?: array{acss_debit?: array{account_number: string, institution_number: string, transit_number: string}, affirm?: array{}, afterpay_clearpay?: array{}, alipay?: array{}, allow_redisplay?: string, alma?: array{}, amazon_pay?: array{}, au_becs_debit?: array{account_number: string, bsb_number: string}, bacs_debit?: array{account_number?: string, sort_code?: string}, bancontact?: array{}, billie?: array{}, billing_details?: array{address?: null|array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, email?: null|string, name?: null|string, phone?: null|string, tax_id?: string}, blik?: array{}, boleto?: array{tax_id: string}, cashapp?: array{}, crypto?: array{}, customer_balance?: array{}, eps?: array{bank?: string}, fpx?: array{account_holder_type?: string, bank: string}, giropay?: array{}, gopay?: array{}, grabpay?: array{}, id_bank_transfer?: array{bank?: string}, ideal?: array{bank?: string}, interac_present?: array{}, kakao_pay?: array{}, klarna?: array{dob?: array{day: int, month: int, year: int}}, konbini?: array{}, kr_card?: array{}, link?: array{}, mb_way?: array{}, metadata?: array<string, string>, mobilepay?: array{}, multibanco?: array{}, naver_pay?: array{funding?: string}, nz_bank_account?: array{account_holder_name?: string, account_number: string, bank_code: string, branch_code: string, reference?: string, suffix: string}, oxxo?: array{}, p24?: array{bank?: string}, pay_by_bank?: array{}, payco?: array{}, paynow?: array{}, paypal?: array{}, payto?: array{account_number?: string, bsb_number?: string, pay_id?: string}, pix?: array{}, promptpay?: array{}, qris?: array{}, radar_options?: array{session?: string}, rechnung?: array{dob: array{day: int, month: int, year: int}}, revolut_pay?: array{}, samsung_pay?: array{}, satispay?: array{}, sepa_debit?: array{iban: string}, shopeepay?: array{}, sofort?: array{country: string}, stripe_balance?: array{account?: string, source_type?: string}, swish?: array{}, twint?: array{}, type: string, us_bank_account?: array{account_holder_type?: string, account_number?: string, account_type?: string, financial_connections_account?: string, routing_number?: string}, wechat_pay?: array{}, zip?: array{}}, payment_method_options?: array{acss_debit?: null|array{mandate_options?: array{custom_mandate_url?: null|string, interval_description?: string, payment_schedule?: string, transaction_type?: string}, setup_future_usage?: null|string, target_date?: string, verification_method?: string}, affirm?: null|array{capture_method?: null|string, preferred_locale?: string, setup_future_usage?: string}, afterpay_clearpay?: null|array{capture_method?: null|string, reference?: string, setup_future_usage?: string}, alipay?: null|array{setup_future_usage?: null|string}, alma?: null|array{capture_method?: null|string}, amazon_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, au_becs_debit?: null|array{setup_future_usage?: null|string, target_date?: string}, bacs_debit?: null|array{mandate_options?: array{reference_prefix?: null|string}, setup_future_usage?: null|string, target_date?: string}, bancontact?: null|array{preferred_language?: string, setup_future_usage?: null|string}, billie?: null|array{capture_method?: null|string}, blik?: null|array{code?: string, setup_future_usage?: null|string}, boleto?: null|array{expires_after_days?: int, setup_future_usage?: null|string}, card?: null|array{capture_method?: null|string, cvc_token?: string, installments?: array{enabled?: bool, plan?: null|array{count?: int, interval?: string, type: string}}, mandate_options?: array{amount: int, amount_type: string, description?: string, end_date?: int, interval: string, interval_count?: int, reference: string, start_date: int, supported_types?: string[]}, moto?: bool, network?: string, request_decremental_authorization?: string, request_extended_authorization?: string, request_incremental_authorization?: string, request_multicapture?: string, request_overcapture?: string, request_partial_authorization?: string, request_three_d_secure?: string, require_cvc_recollection?: bool, setup_future_usage?: null|string, statement_descriptor_suffix_kana?: null|string, statement_descriptor_suffix_kanji?: null|string, statement_details?: null|array{address?: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, phone?: string}, three_d_secure?: array{ares_trans_status?: string, cryptogram: string, electronic_commerce_indicator?: string, exemption_indicator?: string, network_options?: array{cartes_bancaires?: array{cb_avalgo: string, cb_exemption?: string, cb_score?: int}}, requestor_challenge_indicator?: string, transaction_id: string, version: string}}, card_present?: null|array{request_extended_authorization?: bool, request_incremental_authorization_support?: bool, routing?: array{requested_priority?: string}}, cashapp?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, crypto?: null|array{setup_future_usage?: string}, customer_balance?: null|array{bank_transfer?: array{eu_bank_transfer?: array{country: string}, requested_address_types?: string[], type: string}, funding_type?: string, setup_future_usage?: string}, eps?: null|array{setup_future_usage?: string}, fpx?: null|array{setup_future_usage?: string}, giropay?: null|array{setup_future_usage?: string}, gopay?: null|array{setup_future_usage?: string}, grabpay?: null|array{setup_future_usage?: string}, id_bank_transfer?: null|array{expires_after?: int, expires_at?: int, setup_future_usage?: string}, ideal?: null|array{setup_future_usage?: null|string}, interac_present?: null|array{}, kakao_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, klarna?: null|array{capture_method?: null|string, on_demand?: array{average_amount?: int, maximum_amount?: int, minimum_amount?: int, purchase_interval?: string, purchase_interval_count?: int}, preferred_locale?: string, setup_future_usage?: string, subscriptions?: null|array{interval: string, interval_count?: int, name?: string, next_billing?: array{amount: int, date: string}, reference: string}[]}, konbini?: null|array{confirmation_number?: null|string, expires_after_days?: null|int, expires_at?: null|int, product_description?: null|string, setup_future_usage?: string}, kr_card?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, link?: null|array{capture_method?: null|string, persistent_token?: string, setup_future_usage?: null|string}, mb_way?: null|array{setup_future_usage?: string}, mobilepay?: null|array{capture_method?: null|string, setup_future_usage?: string}, multibanco?: null|array{setup_future_usage?: string}, naver_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, nz_bank_account?: null|array{setup_future_usage?: null|string, target_date?: string}, oxxo?: null|array{expires_after_days?: int, setup_future_usage?: string}, p24?: null|array{setup_future_usage?: string, tos_shown_and_accepted?: bool}, pay_by_bank?: null|array{}, payco?: null|array{capture_method?: null|string}, paynow?: null|array{setup_future_usage?: string}, paypal?: null|array{capture_method?: null|string, line_items?: array{category?: string, description?: string, name: string, quantity: int, sku?: string, sold_by?: string, tax?: array{amount: int, behavior: string}, unit_amount: int}[], preferred_locale?: string, reference?: string, reference_id?: string, risk_correlation_id?: string, setup_future_usage?: null|string, subsellers?: string[]}, payto?: null|array{mandate_options?: array{amount?: int, amount_type?: string, end_date?: string, payment_schedule?: string, payments_per_period?: int, purpose?: string}, setup_future_usage?: null|string}, pix?: null|array{amount_includes_iof?: string, expires_after_seconds?: int, expires_at?: int, mandate_options?: array{amount?: int, amount_includes_iof?: string, amount_type?: string, currency?: string, end_date?: string, payment_schedule?: string, reference?: string, start_date?: string}, setup_future_usage?: string}, promptpay?: null|array{setup_future_usage?: string}, qris?: null|array{setup_future_usage?: string}, rechnung?: null|array{}, revolut_pay?: null|array{capture_method?: null|string, setup_future_usage?: null|string}, samsung_pay?: null|array{capture_method?: null|string}, satispay?: null|array{capture_method?: null|string}, sepa_debit?: null|array{mandate_options?: array{reference_prefix?: null|string}, setup_future_usage?: null|string, target_date?: string}, shopeepay?: null|array{setup_future_usage?: string}, sofort?: null|array{preferred_language?: null|string, setup_future_usage?: null|string}, stripe_balance?: null|array{setup_future_usage?: null|string}, swish?: null|array{reference?: null|string, setup_future_usage?: string}, twint?: null|array{setup_future_usage?: string}, us_bank_account?: null|array{financial_connections?: array{filters?: array{account_subcategories?: string[], institution?: string}, manual_entry?: array{mode: string}, permissions?: string[], prefetch?: string[], return_url?: string}, mandate_options?: array{collection_method?: null|string}, networks?: array{requested?: string[]}, preferred_settlement_speed?: null|string, setup_future_usage?: null|string, target_date?: string, verification_method?: string}, wechat_pay?: null|array{app_id?: string, client?: string, setup_future_usage?: string}, zip?: null|array{setup_future_usage?: string}}, payment_method_types?: string[], receipt_email?: null|string, setup_future_usage?: null|string, shipping?: null|array{address: array{city?: string, country?: string, line1?: string, line2?: string, postal_code?: string, state?: string}, carrier?: string, name: string, phone?: string, tracking_number?: string}, statement_descriptor?: string, statement_descriptor_suffix?: string, transfer_data?: array{amount?: int}, transfer_group?: string}
     */
    public static function requestArray(StripePaymentIntent $rqst) : array
    {
        return $rqst->toArray();
    }
}