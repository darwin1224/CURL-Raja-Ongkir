<?php

if ( ! function_exists('flash')) {

    /**
     * Helper Flash Data
     *
     * @param string $session_name
     * @param string $type
     * @param string $prefix
     * @param string $message
     * @return void
     */
    function flash($session_name = 'msg', $type = '', $prefix = '', $message = '')
    {
        $ci =& get_instance();
        return $ci->session->set_flashdata($session_name, '<div class="alert alert-' . $type . '"><b>' . $prefix . '</b> ' . $message . '</div>');
    }
}

if (!function_exists('response')) {

    /**
     * Helper Response JSON
     *
     * @param array $data
     * @param integer $status
     * @return void
     */
	function response($data = [], $status = 200)
	{
        $ci =& get_instance();
		$ci->output
			->set_content_type('application/json')
			->set_status_header($status)
			->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
}