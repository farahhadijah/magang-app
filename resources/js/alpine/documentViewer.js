// resources/js/alpine/documentViewer.js

export function registerDocumentViewer(Alpine) {
    Alpine.data("documentViewer", () => ({
        isOpen: false,
        fileUrl: "",
        fileType: "",
        title: "",

        pdfViewerBaseUrl: '/vendor/pdfjs/web/viewer.html',

        isMobile() {
            return window.innerWidth < 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        },

        openModal(url, title = "") {
            if (!url) return;
            const type = this.getFileType(url);

            // Fallback mobile: Jika PDF dibuka di mobile, langsung buka di tab baru agar cepat & ringan
            if (type === "pdf" && this.isMobile()) {
                window.open(url, "_blank");
                return;
            }

            this.fileUrl = url;
            this.title = title;
            this.fileType = type;
            this.isOpen = true;
            document.body.classList.add("overflow-hidden");
        },

        closeModal() {
            this.isOpen = false;
            this.fileUrl = "";
            this.fileType = "";
            this.title = "";
            document.body.classList.remove("overflow-hidden");
        },

        getFileType(url) {
            if (!url) return "unknown";
            const cleanUrl = url.split("?")[0];
            const extension = cleanUrl.split(".").pop().toLowerCase();

            if (extension === "pdf") return "pdf";
            if (["jpg", "jpeg", "png", "gif", "webp", "bmp", "svg"].includes(extension)) return "image";
            if (["doc", "docx", "xls", "xlsx", "ppt", "pptx"].includes(extension)) return "office";

            return "unknown";
        },

        isPdf() { return this.fileType === "pdf"; },
        isImage() { return this.fileType === "image"; },
        isOffice() { return this.fileType === "office"; },
        isUnknown() { return this.fileType === "unknown"; },

        pdfViewerUrl() {
            if (!this.isPdf()) return "";
            const encodedUrl = encodeURIComponent(this.fileUrl);
            return `${this.pdfViewerBaseUrl}?file=${encodedUrl}&disableRange=true&disableStream=true`;
        },

        officeViewerUrl() {
            if (!this.isOffice()) return "";
            return `https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(this.fileUrl)}`;
        }
    }));
}
