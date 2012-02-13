travi.test.common = {
    restore: function (functionName) {
        if (functionName.restore) {
            functionName.restore();
        }
    }
};