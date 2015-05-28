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
         * 
         * 
         * 
	 */
	public function index()
	{
            /**
             * Fungsi Default Halaman Lamaran
             * 
             * Fungsi ini digunakan untuk menampilkan halaman utama lamaran 
             * pekerjaan. Halaman ini harus berisikan informasi terkait
             * lowongan pekerjaan apa yang tersedia di perusahaan kita.
             * 
             * 
             */
            // Fungsi dibawah digunakan untuk menset cache halaman selama 5 menit
            $this->output->cache(5);
            // Menampilkan Application>View>Lamaran_welcome.php
            $this->load->view('lamaran_welcome');
	}
        
        public function verifikasihp()
        {
            /**
             * Fungsi untuk memverifikasi nomor HP
             * 
             */
            $kode       = mt_rand(100000, 999999);
            $this->load->helper('form');
            echo form_open('lamaran/verifikasimail');
            
            
        }
        
        public function verifikasimail($kode)
        {
            $data['kode']   = $kode;
            $this->load->view('lamaran_vmail', $data);
        }
        
        public function doverifymail()
        {
            /**
             * Fungsi Proses Form Verifikasi Email
             * 
             * Metode ini digunakan untuk memproses data yang dikirimkan dari
             * form sebelumnya (Application>Views>lamaran_vmail.php
             * 
             * Metode ini akan memasukkan data nama lengkap, alamat email, 
             * nomor lamaran dan kode aktivasi ke dalam database. Selain itu
             * pengiriman email juga dipanggil dalam metode ini.
             * 
             * @author Indra Kurniawan <indra@indramgl.web.id>
             * 
             * @var int     $kode_akt   Kode Aktivasi yang digenerate oleh metode lain
             * @var int     $lamaran    Nomor lamaran elektronik
             * @var string  $nama       Nama pelamar
             * @var mixed   $email      Alamat Email Calon Pelamar
             * @var array   $db         Array Objek database
             */

            // Menyimpan hasil POST dari form ke dalam variabel $kode_akt
            $kode_akt   = $this->input->post('aktivasi');
            // Menyimpan hasil POST dari form nomor lamaran ke dalam $lamaran
            $lamaran    = $this->input->post('lamaran');
            // Menyimpan hasil POST dari form nama ke dalam $nama
            $nama       = $this->input->post('nama');
            // Menyimpan hasil POST dari form email ke dalam $email
            $email      = $this->input->post('email');
            
            // Memanggil modul / helper CI Database
            $this->load->database();
            // Membuat array data dari form untuk dimasukkan ke dalam database
            $db = array(
                'Kode' => $kode_akt,
                'email' => $email,
                'Lamaran' => $lamaran
            );
            // Memasukkan data array $db ke dalam tabel 'aktivasi_mail'
            $this->db->insert('aktivasi_mail', $db);
            
            // Memanggil metode internal _mail untuk mengeksekusi pengiriman email
            $this->_mail($nama,$email,$kode_akt,$lamaran);
            // 
            $data['kode']   = $lamaran;
            $this->load->view('lamaran_vhp', $data);
        }
        
        public function doverifyemail()
        {
            $kode_akt   = $this->input->post('aktivasi');
            $lamaran    = $this->input->post('lamaran');
            $email      = $this->input->post('email');
            $limit      = 1;
            $this->load->database();
            $this->db->where('Lamaran', $lamaran);
            $query = $this->db->get('aktivasi_mail');
            foreach ($query->result() as $row)
            {
                $riil = $row->Lamaran;
                if ($kode_akt = $riil) {
                    $data['kode']   = $lamaran;
                    $dpkai      = 1;
                    $dt = array(
                        'Dipakai' => $dpkai
                    );

                    $this->db->where('Kode', $lamaran);
                    $this->db->update('aktivasi_mail', $dt);
                    $this->load->view('lamaran_vphone', $data);
                } else {
                    $this->load->view('laraman_salah');
                }
            }
        }
        
        private function _pesan()
        {
            $this->load->helper('twilio');
            $service = get_twilio_service();
            $number = "+12677056262";
            $dest   = "+6285729416149";
            $kode       = mt_rand(100000, 999999);
            $message    = "Silakan masukkan kode verifikasi berikut ini: ". $kode ." untuk melanjutkan lamaran anda.";
            
            $message = $service->account->messages->sendMessage($number, $dest, $message);
            
            echo $message->sid;
            //$this->load->view('lamaran_sendsms');
        }
        
        private function _mail($nama,$email,$kode_akt,$lamaran)
        {
            $this->load->library('email');

            $this->email->from('no-reply@solusi-integral.co.id', 'Human Resource');
            $this->email->to($email);
            $this->email->set_header('X-MC-Subaccount', 'hrd');
            $this->email->set_header('X-MC-Tags', 'hrd');
            $this->email->set_header('X-MC-Track', 'opens,clicks_all');

            $this->email->subject('Email Aktivasi Untuk'. $nama .' L-'. $lamaran .' ');
            $this->email->message('Masukkan nomor kode berikut: '. $kode_akt .' ke dalam sistem. Ini merupakan email yang terkirim oleh sistem. Kami mohon anda tidak membalas email ini. Terima kasih.');

            return $this->email->send();
        }
}