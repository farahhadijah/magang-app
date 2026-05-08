<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Surat Permohonan PKL</title>

<style>

@page {
    size: 210mm 330mm;
    margin: 7mm 20mm 20mm 20mm;
}

body {
    font-family: "Times New Roman", serif;
    font-size: 10.5pt;
    margin: 0;
}

/* ================= KOP ================= */
.kop table {
    width: 100%;
}

.logo {
    width: 18%;
    text-align: center;
}

.logo img {
    width: 100px;
}

.text {
    width: 82%;
    text-align: center;
    line-height: 1.3;
}

.text h2 {
    font-size: 15pt;
    margin: 0;
}

.text h1 {
    font-size: 22pt;
    margin: 0;
}

.text .sk {
    font-size: 10pt;
    margin: 2px 0;
}

.text .sub {
    font-size: 9.5pt;
    margin: 0;
}

/* ================= GARIS ================= */
.garis-tebal {
    border-top: 3px solid black;
    margin: 6px 0 2px 0;
}

.garis-tipis {
    border-top: 1px solid black;
    margin-bottom: 12px;
}

/* ================= HEADER ================= */
.header-surat {
    position: relative;
    line-height: 1.5;
}

.tanggal {
    position: absolute;
    right: 0;
    top: 0;
}

/* ================= ISI ================= */
.isi p {
    margin: 7px 0;
    text-align: justify;
    line-height: 1.5;
}

/* BIODATA */
.biodata td {
    padding: 2px 0;
    vertical-align: top;
}

/* ================= TTD ================= */
.ttd {
    width: 40%;
    margin-left: auto;
    margin-top: 35px; /* pas, tidak terlalu jauh */
    text-align: center;
}

</style>
</head>

<body>

@include('surat._body_pengantar')

</body>
</html>