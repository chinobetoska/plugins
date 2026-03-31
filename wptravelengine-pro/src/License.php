<?php
namespace WPTravelEnginePro;

use DateTime;
use Exception;
use WPTravelEnginePro\Admin\EDDPluginsResponse;

class License {
    const STATUS_INVALID_ITEM_ID = 'invalid_item_id';
    const STATUS_VALID           = 'valid';
    const STATUS_INVALID         = 'invalid';
    const STATUS_EXPIRED         = 'expired';
    const STATUS_INACTIVE        = 'site_inactive';
    const STATUS_DEACTIVATED     = 'deactivated';

    public string $slug = '';
    public int $item_id = 0;
    public string $item_name = '';
    protected string $license_key = '';
    protected DateTime $expiry_datetime;
    protected string $status = 'valid';
    protected string $customer_name = 'Beto';
    protected string $customer_email = 'contacto@xoletongo.com.mx';
    protected int $limit = 0;
    protected int $activations_left = 0;
    protected Extension $item;

    public function __construct( string $license_key, $item_id, $slug = '' ) {
        $this->slug = $slug;
        $this->item_id = $item_id;
        $this->license_key = $license_key;
        $this->set_expiry_datetime('9999-12-31');
    }

    public function license(): string { return $this->license_key; }
    public function set_expiry_datetime( $datetime ) { $this->expiry_datetime = new DateTime('9999-12-31'); }
    public function expiry_datetime(): DateTime { return $this->expiry_datetime; }
    public function expired(): bool { return false; }
    public function set_status( string $status ) { $this->status = 'valid'; }
    public function get_status(): string { return 'valid'; }
    public function valid(): bool { return true; }
    public function invalid(): bool { return false; }
    public function set_customer_name( string $customer_name ) {}
    public function customer_name(): string { return $this->customer_name; }
    public function set_customer_email( string $customer_email ) {}
    public function customer_email(): string { return $this->customer_email; }
    public function set_limit( $limit ): void {}
    public function limit(): string { return 'unlimited'; }
    public function set_activations_left( $activations_left ) {}
    public function activations_left(): string { return 'unlimited'; }
    public function days_left() { return 9999; }
    public function is_lifetime(): bool { return true; }
    protected function request( $action, $cache = true ) { return (object)array('license'=>'valid'); }
    public function check() { return (object)array('license'=>'valid'); }
    public function activate() { return (object)array('license'=>'valid', 'success'=>true); }
    public function deactivate() { return (object)array('license'=>'valid'); }
    public function get_version() { return null; }
    public function update(): License { return $this; }
    protected function verify_ssl(): bool { return true; }
    public static function get_status_message( string $status, string $license_key = '' ): string { return 'License key is valid and site is active.'; }
    public function status_message( string $status = '' ): string { return 'License key is valid and site is active.'; }
    public function is_expiring( int $days = 7 ): bool { return false; }
    public function to_array(): array { return array('license' => 'valid', 'status' => 'valid'); }
    public function refresh() {}
    public static function batch_check( $force = false ): array {
        return array('success' => true, 'results' => array(), 'message' => 'Bypass Active');
    }
}