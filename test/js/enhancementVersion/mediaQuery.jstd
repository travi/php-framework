travi.test.testCase('Screen Width Detection', (function () {
    'use strict';

    return {
        templates: travi.templates,
        cookies: travi.cookies,
        enhancements: travi.enhancements,

        setUp: function () {
            sinon.stub(this.templates, 'get').returns({
                then: function (callback) {
                    callback();
                }
            });
            sinon.stub(this.cookies, 'exists').returns(false);
            sinon.stub(this.cookies, 'create');
            sinon.stub(this.cookies, 'value');
        },

        tearDown: function () {
            travi.test.common.restore([
                this.templates.get,
                Modernizr.mq,
                travi.location.refresh,
                this.cookies.exists,
                this.cookies.value,
                this.cookies.create
            ]);
        },

        'test Enhancement version cookie name defined as what server is expecting': function () {
            assertEquals('ev', this.enhancements.constants().ENHANCEMENT_VERSION_KEY);
        },

        'test Enhancement version set to mobile when screen width reported within smartphone range': function () {
            sinon.stub(Modernizr, 'mq').returns(true);
            sinon.stub(travi.location, 'refresh');

            this.enhancements.init();

            sinon.assert.calledOnce(Modernizr.mq);
            sinon.assert.calledWith(Modernizr.mq, 'only screen and (min-width: 320px) and (max-width: 480px)');

            sinon.assert.calledOnce(this.cookies.create);
            sinon.assert.calledWith(
                this.cookies.create,
                this.enhancements.constants().ENHANCEMENT_VERSION_KEY,
                this.enhancements.constants().SMALL_COOKIE_VALUE,
                this.enhancements.constants().DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION
            );
            sinon.assert.calledOnce(travi.location.refresh);
        },

        'test Enhancement version set to desktop at higher screen width': function () {
            sinon.stub(Modernizr, 'mq').returns(false);
            sinon.stub(travi.location, 'refresh');

            this.enhancements.init();

            sinon.assert.calledOnce(Modernizr.mq);
            sinon.assert.calledWith(Modernizr.mq, 'only screen and (min-width: 320px) and (max-width: 480px)');

            sinon.assert.calledOnce(this.cookies.create);
            sinon.assert.calledWith(
                this.cookies.create,
                this.enhancements.constants().ENHANCEMENT_VERSION_KEY,
                this.enhancements.constants().LARGE_COOKIE_VALUE,
                this.enhancements.constants().DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION
            );
            sinon.assert.calledOnce(travi.location.refresh);
        }
    };
}()));