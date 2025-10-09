<?php namespace Dpay\Data;
// Pauldro\Minicli
use Pauldro\Minicli\v2\Util\SimpleArray;

/**
 * PaymentLink
 * Container for PaymentLink Data
 * 
 * @property string $id        Payment Link ID / URL
 * @property string $url       Payment Link URL
 * @property bool   $isActive  Return if Payment Link is Active
 * @property Order  $order
 * @property string      $redirectUrl        Redirect URL (used on creates)
 * @property string      $description        Payment Description
 * @property SimpleArray $metadata           Metadata
 */
class PaymentLink extends Data {
	const FIELDS_STRING  = ['id', 'url', 'redirectUrl', 'description'];

/* =============================================================
	Constructors / Inits
============================================================= */
	public function __construct() {
		parent::__construct();
		$this->isActive = false;
		$this->order    = new Order();
		$this->metadata = new SimpleArray();
	}

/* =============================================================
	Setters
============================================================= */
	/**
	 * Set Fields fom JSON array
	 * @param  array $data
	 * @return void
	 */
	public function setFromJson(array $data) : void {
		if (array_key_exists('ordernbr', $data) === false) {
			return;
		}
		if (array_key_exists('redirectUrl', $data)) {
			$this->redirectUrl = $data['redirectUrl'];
		}
		if (array_key_exists('description', $data)) {
			$this->description = $data['description'];
		}
		if (array_key_exists('metadata', $data)) {
			foreach ($data['metadata'] as $key => $value) {
				$this->metadata->set($key, $value);
			}
		}
		$this->order->setFromJson($data);
	}

/* =============================================================
	Getters
============================================================= */
	public function getArray() : array
	{
		$data = $this->data;
		$data['order']    = $this->order->getArray();
		$data['metadata'] = $this->metadata->getArray();
		return $data;
	}
}