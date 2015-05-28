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
            /**
             * Metode untuk menampilkan form verifikasi email
             * 
             * Metode paling sederhana, metode ini menerima kode lamaran dari
             * halaman utama melalui $kode yang kemudia dikirim ke view.
             * 
             * @author Indra Kurniawan<indra@indramgl.web.id>
             * 
             * @var array   $data   Menampung array untuk kode lamaran
             * 
             */
            
            // Menampung kode lamaran
            $data['kode']   = $kode;
            // Menampilkan view>lamaran_vmail dengan $data
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
            // Memasukkan nomor lamaran ke dalam array untuk dikirim ke form selanjutnya
            $data['kode']   = $lamaran;
            // Memanggil view (Application>Views>lamaran_vhp.php
            $this->load->view('lamaran_vhp', $data);
        }
        
        public function doverifyemail()
        {
            /**
             * Metode untuk proses kode verifikasi email
             * 
             * Metode ini digunakan setelah kode verifikasi diterima oleh 
             * pelamar. Pelamar akan dibawa pada halaman selanjutnya jika kode 
             * yang dimasukkan kedalam form cocok dengan kode yang telah disimpan
             * di database.
             * 
             * @author Indra Kurniawan <indra@indramgl.web.id>
             * @var int     $kode_akt   Kode aktivasi yang diperoleh dari form
             * @var int     $lamaran    Nomor lamaran elektronik
             * @var string  $email      Alamat email pelamar
             * @var object  $query      Menampung hasil query database
             * @var int     $riil       Menampung hasil kode aktivasi dari DB
             * @var int     $dpkai      Integer untuk menset kolom dipakai di DB
             * @var array   $dt         Menampung update data ke DB
             * 
             */
            
            // Menampung nilai dari POST form kode aktivasi
            $kode_akt   = $this->input->post('aktivasi');
            // menampung kode lamaran dari POST form
            $lamaran    = $this->input->post('lamaran');
            // Menampung alamat email dari POST form
            $email      = $this->input->post('email');
            // Memanggil modul database
            $this->load->database();
            // Menset kriteria pencarian DB di kolom Lamaran 
            $this->db->where('Lamaran', $lamaran);
            // Melakukan query ke tabel 'aktivasi_mail' dan masuk ke array
            $query = $this->db->get('aktivasi_mail');
            // Memproses setiap hasil yang keluar dari database
            foreach ($query->result() as $row)
            {
                // Memasukkan setiap baris ke variabel $riil
                $riil = $row->Lamaran;
                // Memisahkan antara kode valid dan tidak
                if ($kode_akt = $riil) {
                    // Memasukkan kode lamaran ke dalam array untuk dikirim ke view
                    $data['kode']   = $lamaran;
                    // Set variabel dipakai sebagai 1 => Sudah dipakai
                    $dpkai      = 1;
                    // Memasukan $dpkai ke dalam array untuk update database
                    $dt = array(
                        'Dipakai' => $dpkai
                    );

                    // Menentukan kriteria update database
                    $this->db->where('Kode', $lamaran);
                    // Mengeksekusi update database
                    $this->db->update('aktivasi_mail', $dt);
                    // Mengirimkan hasil akhir ke Application>View>lamaran_vphone.php dengan $data
                    $this->load->view('lamaran_vphone', $data);
                } else {
                    // Jika kode salah maka akan ditampilkan halaman Application>view>lamaran_salah
                    $this->load->view('laraman_salah');
                }
            }
        }
        
        private function _pesan()
        {
            /**
             * Metode Untuk Mengirim SMS Melalui Twilio
             * 
             * Metode ini digunakan untuk mengirimkan SMS melalui API Twilio.
             * 
             * @author Indra Kurniawan <indra@indramgl.web.id>
             * 
             * @var object  $service    Object dari API Twilio
             * @var mixed   $number     Nomor Telepon Twilio Kita
             * @var mixed   $dest       Nomor telepon pelamar
             * @var int     $kode       Kode aktivasi HP
             * @var string  $message    Pesan yang dikirim ke HP pelamar
             * @var object  $msg        Hasil proses API Twilio, message ID
             * 
             */
            
            // Memanggil helper Twilio
            $this->load->helper('twilio');
            // Menginisiasi API Twilio
            $service = get_twilio_service();
            // Nomor Twilio kita
            $number = "+12677056262";
            // Nomor Pelamar
            $dest   = "+6285729416149";
            // Menghasilkan nomor acak 6 digit
            $kode       = mt_rand(100000, 999999);
            // Konfigurasi pesan yang akan dikirim ke pelamar
            $message    = "Silakan masukkan kode verifikasi berikut ini: ". $kode ." untuk melanjutkan lamaran anda.";
            
            // Mengirim perintah SMS ke API Twilio
            $msg = $service->account->messages->sendMessage($number, $dest, $message);
            
            // Mengembalikan hasil ke fungsi pemanggil
            return $msg->sid;
            //$this->load->view('lamaran_sendsms');
        }
        
        private function _mail($nama,$email,$kode_akt,$lamaran)
        {
            /**
             * Metode Pengiriman Email Melalui Mandrill
             * 
             * Metode ini digunakan untuk mengirimkan email melalui Mandrill.
             * Metode ini bekerja dengan bantuan API dan PHP Library yang disedia
             * kan oleh Mandrill. Terletak di folder Libraries>Mandrill
             * 
             * @author Indra Kurniawan<indra@indramgl.web.id>
             * 
             * @return object Hasil Pemanggilan
             */
            
            // Memanggila library email
            $this->load->library('email');
            // Konfigurasi email pengirim
            $this->email->from('no-reply@solusi-integral.co.id', 'Human Resource');
            // Konfigurasi email penerima
            $this->email->to($email);
            // Konfigurasi sub account HRD di Mandrill
            $this->email->set_header('X-MC-Subaccount', 'hrd');
            // Kondigurasi tag HRD di Mandrill
            $this->email->set_header('X-MC-Tags', 'hrd');
            // Konfigurasi untuk melacak setiap email yang dibuka dan juga click 
            $this->email->set_header('X-MC-Track', 'opens,clicks_all');
            // Konfigurasi subjek pesan
            $this->email->subject('Email Aktivasi Untuk'. $nama .' L-'. $lamaran .' ');
            // Konfiguaasi isi email yang dikirimkan ke pelamar
            $this->email->message('Masukkan nomor kode berikut: '. $kode_akt .' ke dalam sistem. Ini merupakan email yang terkirim oleh sistem. Kami mohon anda tidak membalas email ini. Terima kasih.');
            
            // Mengembalikan hasil ke metode pemanggil
            return $this->email->send();
        }
}