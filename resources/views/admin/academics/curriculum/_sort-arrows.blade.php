@push('scripts')
<script>
(function () {
    document.querySelectorAll('#curriculum-table th.cursor-pointer').forEach(function (header) {
        const label = header.textContent.replace(/[\u2191\u2193\u2195\u25b2\u25bc]/g, '').replace(/\s*â.*$/g, '').replace(/[^A-Za-z0-9 /]+/g, '').trim();
        header.innerHTML = '<span class="curriculum-sort-label">' + label + '</span><span class="curriculum-sort-arrow" aria-hidden="true">\u25bc</span>';
        header.dataset.asc = '0';
        header.addEventListener('click', function () {
            const ascending = header.dataset.asc === '1';
            header.dataset.asc = ascending ? '0' : '1';
            header.querySelector('.curriculum-sort-arrow').textContent = ascending ? '\u25bc' : '\u25b2';
        });
    });
})();
</script>
@endpush
