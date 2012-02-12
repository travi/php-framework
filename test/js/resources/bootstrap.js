/*global console, jstestdriver*/
travi.namespace('test', {
    testCase: TestCase
});
console.log = sinon.log = function () {
    jstestdriver.console.log.apply(jstestdriver.console, arguments);
};

$.fx.off = true;

travi.templates.preLoad('entityList', '/test/php/framework/test/js/templates/entity-list.tmpl');
