travi.framework.entityList = (function () {
    "use strict";

    var buttonText,
        constants = travi.constants;

    constants.set('PAGE_EVENT', 'updates-loaded');
    constants.set('HIDDEN_CLASS', 'outOfRange');

    function setMessage(confirmation) {
        $("#confirmation").text(confirmation);
    }

    function setText(text) {
        buttonText = text;
    }

    function getText() {
        return buttonText;
    }

    function confirm() {
        var $form = $(this);

        $("#confirmation").dialog("option", "buttons", [
            {
                text:   travi.framework.entityList.getButtonText(),
                click:  function () {
                    $(this).dialog("close");
                    $form.ajaxSubmit({
                        beforeSubmit: function (data, $form) {
                            $form
                                .closest('li')
                                .append('<img src="/resources/shared/img/progress/ajax-spinner.gif" class="loading-indicator"/>');
                        },
                        success: function (data, testStatus, xhr, $form) {
                            var $containingList = $form.closest('ol');

                            $form
                                .closest('li')
                                .parent()
                                .closest('li')
                                .slideUp('slow', function () {
                                    $(this).remove();
                                    $containingList.trigger('entityRemoved');
                                });
                        },
                        dataType: 'json'
                    });
                }
            },
            {
                text:   "Cancel",
                click:  function () {
                    $(this).dialog("close");
                }
            }
        ]);
        $("#confirmation").dialog("open");

        return false;
    }

    function initPagination() {
        $('#moreUpdates, #previousUpdates').click(function () {
            var $this = $(this);

            $.getJSON($this.attr('href'), function (data) {
                var i,
                    updateContainer = data.updates.updateList,
                    updates = updateContainer.entities,
                    updateCount = updates.length,
                    $updateList = $('ol.entityList'),
                    $prevUpdates = $('#previousUpdates'),
                    $divider = $('.pipeDivider');

                for (i = 0; i < updateCount; i = i + 1) {
                    $updateList.append('<li>&nbsp;</li>');
                }

                if (updateContainer.offset <= 0 || !updateContainer.offset) {
                    $prevUpdates.parent().addClass(constants.get('HIDDEN_CLASS'));
                    $divider.addClass(constants.get('HIDDEN_CLASS'));
                } else {
                    $prevUpdates.parent().removeClass(constants.get('HIDDEN_CLASS'));
                    $divider.removeClass(constants.get('HIDDEN_CLASS'));
                }

                $this.trigger(constants.get('PAGE_EVENT'));
            });

            return false;
        });
    }

    function init() {
        $("li.remove-item form")
            .hide()
            .after("<a class='item-action' href='#'>Remove</a>");
        $('ol.entityList').delegate('li.remove-item a.item-action', 'click', function () {
            $(this).prev("form").submit();
            return false;
        });
        $("body").append("<div id='confirmation' title='Are you sure?'></div>");
        $("#confirmation").dialog({
            autoOpen:   false,
            modal:      true,
            resizable:  false
        });
        $("form.item-action").submit(confirm);
        $('a.add-item').button({icons: {primary: 'ui-action-circle-plus'}});
        initPagination();
    }

    $(document).ready(function () {
        init();
    });

    return {
        init                    : init,
        setConfirmationMessage  : setMessage,
        setButtonText           : setText,
        getButtonText           : getText,
        constants               : constants
    };
}());