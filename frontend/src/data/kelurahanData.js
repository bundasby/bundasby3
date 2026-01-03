// Data Kelurahan Kota Surabaya 2025
// Sumber: BPS Kota Surabaya, 31 Kecamatan, 154 Kelurahan

export const kelurahanData = {
  // Surabaya Pusat
  'Tegalsari': ['Tegalsari', 'Wonorejo', 'Keputran', 'Dr. Soetomo', 'Kedungdoro'],
  'Genteng': ['Embong Kaliasin', 'Ketabang', 'Kapasari', 'Peneleh', 'Genteng'],
  'Bubutan': ['Bubutan', 'Alun-Alun Contong', 'Gundih', 'Jepara', 'Tembok Dukuh'],
  'Simokerto': ['Simokerto', 'Sidodadi', 'Simolawang', 'Kapasan', 'Tambakrejo'],

  // Surabaya Utara
  'Pabean Cantikan': ['Bongkaran', 'Nyamplungan', 'Tanjung Perak', 'Krembangan Utara'],
  'Semampir': ['Ampel', 'Pegirian', 'Sidotopo', 'Ujung', 'Wonokusumo'],
  'Krembangan': ['Dupak', 'Kemayoran', 'Krembangan Selatan', 'Morokrembangan', 'Perak Barat'],
  'Kenjeran': ['Bulak Banteng', 'Tambak Wedi', 'Tanah Kali Kedinding', 'Sidotopo Wetan'],
  'Bulak': ['Bulak', 'Kedung Cowek', 'Kenjeran', 'Sukolilo Baru'],

  // Surabaya Timur  
  'Tambaksari': ['Dukuh Setro', 'Gading', 'Kapas Madya Baru', 'Pacar Keling', 'Pacar Kembang', 'Ploso', 'Rangkah', 'Tambaksari'],
  'Gubeng': ['Airlangga', 'Barata Jaya', 'Gubeng', 'Kertajaya', 'Mojo', 'Pucang Sewu'],
  'Rungkut': ['Kalirungkut', 'Kedung Baruk', 'Medokan Ayu', 'Penjaringan Sari', 'Rungkut Kidul', 'Wonorejo'],
  'Tenggilis Mejoyo': ['Kendangsari', 'Kutisari', 'Panjang Jiwo', 'Tenggilis Mejoyo'],
  'Gunung Anyar': ['Gunung Anyar', 'Gunung Anyar Tambak', 'Rungkut Menanggal', 'Rungkut Tengah'],
  'Sukolilo': ['Gebang Putih', 'Keputih', 'Klampis Ngasem', 'Medokan Semampir', 'Menur Pumpungan', 'Nginden Jangkungan', 'Semolowaru'],
  'Mulyorejo': ['Dukuh Sutorejo', 'Kalijudan', 'Kalisari', 'Kejawan Putih Tambak', 'Manyar Sabrangan', 'Mulyorejo'],

  // Surabaya Selatan
  'Sawahan': ['Banyu Urip', 'Kupang Krajan', 'Pakis', 'Patemon', 'Putat Jaya', 'Sawahan'],
  'Wonokromo': ['Darmo', 'Jagir', 'Ngagel', 'Ngagel Rejo', 'Sawunggaling', 'Wonokromo'],
  'Karang Pilang': ['Karang Pilang', 'Kebraon', 'Kedurus', 'Waru Gunung'],
  'Dukuh Pakis': ['Dukuh Kupang', 'Dukuh Pakis', 'Gunung Sari', 'Pradah Kalikendal'],
  'Wiyung': ['Babatan', 'Balas Klumprik', 'Jajar Tunggal', 'Wiyung'],
  'Wonocolo': ['Bendul Merisi', 'Jemur Wonosari', 'Margorejo', 'Sidosermo', 'Siwalankerto'],
  'Gayungan': ['Dukuh Menanggal', 'Gayungan', 'Ketintang', 'Menanggal'],
  'Jambangan': ['Jambangan', 'Karah', 'Kebonsari', 'Pagesangan'],

  // Surabaya Barat
  'Tandes': ['Balongsari', 'Banjar Sugihan', 'Karang Poh', 'Manukan Kulon', 'Manukan Wetan', 'Tandes'],
  'Sukomanunggal': ['Putat Gede', 'Simomulyo', 'Simomulyo Baru', 'Sono Kwijenan', 'Sukomanunggal', 'Tanjungsari'],
  'Asem Rowo': ['Asem Rowo', 'Genting Kalianak', 'Tambak Sarioso'],
  'Benowo': ['Kandangan', 'Romokalisari', 'Sememi', 'Tambak Oso Wilangun'],
  'Pakal': ['Babat Jerawat', 'Benowo', 'Pakal', 'Sumber Rejo'],
  'Lakarsantri': ['Bangkingan', 'Jeruk', 'Lakarsantri', 'Lidah Kulon', 'Lidah Wetan', 'Sumur Welut'],
  'Sambikerep': ['Bringin', 'Lontar', 'Made', 'Sambikerep']
}

// Generate Bunda PAUD Kelurahan data
export function generateKelurahanBundaPaud() {
  const data = []
  let id = 1000
  
  Object.entries(kelurahanData).forEach(([kecamatan, kelurahans]) => {
    kelurahans.forEach(kelurahan => {
      data.push({
        id: id++,
        name: '[Nama Bunda PAUD]',
        position: 'Bunda PAUD Kelurahan',
        area: kelurahan,
        kecamatan: kecamatan,
        kelurahan: kelurahan,
        type: 'kelurahan',
        photo: 'https://placehold.co/150x150/64748b/ffffff?text=BP'
      })
    })
  })
  
  return data
}

// Total: 154 Kelurahan
export const totalKelurahan = Object.values(kelurahanData).flat().length
