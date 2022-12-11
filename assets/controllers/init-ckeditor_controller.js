import { Controller } from '@hotwired/stimulus';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default class extends Controller {
    static targets = ['id', 'name'];

    connect() {
        if (!this.alreadyInDom()) {
            ClassicEditor
                .create(document.querySelector('textarea'))
        }
    }

    alreadyInDom() {
        return document.querySelectorAll('.ck-editor').length > 0
    }
}