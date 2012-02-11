travi.namespace('test', {
    testCase: TestCase
});
sinon.log = function () {
    jstestdriver.console.log.apply(jstestdriver.console, arguments);
};

travi.templates.preLoad('entityList', '/test/php/framework/test/js/templates/entity-list.tmpl');
