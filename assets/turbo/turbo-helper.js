import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

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
        });

        document.addEventListener('turbo:load', () => {
            if (!(document.querySelectorAll('#products_table_wrapper').length > 0) &&
                document.querySelectorAll('table').length > 0) {
                $('#products_table').DataTable();
            }

            if (!(document.querySelectorAll('.ck-editor').length > 0) &&
                document.querySelectorAll('textarea').length > 0) {
                ClassicEditor.create(document.querySelector('textarea'))
            }
        })
    }
}
export default new TurboHelper();