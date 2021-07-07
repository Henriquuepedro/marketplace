/*
 * Common javascript file
 * @author Leandro Antonello <lantonello@gmail.com>
 *
 */

var Common = Common || {
    /**
     * The Html for activity indicator.
     * @type String
     */
    activityIndicator: '<i class="fa fa-2x fa-spinner fa-pulse fa-fw"></i>',

    /**
     * The ajax object.
     * @type XmlHttpRequest
     */
    xhr: null,

    /**
     * An usefull variable for some usefull operations
     * @type
     */
    target: null,

    /**
     * Performs a XmlHttpRequest GET.
     * @param {String} route
     * @param {Object} params
     * @param {String} target
     * @param {Function} callback
     * @returns {Void}
     */
    get: function( route, params, target, callback )
    {
        var _res;
        var _args;
        var _url  = BASE_URL + '/' + route;

        if( params )
            _args = $.param( params );

        // Request
        this.xhr = $.ajax({
            url: _url,
            type: 'GET',
            data: _args,
            complete: function( jqXHR, textStatus )
            {
                _res = ( jqXHR.responseJSON ? jqXHR.responseJSON : jqXHR.responseText );

                //console.log( _res );

                // Callback
                if( callback && target )
                    callback( _res, target );
                else
                    Common.putSource( _res, target );
            }
        });
    },

    /**
     * Writes a content loaded by a generic get method.
     * @param {String} content
     * @param {String} target
     * @returns {Void}
     */
    putSource: function( content, target )
    {
        $( target ).html( content );
    },

    /**
     * Performs a XmlHttpRequest.
     * @param {String} route
     * @param {String} method
     * @param {Object} fields
     * @param {Element} sender
     * @param {Function} callback
     * @returns {Void}
     */
    ajax: function( route, method, fields, sender, callback )
    {
        var _ladda;

        // Append the csrf token to fields
        fields._token = $('input[name=_token]').val();

        this.xhr = $.ajax({
            url: route,
            type: method,
            data: fields,
            beforeSend: function( xhr )
            {
                // Disable button
                if( $(sender).hasClass('ladda-button') )
                {
                    _ladda = Ladda.create(sender);
                    _ladda.start();
                }
                else
                    Common.disableButton( sender );
            },
            complete: function( jqXHR, textStatus )
            {
                // Re-enable button
                if( $(sender).hasClass('ladda-button') )
                    _ladda.stop();
                else
                    Common.enableButton( sender );

                // Call response handler
                if( callback )
                    callback( jqXHR.responseJSON );
                else
                    Common.responseHandler( jqXHR.responseJSON );
            }
        });
    },

    /**
     * Performs a generic Post request.
     * @param {String} route
     * @param {Object} fields
     * @param {Element} sender
     * @param {Function} callback
     * @returns {Void}
     */
    post: function( route, fields, sender, callback )
    {
        this.ajax( route, 'POST', fields, sender, callback );
    },

    /**
     * Performs a generic DELETE request.
     * @param {String} route
     * @param {Object} fields
     * @param {Element} sender
     * @param {Function} callback
     * @returns {Void}
     */
    del: function( route, fields, sender, callback )
    {
        this.ajax( route, 'DELETE', fields, sender, callback );
    },

    /**
     * Alias to postForm function
     * @param {Button} _sender
     * @param {Function} callback
     * @returns {Void}
     */
    formPost: function( _sender, _callback )
    {
        this.postForm( _sender, _callback );
    },

    /**
     * Performs a XmlHttpRequest from an HTML form.
     * @param {Button} _sender
     * @param {Function} callback
     * @returns {Void}
     */
    postForm: function( _sender, callback )
    {
        var $form   = $(_sender).closest('form');
        var _method = $form.attr('method');
        var _action = $form.attr('action');
        var _data   = $form.serialize();
        var _ladda;

        this.xhr = $.ajax({
            url: _action,
            type: _method,
            data: _data,
            beforeSend: function( xhr )
            {
                // Disable button
                if( $(_sender).hasClass('ladda-button') )
                {
                    _ladda = Ladda.create(_sender);
                    _ladda.start();
                }
                else
                    Common.disableButton( _sender );
            },
            complete: function( jqXHR, textStatus )
            {
                // Re-enable button
                if( $(_sender).hasClass('ladda-button') )
                    _ladda.stop();
                else
                    Common.enableButton( _sender );

                // Call response handler
                if( callback )
                    callback( jqXHR.responseJSON );
                else
                    Common.responseHandler( jqXHR.responseJSON );
            }
        });
    },

    /**
     * Generic response handler.
     * @param {Object} json
     * @returns {Void}
     */
    responseHandler: function( json )
    {
        var message;

        if( ! json.hasOwnProperty('success') )
        {
            // HTTP Status 422
            if( ! json.hasOwnProperty('errors') )
            {
                message = 'Ocorreu uma falha na operação. Por favor, tente novamente.';
            }
            else
            {
                for( var key in json.errors )
                {
                    if( json.errors.hasOwnProperty(key) )
                    {
                        message = json.errors[key][0];
                        break;
                    }
                }
            }

            Common.notifyError( message );
            return;
        }

        if( json.success === false )
        {
            Common.notifyError( json.message );
            return;
        }

        Common.notifySuccess( json.message );
    },

    /**
     * Default save operation
     * @param {Object} json
     * @returns {Void}
     */
    save: function( _sender )
    {
        this.postForm( _sender, Common.saveHandler );
    },

    /**
     * Default handler for save operations
     * @param {Object} json
     * @returns {Void}
     */
    saveHandler: function( json )
    {
        Common.responseHandler( json );

        if( json.success !== true )
            return;

        if( json.stay )
            return;

        if( json.next_page )
        {
            setTimeout(function(){
                Common.toUrl( json.next_page );
            }, 1000);

            return;
        }
        else
        {
            setTimeout(function(){
                document.location.reload();
            }, 1000);
        }
    },

    /**
     * Dispatch a delete action
     * @param {Event} event
     * @returns {Void}
     */
    delete: function( _url, _data )
    {
        // SweetAlert
        Swal.fire({
            title: 'Excluir item',
            text: 'Tem certeza que deseja excluir esse item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, excluir item!',
            cancelButtonText: 'Cancelar',
            //confirmButtonColor: '#3085d6',
            //cancelButtonColor: '#d33',
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        }).then((result) => {
            if( result.isConfirmed )
            {
                // Add Token and Method to data
                _data._token  = $('input[name=_token]').val();
                _data._method = 'DELETE';

                Common.ajax( _url, 'POST', _data, null, Common.deleteHandler );
            }
        });
    },

    /**
     * Default handler for Delete operations
     * @param {Object} json
     * @returns {Void}
     */
    deleteHandler: function( json )
    {
        Common.responseHandler( json );

        if( json.success !== true )
            return;

        setTimeout(function(){
            document.location.reload();
        }, 1000);
    },

    // BUTTONS ================================================================
    /**
     * Disable the given Button
     * @param {Button} _element
     * @returns {Void}
     */
    disableButton: function( _element )
    {
        if( (_element === null) || (typeof _element === 'undefined') )
            return;

        // Get button content
        var content = $(_element).html();

        // Store the content inside element's data
        $(_element).data( 'label', content );

        // Change the content
        $(_element).html( this.activityIndicator + content );

        // Disable button
        $(_element).addClass('disabled');
        $(_element).prop('disabled', true);
    },

    /**
     * Re enable the given button.
     * @param {Button} _element
     * @returns {Void}
     */
    enableButton: function( _element )
    {
        if( (_element === null) || (typeof _element === 'undefined') )
            return;

        // Restore the button content
        var content = $(_element).data('label');

        $(_element).html( content );

        // Re-enable the button
        $(_element).removeClass('disabled');
        $(_element).prop('disabled', false);
    },

    /**
     * Slugify the value of given "_source" and put into "_target", using "_slug_url".
     * @param {Element} _source
     * @param {Element} _target
     * @param {String} _slug_url
     * @returns {Void}
     */
    slugify: function( _source, _target, _slug_url )
    {
        if( (_source === null) || (typeof _source === 'undefined') )
            return;

        this.target = _target;

        // Keyup event
        $(_source).blur(function(){
            // Get value
            var _value = $(this).val();

            if( _value.length === 0 )
                return;

            // Data
            var _data = { input: _value };

            Common.get( _slug_url, _data, _target, Common.slugifyHandler );
        });
    },

    slugifyHandler: function( response, target )
    {
        //console.log( response );
        $(target).val( response.result );
    },

    // CEP ====================================================================
    /**
     * Handler for CEP input change
     * @param {Input} _element
     * @returns {Void}
     */
    getAddress: function( _element )
    {
        // Get CEP
        var cep = $(_element).val();

        if( cep.length < 9 )
            return;

        // TODO: Block page

        // Clean CEP
        cep = cep.replace(/\D/g,'');

        var url = 'https://viacep.com.br/ws/'+ cep +'/json/';

        // Search for cep
        this.ajax( url, 'GET', {}, null, this.fillAddress );
    },
    /**
     * Handler for CEP search
     * @param {JSON} response
     * @returns {Void}
     */
    fillAddress: function( response )
    {
        //console.log( response );
        $('input[name=address]').val( response.logradouro );
        $('input[name=district]').val( response.bairro );
        $('input[name=city]').val( response.localidade );
        //$('select[name=state]').val( response.uf );

        var state_id;

        switch( response.uf )
        {
            case 'AC': state_id = 1; break;
            case 'AL': state_id = 2; break;
            case 'AM': state_id = 3; break;
            case 'AP': state_id = 4; break;
            case 'BA': state_id = 5; break;
            case 'CE': state_id = 6; break;
            case 'DF': state_id = 7; break;
            case 'ES': state_id = 8; break;
            case 'GO': state_id = 9; break;
            case 'MA': state_id = 10; break;
            case 'MG': state_id = 11; break;
            case 'MS': state_id = 12; break;
            case 'MT': state_id = 13; break;
            case 'PA': state_id = 14; break;
            case 'PB': state_id = 15; break;
            case 'PE': state_id = 16; break;
            case 'PI': state_id = 17; break;
            case 'PR': state_id = 18; break;
            case 'RJ': state_id = 19; break;
            case 'RN': state_id = 20; break;
            case 'RO': state_id = 21; break;
            case 'RR': state_id = 22; break;
            case 'RS': state_id = 23; break;
            case 'SC': state_id = 24; break;
            case 'SE': state_id = 25; break;
            case 'SP': state_id = 26; break;
            case 'TO': state_id = 27; break;
        }

        $('select[name=state_id]').val( state_id ).trigger('change');

        if( response.logradouro )
            $('input[name=number]').focus();
        else
            $('input[name=address]').focus();
    },

    // NOTIFICATIONS ==========================================================
    /**
     * Shows a notification using Materialize plugin.
     * @param {String} _type
     * @param {String} _text
     * @param {Function} callback
     * @returns {Void}
     */
    notify: function( _type, _text, callback )
    {
        if( Swal )
        {
            Swal.fire({
                icon: _type,
                title: ((_type === 'error') ? 'Atenção!' : 'Sucesso'),
                text: _text
            });
        }
        else
        {
            alert( _text );
        }
    },

    /**
     * Shows an error notification with Toastr plugin.
     * @param {String} message
     * @param {Function} callback
     * @returns {Void}
     */
    notifyError: function( message, callback )
    {
        this.notify( 'error', message, callback );
    },

    /**
     * Shows a warning notification with Toastr plugin.
     * @param {String} message
     * @param {Function} callback
     * @returns {Void}
     */
    notifyWarning: function( message, callback )
    {
        this.notify( 'warning', message, callback );
    },

    /**
     * Shows an info notification with Toastr plugin.
     * @param {String} message
     * @param {Function} callback
     * @returns {Void}
     */
    notifyInfo: function( message, callback )
    {
        this.notify( 'info', message, callback );
    },

    /**
     * Shows a success notification with Toastr plugin.
     * @param {String} message
     * @param {Function} callback
     * @returns {Void}
     */
    notifySuccess: function( message, callback )
    {
        this.notify( 'success', message, callback );
    },

    // NAVIGATION =================================================================================
    /**
     * Navigate to given route.
     * @param {String} target
     * @returns {Void}
     */
    navTo: function( target )
    {
        document.location.href = BASE_URL + target;
    },

    /**
     * Go to given absolute url
     * @param {string} absolute_url
     * @returns {Void}
     */
    toUrl: function( absolute_url )
    {
        document.location.href = absolute_url;
    }
};
