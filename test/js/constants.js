(function () {
    "use strict";

    TestCase("ConstantsTest", {
        setUp: function () {
            this.key = 'CONST';
            this.value = 'some value';
        },

        "test should store constant key and value":
        function () {
            travi.constants.set(this.key, this.value);

            assertObject(travi.constants);
            assertFunction(travi.constants.set);
            assertFunction(travi.constants.get);
            assertEquals(this.value, travi.constants.get(this.key));
        },

        "test constant cannot be overridden": function () {
            travi.constants.set(this.key, this.value);

            travi.constants.set(this.key, 'some other value');
            if (typeof travi.constants.constants !== 'undefined') {
                travi.constants.constants[this.key] = 'yet another value';
            }

            assertEquals(this.value, travi.constants.get(this.key));
        },

        "test constants should be exposed twice (correct name for this pattern?) from the travi object API": function () {
            assertObject(travi.enableConstants);
            assertSame(travi.constants, travi.enableConstants);
        }
    });
}());