const TurboHelper = class {
    constructor() {
        document.addEventListener('turbo:before-cache', () => {
            // internal way to see if sweetalert2 has been imported yet
            if (__webpack_modules__[require.resolveWeak('sweetalert2')]) {
                // because we know it's been imported, this will run synchronously
                import(/* webpackMode: 'weak' */'sweetalert2').then((Swal) => {
                    if (Swal.default.isVisible()) {
                        Swal.default.getPopup().style.animationDuration = '0ms'
                        Swal.default.close();
                    }
                })
            }

            // $('textarea').ckeditor({
            //     readOnly: true
            // });
        });

        document.addEventListener('turbo:load', () => {
            $('#products_table').DataTable();

            // $('textarea').ckeditor({
            //     readOnly: false
            // });
        })
    }
}
export default new TurboHelper();