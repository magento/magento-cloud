/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'mageUtils',
    'Magento_Staging/js/preview/preview'
], function (utils, Preview) {
    'use strict';

    describe('Staging preview update', function () {
        var preview;

        beforeEach(function () {
            spyOn(Preview.prototype, 'initDOMElements').and.returnValue(true);
            spyOn(utils, 'normalizeDate').and.returnValue('01/01/01');

            preview = new Preview;
        });

        it('checks component properties and methods', function () {
            expect(preview.getStoreOfUrl).toBeDefined();
            expect(preview.applyStoreToUrl).toBeDefined();

            expect(typeof preview.getStoreOfUrl).toEqual('function');
            expect(typeof preview.applyStoreToUrl).toEqual('function');
        });

        it('checks getStoreOfUrl method', function () {
            var url = 'http://m2.dev/second/catalog/product/view/11/?SID=SID&__store=default',
                stores = {
                    defaultStore: {
                        baseUrl: 'http://m2.dev/'
                    },
                    defaultStoreView: {
                        baseUrl: 'http://m2.dev/secondview/'
                    },
                    secondStore: {
                        baseUrl: 'http://m2.dev/second/'
                    }
                };

            spyOn(preview, 'getStores').and.returnValue(stores);
            expect(preview.getStoreOfUrl(url)).toEqual(stores.secondStore);
        });
    });
});
