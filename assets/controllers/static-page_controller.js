import { Controller } from '@hotwired/stimulus';
import Swal from "sweetalert2";

export default class extends Controller {
    static targets = ['id', 'title'];

    delete() {
        console.log('hej');
        Swal.fire({
            title: 'Delete ' + this.titleTarget.innerText + '?',
            html: 'Do you want to delete <strong>' + this.titleTarget.innerText + '</strong> page?',
            icon: 'question',
            confirmButtonText: 'Delete',
            showCloseButton: true,
            preConfirm: () => {
                return fetch('/static-pages/delete/' + this.idTarget.innerText, {
                    method: 'DELETE'
                }).then((response) => {
                    if (!response.ok) {
                        Swal.fire({
                            icon: 'error',
                            html: 'Failed!'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            html: 'Successfully deleted <strong>' + this.titleTarget.innerText + '</strong> page!',
                        }).then(function() {
                            location.reload();
                        });
                    }
                })
            }
        });
    }
}