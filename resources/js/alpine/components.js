/**
 * Alpine.js components — ganti script vanilla / manipulasi DOM manual di Blade.
 */
export function registerAlpineComponents(Alpine) {

    Alpine.data('pdfViewer', () => ({
        isOpen: false,
        fileUrl: '',
        openModal(url) {
            this.fileUrl = url;
            this.isOpen = true;
        },
        closeModal() {
            this.fileUrl = '';
            this.isOpen = false;
        },
    }));

    Alpine.data('togglePdf', (src = '') => ({
        visible: false,
        src,
        toggle() {
            this.visible = !this.visible;
        },
    }));

    Alpine.data('copyTextarea', () => ({
        copied: false,
        copy(textareaId) {
            const el = document.getElementById(textareaId);
            if (!el) return;
            el.select();
            el.setSelectionRange(0, 99999);
            navigator.clipboard?.writeText(el.value).catch(() => {
                document.execCommand('copy');
            });
            this.copied = true;
            setTimeout(() => (this.copied = false), 2000);
            alert('Pesan berhasil disalin');
        },
    }));

    Alpine.data('selectAllGroup', (checkboxClass = '.row-checkbox') => ({
        selectAll: false,
        toggleAll() {
            document.querySelectorAll(checkboxClass).forEach((cb) => {
                if (!cb.disabled) cb.checked = this.selectAll;
            });
        },
    }));

    Alpine.data('logbookPage', (initialStatuses = {}) => ({
        openId: null,
        currentKegiatan: '',
        selectAll: false,
        statuses: { ...initialStatuses },
        review: { status: 'approved', catatan: '' },
        catatanError: false,
        openModal(id, kegiatan = '') {
            this.openId = id;
            this.currentKegiatan = kegiatan;
            this.review = { status: 'approved', catatan: '' };
            this.catatanError = false;
        },
        closeModal() {
            this.openId = null;
        },
        get showCatatan() {
            return this.review.status === 'revisi';
        },
        toggleSelectAll() {
            document.querySelectorAll('.logbook-checkbox').forEach((cb) => {
                cb.checked = this.selectAll;
            });
        },
        async submitReview(id) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!token) return;

            let catatan = this.review.catatan?.trim() ?? '';
            this.catatanError = false;

            if (this.review.status === 'revisi' && catatan === '') {
                alert('Silakan isi catatan ketika memilih "Perlu Revisi".');
                this.catatanError = true;
                return;
            }

            if (this.review.status === 'approved') {
                catatan = null;
            }

            try {
                const res = await fetch(`/dosen/logbook/${id}/review-ajax`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({
                        status: this.review.status,
                        catatan,
                    }),
                });

                if (res.status === 422) {
                    const data = await res.json();
                    if (data.errors?.catatan) {
                        alert(data.errors.catatan.join(' '));
                    }
                    return;
                }

                if (!res.ok) {
                    const data = await res.json().catch(() => ({}));
                    alert(data.message || 'Terjadi kesalahan.');
                    return;
                }

                const data = await res.json();
                if (data.success) {
                    this.statuses[id] = data.status;
                    this.closeModal();
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan saat mengirim data. Silakan coba lagi.');
            }
        },
        statusLabel(id) {
            const s = this.statuses[id];
            if (s === 'approved') return 'approved';
            if (s === 'revisi') return 'revisi';
            return 'pending';
        },
    }));

    Alpine.data('kaprodiPengajuanShow', (lokasi = null, dosenList = []) => ({
        isOpen: false,
        fileUrl: '',
        searchDosen: '',
        dosenList,
        openModal(url) {
            this.fileUrl = url;
            this.isOpen = true;
        },
        closeModal() {
            this.fileUrl = '';
            this.isOpen = false;
        },
        matchesDosen(nama, keahlian) {
            if (!this.searchDosen) return true;
            const s = this.searchDosen.toLowerCase();
            return (
                (nama || '').toLowerCase().includes(s) ||
                (keahlian || '').toLowerCase().includes(s)
            );
        },
        get dosenSearchEmpty() {
            if (!this.searchDosen) return false;
            return !this.dosenList.some((d) =>
                this.matchesDosen(d.nama, d.keahlian)
            );
        },
        initMap() {
            if (!lokasi || typeof L === 'undefined') return;

            let lat = null;
            let lon = null;

            const qMatch = lokasi.match(/q=(-?\d+\.\d+),(-?\d+\.\d+)/);
            if (qMatch) {
                lat = parseFloat(qMatch[1]);
                lon = parseFloat(qMatch[2]);
            }

            if (!lat) {
                const atMatch = lokasi.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
                if (atMatch) {
                    lat = parseFloat(atMatch[1]);
                    lon = parseFloat(atMatch[2]);
                }
            }

            if (!lat || !lon) return;

            const el = document.getElementById('mapKaprodi');
            if (!el || el._leaflet_id) return;

            const kampusLat = -7.1224094;
            const kampusLng = 112.4223971;

            const map = L.map('mapKaprodi').setView([lat, lon], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            const greenIcon = new L.Icon({
                iconUrl:
                    'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41],
            });

            L.marker([lat, lon]).addTo(map).bindPopup('Tempat PKL Mahasiswa').openPopup();
            L.marker([kampusLat, kampusLng], { icon: greenIcon })
                .addTo(map)
                .bindPopup('Kampus Universitas Islam Lamongan');
            L.polyline(
                [
                    [kampusLat, kampusLng],
                    [lat, lon],
                ],
                { color: 'blue', weight: 4, opacity: 0.7 }
            ).addTo(map);

            const group = new L.featureGroup([
                L.marker([lat, lon]),
                L.marker([kampusLat, kampusLng]),
            ]);
            map.fitBounds(group.getBounds().pad(0.3));
        },
    }));

    Alpine.data('suratPage', () => ({
        modalOpen: false,
        previewUrl: '',
        selectAll: false,
        openModal(id) {
            this.previewUrl = `/staff/surat-pengantar/${id}/preview`;
            this.modalOpen = true;
        },
        closeModal() {
            this.modalOpen = false;
            this.previewUrl = '';
        },
        printPreview() {
            const iframe = this.$refs.previewFrame;
            if (iframe?.contentWindow) {
                iframe.contentWindow.print();
            }
        },
        toggleSelectAll() {
            document.querySelectorAll('.checkbox-item:not(:disabled)').forEach((cb) => {
                cb.checked = this.selectAll;
            });
        },
        submitValidasi(id) {
            if (!confirm('Kirim validasi surat ke mahasiswa?')) return;
            const form = this.$refs.formValidasi;
            if (form) {
                form.action = `/staff/surat-pengantar/${id}/validasi`;
                form.submit();
            }
        },
        submitBulkValidasi() {
            const checked = [...document.querySelectorAll('.checkbox-item:checked')];
            if (checked.length === 0) {
                alert('Pilih minimal satu mahasiswa.');
                return;
            }
            if (!confirm('Kirim validasi ke mahasiswa terpilih?')) return;
            const form = this.$refs.bulkValidasiForm;
            if (!form) return;
            form.innerHTML = '';
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrf) {
                const t = document.createElement('input');
                t.type = 'hidden';
                t.name = '_token';
                t.value = csrf;
                form.appendChild(t);
            }
            checked.forEach((item) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = item.value;
                form.appendChild(input);
            });
            form.submit();
        },
        openBulkModal() {
            const checked = [...document.querySelectorAll('.checkbox-item:checked')];
            if (checked.length === 0) {
                alert('Pilih minimal satu mahasiswa.');
                return;
            }
            const form = this.$refs.bulkPreviewForm;
            if (!form) return;
            form.innerHTML = '';
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrf) {
                const t = document.createElement('input');
                t.type = 'hidden';
                t.name = '_token';
                t.value = csrf;
                form.appendChild(t);
            }
            checked.forEach((item) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = item.value;
                form.appendChild(input);
            });
            form.target = 'previewFrame';
            form.action = '/staff/surat-pengantar/bulk-preview';
            this.modalOpen = true;
            this.$nextTick(() => form.submit());
        },
    }));

    Alpine.data('sertifikatModal', () => ({
        open: false,
        url: 'about:blank',
        openModal(url) {
            this.url = url;
            this.open = true;
        },
        closeModal() {
            this.open = false;
            this.url = 'about:blank';
        },
    }));

    Alpine.data('mitraFileModal', () => ({
        fileOpen: false,
        revisiOpen: false,
        fileUrl: '',
        downloadUrl: '',
        revisiAction: '',
        openFile(url) {
            this.fileUrl = this.viewerSrc(url);
            this.downloadUrl = url;
            this.fileOpen = true;
        },
        closeFile() {
            this.fileOpen = false;
            this.fileUrl = '';
        },
        openRevisi(id, action) {
            this.revisiAction = action;
            this.revisiOpen = true;
        },
        closeRevisi() {
            this.revisiOpen = false;
        },
        viewerSrc(url) {
            const ext = url.split('.').pop().toLowerCase();
            if (ext === 'pdf') return url;
            if (['doc', 'docx', 'xls', 'xlsx'].includes(ext)) {
                return `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(url)}`;
            }
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) return url;
            return '';
        },
    }));

    Alpine.data('mitraAkunWa', (username, password) => ({
        kirimWA() {
            document.querySelectorAll('.nomor').forEach((el) => {
                const no = el.dataset.no || el.textContent?.trim();
                if (!no) return;
                const pesan = `Username: ${username}\nPassword: ${password}`;
                const link = `https://wa.me/${no.replace(/^0/, '62')}?text=${encodeURIComponent(pesan)}`;
                window.open(link, '_blank');
            });
        },
    }));

    Alpine.data('staffMitraIndex', () => ({
        openForms: {},
        toggleForm(id) {
            this.openForms[id] = !this.openForms[id];
        },
    }));

    Alpine.data('fakultasBulk', () => ({
        selectAll: false,
        toggleAll() {
            document.querySelectorAll('.rowCheckbox').forEach((cb) => {
                cb.checked = this.selectAll;
            });
        },
        bulkDelete(event) {
            event.preventDefault();
            const checked = [...document.querySelectorAll('.rowCheckbox:checked')];
            if (checked.length === 0) {
                alert('Pilih minimal satu data.');
                return;
            }
            if (!confirm('Yakin hapus data terpilih?')) return;
            const form = this.$refs.bulkDeleteForm;
            const container = this.$refs.bulkIds;
            if (!form || !container) return;
            container.innerHTML = '';
            checked.forEach((cb) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = cb.value;
                container.appendChild(input);
            });
            form.submit();
        },
    }));

    Alpine.data('historiPerPage', (mobileUrl, desktopUrl) => ({
        init() {
            if (sessionStorage.getItem('perPageRedirectDone')) return;
            const isMobile = window.matchMedia('(max-width: 768px)').matches;
            sessionStorage.setItem('perPageRedirectDone', '1');
            window.location.href = isMobile ? mobileUrl : desktopUrl;
        },
    }));

    Alpine.data('navScrollActive', () => ({
        init() {
            this.$nextTick(() => {
                const active = this.$el.querySelector('.bg-green-800');
                active?.scrollIntoView({ block: 'center' });
            });
        },
    }));
}
