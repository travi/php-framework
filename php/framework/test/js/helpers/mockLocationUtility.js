travi.framework.location = travi.framework.utils.createObjectFrom(travi.framework.baseMock, {
    refresh: function () {
        this.recordCall('refresh');
    }
});