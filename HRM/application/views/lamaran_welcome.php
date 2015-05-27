<?php 
header('Cache-Control: public, max-age=600, must-revalidate');
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Formulir Lamaran Pekerjaan Online</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>
    <div id="container">
        <h1>Formulir Calon Tenaga Kerja</h1>
        <p>Perusahaan Teknologi Informasi yang sedang berkembang membutuhkan beberapa tenaga kerja baru  dengan posisi dan kualifikasi sebagai berikut:</p>

1.	Data Entry Staf [DES] [Urgent]
Kualifikasi:
◦	Lulusan minimal SMA / SMK jurusan TKJ, atau RPL, diizinkan tahun ajaran 2014/2015
◦	Mampu dan memahami pengoperasian komputer;
◦	Pria / wanita;
◦	Umur maksimal 22 tahun;
◦	Memahami bahasa Inggris (dasar), dibuktikan dengan sertifikat (jika ada);
◦	Diutamakan lulusan baru.

2.	Marketing Staff [STM] [Urgent]
Qualifications:
◦	Fluency in both English and Indonesian; Oral, Listening, and Writing;
◦	Candidates with in-depth knowledge about Computer, Internet, and general technology would be highly considered;
◦	Younger than 25 years old;
◦	Application Letters and curriculum vitae must be written in English;
◦	Fresh graduates are highly encouraged to apply.


===================================
Pelamar yang pernah mengirimkan data sebelumnya diharapkan untuk tidak mengirim ulang karena semua data tercatat dalam basis data kami.
===================================
====                                                      ====
==== TIDAK DIPUNGUT BIAYA              ====
====                                                      ====
====                                                      ====
===================================
    <br>
    <center><a href="<?php echo $this->config->base_url(); $kode = mt_rand(1000, 100000); ?>index.php/lamaran/verifikasimail/<?php echo $kode;?>">Isi Formulir Lamaran</a></center>
    </div>
        
    <p class="footer">Nomor Lamaran: L-<?php echo $kode; ?>. Halaman diproses dalam {elapsed_time}.</p>
    </body>
</html>


