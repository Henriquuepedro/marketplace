/*
 * Input masks definition
 * @author Leandro Antonello <lantonello@gmail.com>
 *
 */

var Masks = Masks || {

    zipcode: '99999-999',
    telephone: '+55 (99) 9999[9]-9999',
    url: '(\\http\\s://)|(\\http://)i{+}',


    /**
     * Initializes the masks on input elements.
     * @returns {Void}
     */
    init: function()
    {
        // Zip Code
        $('.msk-zip').inputmask({
            mask: Masks.zipcode
        });

        // Phones
        $('.msk-phone').inputmask({
            mask: Masks.telephone,
            greedy: false
        });

        // URLs
        $('.msk-url').inputmask({
            mask: Masks.url,
            greedy: false,
            insertMode: !1,
            autoUnmask: !1,
            definitions: {
                'i': {
                    validator: ".",
                    cardinality: 1,
                    casing: "lower"
                }
            }
        });
    }
};
