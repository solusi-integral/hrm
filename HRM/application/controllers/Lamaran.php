<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lamaran extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/lamaran
	 *	- or -
	 * 		http://example.com/index.php/lamaran/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/lamaran/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
            $this->output->cache(5);
            $this->load->view('lamaran_welcome');
	}
        
        public function verifikasihp()
        {
            $kode       = mt_rand(100000, 999999);
            $this->load->helper('form');
            echo form_open('lamaran/verifikasimail');
            
            
        }
        
        public function verifikasimail()
        {
            $kode       = mt_rand(10000000, 99999999);
            $this->load->view('lamaran_vmail', $kode);
        }
        
        private function _pesan()
        {
            $this->load->helper('twilio');
            $service = get_twilio_service();
            $number = "+12677056262";
            $dest   = "+6285729416149";
            $kode       = mt_rand(100000, 999999);
            $message    = "Silakan masukkan kode verifikasi berikut ini: ". $kode ." untuk melanjutkan lamaran anda.";
            
            //$client = new Services_Twilio($sid, $token);
            
            $message = $service->account->messages->sendMessage($number, $dest, $message);
            
            echo $message->sid;
            //$this->load->view('lamaran_sendsms');
            
        }
        
        private function _mail()
        {
            $this->load->library('email');

            $this->email->from('no-reply@solusi-integral.co.id', 'Human Resource');
            $this->email->to('indra@indramgl.web.id');
            //$this->email->cc('another@another-example.com');
            //$this->email->bcc('them@their-example.com');
            $this->email->set_header('X-MC-Subaccount', 'hrd');
            $this->email->set_header('X-MC-Tags', 'hrd');
            $this->email->set_header('X-MC-Track', 'opens,clicks_all');

            $this->email->subject('Email Test');
            $this->email->message('Testing the email class.');

            $this->email->send();
        }
}