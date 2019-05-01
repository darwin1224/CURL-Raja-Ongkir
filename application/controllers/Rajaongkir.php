<?php

use GuzzleHttp\Client;

class Rajaongkir extends CI_Controller
{
    private $_prefix            = 'rajaongkir';
    private $_template          = 'template';
    private $_client;
    private $_config;

    public function __construct()
    {
        parent::__construct();

        $this->_client = new Client([
			'base_uri' => 'http://api.rajaongkir.com/starter/'
		]);

		$this->_config = [
			'headers' => [
				'key' => '3a3f736b3e2f9e2f692efa4a027629c5'
			]
		];
    }

    public function index()
    {
        $data['provinsi'] = $this->get_province();

        $content = $this->_prefix . '/v_rajaongkir';

        $this->template->load($this->_template, $content, $data);
    }

    public function get_province()
    {
        $response = $this->_client->request('GET', 'province', $this->_config);

		$result = json_decode($response->getBody()->getContents(), true);

		return $result['rajaongkir']['results'];
    }

    public function get_city_by_province($id)
    {
        $this->_config['query'] = [
            'province' => $id
        ];

        $response = $this->_client->request('GET', 'city', $this->_config);

		$result = json_decode($response->getBody()->getContents(), true);

        $res = $result['rajaongkir']['results'];

        return response($res, 200);
    }

    public function cost()
    {
        $this->_config['headers']['Content-Type'] = 'application/x-www-form-urlencoded';

        $data = [
            'origin'        => $this->input->post('kota_asal'),
            'destination'   => $this->input->post('kota_tujuan'),
            'weight'        => $this->input->post('weight'),
            'courier'       => $this->input->post('courier')
        ];

        $this->_config['form_params'] = $data;

        $response = $this->_client->request('POST', 'cost', $this->_config);

        $result = json_decode($response->getBody()->getContents(), true);
        
        $res = $result['rajaongkir']['results'][0]['costs'];

        return response($res, 200);
    }
}