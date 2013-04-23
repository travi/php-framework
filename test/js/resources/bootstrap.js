/*global console, jstestdriver*/
console.log = sinon.log = function () {
    jstestdriver.console.log.apply(jstestdriver.console, arguments);
};

travi.test = travi.test || {};
travi.test.testCase = TestCase;

var contextPath;
if (window.buster) {
    contextPath = buster.env.contextPath + '/include';
} else if (jstestdriver) {
    contextPath = '/test';
}

$.fx.off = true;

travi.templates.init();

travi.templates.preLoad('chooseEnhancement', contextPath + '/client/templates/enhancementVersion.tmpl');

travi.templates.preLoad('update-item', contextPath + '/test/js/templates/stub-update-item.tmpl');
travi.templates.preLoad('entityList', contextPath + '/test/js/templates/entity-list.tmpl');
travi.templates.preLoad('pagination', contextPath + '/test/js/templates/pagination.tmpl');

//$.ajax = function () {
//    fail('ajax not stubbed properly');
//};

