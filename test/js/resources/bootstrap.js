/*global console, jstestdriver*/
console.log = function () {
    jstestdriver.console.log.apply(jstestdriver.console, arguments);
};

testCase = TestCase;

$.fx.off = true;