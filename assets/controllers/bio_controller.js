import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const bio = document.getElementById("bioTextarea");
        const nbMax = 255;
        let count = bio.value.length;

        this.element.textContent = count + " / " + nbMax;

        bio.addEventListener("input", () => {
            count = bio.value.length;
            this.element.textContent = count + " / " + nbMax;

            if (count > nbMax) {
                this.element.classList.add('text-danger');
            }
            else {
                this.element.classList.remove('text-danger');
            }
        })
    }
}
