import { Controller } from '@hotwired/stimulus';

/*
 * The following line makes this controller "lazy": it won't be downloaded until needed
 * See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
 */

/* stimulusFetch: 'lazy' */
export default class extends Controller {

    redirecionar(event) {
        const registros = event.target.value;

        const url = new URL(window.location.href);

        url.searchParams.delete('pagina');

        url.searchParams.set("registros-por-pagina", registros);
        
        $.LoadingOverlay('show');

        window.location.href = url.toString();
    }

}
