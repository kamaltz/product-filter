# Product Filter Elementor Plugin

Plugin Elementor untuk menampilkan filter produk dan grid produk yang dapat dikustomisasi, mirip dengan https://orpcatalog.id/collections/performance-all-day

## Fitur

### Widget Product Filter
- Filter berdasarkan kategori produk
- Filter berdasarkan tipe produk (Men, Women, Unisex)
- Filter berdasarkan range harga
- Tombol Apply Filter dan Clear All
- Styling yang dapat dikustomisasi

### Widget Product Display
- Grid produk yang responsif
- Kontrol jumlah kolom (1-6)
- Kontrol gap antar produk
- Kontrol jumlah produk per halaman
- Paginasi yang dapat diaktifkan/nonaktifkan
- Styling lengkap (background, border, shadow, typography)

## Instalasi

1. Upload folder plugin ke `/wp-content/plugins/`
2. Aktifkan plugin melalui WordPress admin
3. Pastikan Elementor sudah terinstall dan aktif

## Penggunaan

1. Buka Elementor editor
2. Cari widget "Product Filter" dan "Product Display"
3. Drag widget ke halaman
4. Kustomisasi pengaturan sesuai kebutuhan

## Kustomisasi

### Product Filter Widget
- **Content Tab:**
  - Filter Title: Judul untuk section filter
  - Show Category Filter: Toggle untuk menampilkan filter kategori
  - Show Type Filter: Toggle untuk menampilkan filter tipe produk
  - Show Price Filter: Toggle untuk menampilkan filter harga

- **Style Tab:**
  - Background Color: Warna background filter
  - Text Color: Warna teks
  - Padding: Padding container filter

### Product Display Widget
- **Content Tab:**
  - Products Per Page: Jumlah produk per halaman (1-50)
  - Columns: Jumlah kolom grid (1-6, responsif)
  - Gap: Jarak antar produk
  - Show Pagination: Toggle untuk menampilkan paginasi

- **Style Tab:**
  - Product Background: Warna background produk
  - Border: Border produk
  - Border Radius: Radius border
  - Box Shadow: Shadow produk

- **Typography Tab:**
  - Title Typography: Styling judul produk
  - Title Color: Warna judul
  - Price Typography: Styling harga
  - Price Color: Warna harga

## Custom Fields

Plugin ini menggunakan custom fields berikut untuk produk:
- `_price`: Harga produk
- `product_type`: Tipe produk (Men/Women/Unisex)
- `product_cat`: Kategori produk (taxonomy)

## AJAX Filtering

Plugin menggunakan AJAX untuk filtering real-time tanpa reload halaman. Filter akan diterapkan berdasarkan:
- Kategori produk yang dipilih
- Tipe produk yang dipilih  
- Range harga yang dipilih

## Responsive Design

Plugin sudah dioptimasi untuk berbagai ukuran layar:
- Desktop: 4 kolom default
- Tablet: 3 kolom
- Mobile: 2 kolom
- Mobile kecil: 1 kolom

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Changelog

### Version 1.0.0
- Initial release
- Product Filter widget
- Product Display widget
- AJAX filtering
- Responsive design
- Full Elementor integration