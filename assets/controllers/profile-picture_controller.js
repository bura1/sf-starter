import { Controller } from '@hotwired/stimulus';
import * as FilePond from 'filepond';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import Swal from "sweetalert2";

export default class extends Controller {
    static targets = ['userId'];

    connect() {
        FilePond.registerPlugin(
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );
        FilePond.create(
            document.querySelector('input'),
            {
                labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
                server: {
                    process: {
                        url: '/upload-profile-picture/' + this.userIdTarget.innerText,
                        method: "POST"
                    },
                }
            }
        );

        $('.filepond').on('FilePond:processfile', function(e) {
            location.reload();
        });
    }

    delete() {
        fetch('delete-profile-picture/' + this.userIdTarget.innerText, {
            method: 'DELETE'
        }).then((response) => {
            if (!response.ok) {
                Swal.fire({
                    icon: 'error',
                    html: 'Failed!'
                });
            } else {
                location.reload();
            }
        })
    }
}
