import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        uuid: String,
        mensagem: String
    };

    show() {
        const uuid = this.uuidValue;
        
        let mensagem = this.mensagemValue;
        
        if ('' === mensagem) {
            mensagem = 'Deseja realmente excluir?';
        }

        $('#deleteUuid').val(uuid);
        $('#deleteMensagem').html(mensagem);
        $('#deleteModal').modal('show');
        $('#deleteModalButton').focus();
    }
}
