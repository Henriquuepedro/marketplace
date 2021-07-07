/*
 * File upload implementation with Dropzone.js
 * @author Leandro Antonello <lantonello@gmail.com>
 *
 */

var Uploader = Uploader || {
    /**
     * Array of all Dropzone handlers.
     * @type Array
     */
    handlers: null,

    /**
     * The CSRF token
     * @type String
     */
    token: $('input[name=_token]').val(),

    /**
     * The optional Store ID
     * @type String
     */
    store: $('input[name=store_id]').val(),

    /**
     * Initializes all Dropzone elements.
     * @returns {Void}
     */
    init: function()
    {
        // Initializes the handlers array
        this.handlers = [];

        // Disable auto discover for all elements:
        Dropzone.autoDiscover = false;

        // Setup Dropzone for existing elements
        $('.dropzone').each(function( index, obj ){
            // Creates the Dropzone instance
            Uploader.initOne( obj );
        });
    },

    /**
     * Initializes one Dropzone element.
     * @param {HTMLElement} element
     * @returns {Void}
     */
    initOne: function( element ){
        //
        var temp = $(element).dropzone({
            url: BASE_URL + '/upload',
            params: {
                _token: Uploader.token,
                store_id: Uploader.store
            },
            uploadMultiple: false,
            paramName: 'upload',
            maxFiles: 1,
            maxFilesize: 2048,
            acceptedFiles: 'image/*',
            addRemoveLinks: false,
            // Translation
            dictDefaultMessage: 'Arraste e solte uma imagem aqui',
            dictFallbackMessage: 'Seu navegador não suport a função de arrastar-e-soltar',
            dictFallbackText: 'Utilize o campo abaixo pra enviar a imagem',
            dictFileTooBig: 'A imagem é muito grande ({{filesize}}MiB). Tamanho máximo permitido: {{maxFilesize}}MiB.',
            dictInvalidFileType: 'Você não pode enviar arquivos desse tipo',
            dictResponseError: 'Nosso servidor respondeu com o código {{statusCode}}.',
            dictCancelUpload: 'Cancelar envio',
            dictUploadCanceled: 'Envio cancelado',
            dictCancelUploadConfirmation: 'Tem certeza de que deseja cancelar esse envio?',
            dictRemoveFile: 'Remover imagem',
            dictRemoveFileConfirmation: 'Tem certeza de que deseja remover essa imagem?',
            dictMaxFilesExceeded: 'Você não pode enviar mais arquivos',
            init: function(){
                // Function to show existing file
                this.addExistingFile = function( file, thumbnail_url, response ){
                    // Push file to collection
                    this.files.push( file );
                    // Emulate event to create interface
                    this.emit('addedfile', file);
                    // Add thumbnail url
                    this.emit('thumbnail', file, thumbnail_url);
                    // Add status processing to file
                    this.emit('processing', file);
                    // Add status success to file AND RUN EVENT success from responce
                    this.emit('success', file, response, false);
                    // Add status complete to file
                    this.emit('complete', file);
                };

                // Handler for success event
                this.on('success', function(file, xhr){
                    //
                    var json = JSON.parse( file.xhr.response );
                    var $inp = $(element).closest('.form-group').find('input[type=hidden]');

                    $inp.val( json.id ).trigger('change');
                });

                // Handler for added file event
                this.on('addedfile', function(file){
                    // Create remove button
                    var removeButton = Dropzone.createElement('<button type="button" class="btn btn-secondary">Remover</button>');
                    // Capture the Dropzone instance as closure.
                    var _self = this;
                    // Listen to the click event
                    removeButton.addEventListener('click', function(e){
                        // Make sure the button click doesn't submit the form
                        e.preventDefault();
                        e.stopPropagation();
                        // Remove the file preview.
                        //_self.removeFile(file);
                        console.log( _self );
                        // Get file id
                        var file_id = $(_self).closest('div.form-group').find('input[type=hidden]').val();
                        // Remove from server
                        //Uploader.remove( file_id );
                    });
                    // Add the button to the file preview element.
                    file.previewElement.appendChild(removeButton);
                });
            }
        });

        Uploader.handlers.push( temp );
    },

    /**
     * Remove an existing uploaded image.
     * @param {Number} media_id
     * @returns {Void}
     */
    remove: function( media_id )
    {
        //console.log( media_id );

        var url = BASE_URL + '/upload/'+ media_id +'/delete';
        var data = { referer: document.location.href };

        Common.post( url, data, null, Common.saveHandler );
    },

    /**
     * Adds a new Dropzone area
     * @param {Element} _sender
     * @returns {Void}
     */
    addDropzone: function( _sender )
    {
        // Sets the elements
        var $container = $('<div class="form-group col-md-3"><input type="hidden" name="images[]" value=""></div>');
        var $new_dz    = $('<div class="dropzone"></div>');

        $(_sender).closest('div.form-group').before( $container );
        $container.append( $new_dz );

        // Init dropzone
        Uploader.initOne( $new_dz );
    }
};
