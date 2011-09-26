(function (global) {
    "use strict";

    travi.framework.baseMock = {
        expectations: {},
        calls: [],
        verify: function () {
            var allExpectationsMet = true,
                expectedMethodName,
                expectationCount = 0,
                expectedParameter,
                expectationObject;


            for (expectedMethodName in this.expectations) {
                expectationObject = this.expectations[expectedMethodName];

                if (this.expectations.hasOwnProperty(expectedMethodName)) {

                    expectedParameter = expectationObject.withValue;
                    if (expectedParameter !== undefined) {
                        if (-1 === $.inArray(expectedMethodName + ':' + expectedParameter, this.calls)) {
                            allExpectationsMet = false;
                        }
                    } else if (-1 === $.inArray(expectedMethodName, this.calls)) {
                        allExpectationsMet = false;
                    }
                }
                expectationCount = expectationCount + expectationObject.timesExpected;
            }

            if (expectationCount !== this.calls.length) {
                allExpectationsMet = false;
            }

            return allExpectationsMet;
        },
        expect: function (call, options) {
            if (options.returnValue === undefined) {
                options.returnValue = '';
            }
            if (options.timesExpected === undefined) {
                options.timesExpected = 1;
            }
            this.expectations[call] = {
                withValue: options.withValue,
                returnValue: options.returnValue,
                timesExpected: options.timesExpected
            };
        },
        recordCall: function (call, parameter) {
            if (parameter === undefined) {
                this.calls.push(call);
            } else {
                this.calls.push(call + ':' + parameter);
            }
        },
        init: function () {
            this.expectations = {};
            this.calls = [];
        }
    };
}(this));
